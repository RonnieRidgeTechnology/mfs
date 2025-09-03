<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\MembershipFeeSetting;
use App\Models\User;
use App\Models\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Imports\TransactionImport;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Mail\MemberCreatedMail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // login page
    public function loginpage()
    {
        return view('admin.login.login');
    }
    //login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Log successful login activity with action_type 'login' and activity as "UserName login successfully"
            $user = Auth::user();
            $userName = $user ? $user->name : 'Unknown User';
            $activityMessage = $userName . ' login successfully';
            log_activity($activityMessage, $user, 'login'); // 👈 action_type now correctly set to 'login'

            // Add success message with user name and link
            $welcomeMessage = 'Welcome to MFS, <strong>' . e($userName) . '</strong>!';
            return redirect()->route('dashboard')->with('success', $welcomeMessage);
        }

        // Log failed login attempt (no user context)
        log_activity('Login Failed', null, 'login'); // 👈 action_type now correctly set to 'login'

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }
    //dashboard
    public function dashbaord(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');
        $period = $request->input('period');
        $perPage = $request->input('per_page', 5);

        // User search logic
        $userQuery = User::query()->whereIn('type', ['admin', 'member']);

        if ($search) {
            $userQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($type && in_array($type, ['admin', 'member'])) {
            $userQuery->where('type', $type);
        }

        $searchedUsers = $userQuery->orderBy('created_at', 'desc')->paginate(10);

        // Metrics
        $totalAdmins = User::where('type', 'admin')->count();
        $totalMembers = User::where('type', 'member')->count();
        $totalTransactions = Transaction::count();
        $totalGuests = User::where('is_guest', 1)->count();
        $totalFlaggedTransactions = Transaction::where('flag_status', 0)->count();

        // myTotalTransactions: show the logged-in user's own transactions count
        $myTotalTransactions = null;
        if (auth()->check()) {
            $myTotalTransactions = Transaction::where('user_id', auth()->id())->count();
        }

        // Activity logs (with period filter)
        $activityLogQuery = ActivityLog::with('user')->orderBy('created_at', 'desc');

        if ($period) {
            $now = now();
            switch ($period) {
                case 'today':
                    $activityLogQuery->whereDate('created_at', $now->toDateString());
                    break;
                case 'week':
                    $activityLogQuery->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()]);
                    break;
                case 'month':
                    $activityLogQuery->whereBetween('created_at', [$now->startOfMonth(), $now->endOfMonth()]);
                    break;
                case 'year':
                    $activityLogQuery->whereBetween('created_at', [$now->startOfYear(), $now->endOfYear()]);
                    break;
                case 'last_week':
                    $activityLogQuery->whereBetween('created_at', [
                        $now->copy()->subWeek()->startOfWeek(),
                        $now->copy()->subWeek()->endOfWeek()
                    ]);
                    break;
                case 'last_month':
                    $activityLogQuery->whereBetween('created_at', [
                        $now->copy()->subMonth()->startOfMonth(),
                        $now->copy()->subMonth()->endOfMonth()
                    ]);
                    break;
                case 'last_year':
                    $activityLogQuery->whereBetween('created_at', [
                        $now->copy()->subYear()->startOfYear(),
                        $now->copy()->subYear()->endOfYear()
                    ]);
                    break;
            }
        }

        $activityLogs = $activityLogQuery->paginate($perPage);
        $totalActivityLogs = (clone $activityLogQuery)->count();

        // CHART: Group by hour + keywords in 'activity' field, for the current month and previous months
        // We'll build $chartLogsByMonth as [ 'YYYY-MM' => [hourly chartLogs array], ... ]
        $chartLogsByMonth = [];
        $activityLogModel = new ActivityLog();

        // Get all months with activity logs (for the month filter)
        $months = ActivityLog::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month")
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->pluck('month')
            ->toArray();

        // Always include the current month, even if no logs yet
        $currentMonth = now()->format('Y-m');
        if (!in_array($currentMonth, $months)) {
            array_unshift($months, $currentMonth);
        }

        foreach ($months as $month) {
            // Get all logs for this month
            $logs = ActivityLog::with('user')
                ->whereYear('created_at', substr($month, 0, 4))
                ->whereMonth('created_at', substr($month, 5, 2))
                ->get()
                ->groupBy(function ($log) {
                    return $log->created_at->format('H');
                })
                ->map(function ($group) {
                    return [
                        'login' => [
                            'count' => $group->filter(fn($log) => str_contains(strtolower($log->activity), 'login'))->count(),
                            'users' => $group->filter(fn($log) => str_contains(strtolower($log->activity), 'login'))->pluck('user.name')->unique()->values(),
                        ],
                        'logout' => [
                            'count' => $group->filter(fn($log) => str_contains(strtolower($log->activity), 'logout'))->count(),
                            'users' => $group->filter(fn($log) => str_contains(strtolower($log->activity), 'logout'))->pluck('user.name')->unique()->values(),
                        ],
                        'created' => [
                            'count' => $group->filter(fn($log) => str_contains(strtolower($log->activity), 'created'))->count(),
                            'users' => $group->filter(fn($log) => str_contains(strtolower($log->activity), 'created'))->pluck('user.name')->unique()->values(),
                        ],
                        'updated' => [
                            'count' => $group->filter(fn($log) => str_contains(strtolower($log->activity), 'updated'))->count(),
                            'users' => $group->filter(fn($log) => str_contains(strtolower($log->activity), 'updated'))->pluck('user.name')->unique()->values(),
                        ],
                        'deleted' => [
                            'count' => $group->filter(fn($log) => str_contains(strtolower($log->activity), 'deleted'))->count(),
                            'users' => $group->filter(fn($log) => str_contains(strtolower($log->activity), 'deleted'))->pluck('user.name')->unique()->values(),
                        ]
                    ];
                });

            $chartLogs = [];
            foreach (range(0, 23) as $hour) {
                $hourStr = str_pad($hour, 2, '0', STR_PAD_LEFT);
                $entry = ['hour' => "{$hourStr}:00"];
                foreach (['login', 'logout', 'created', 'updated', 'deleted'] as $type) {
                    $entry["{$type}_count"] = $logs[$hourStr][$type]['count'] ?? 0;
                    $entry["{$type}_users"] = $logs[$hourStr][$type]['users'] ?? collect();
                }
                $chartLogs[] = $entry;
            }
            $chartLogsByMonth[$month] = $chartLogs;
        }

        // For backward compatibility, also provide $chartLogs for the current month
        $chartLogs = $chartLogsByMonth[$currentMonth] ?? [];

        return view('admin.login.dashboard', compact(
            'activityLogs',
            'searchedUsers',
            'search',
            'type',
            'period',
            'perPage',
            'totalActivityLogs',
            'totalAdmins',
            'totalMembers',
            'totalTransactions',
            'totalGuests',
            'totalFlaggedTransactions',
            'chartLogs',
            'chartLogsByMonth',
            'currentMonth',
            'myTotalTransactions' // Pass to view
        ));
    }
    //logout
    public function logout(Request $request)
    {
        $user = Auth::user();
        $userName = $user ? $user->name : 'Unknown User';
        $activityMessage = $userName . ' logout successful';

        // Only log if user is actually logged in (not system)
        if ($user) {
            // Log logout activity before logging out, with full message and action_type 'logout'
            log_activity($activityMessage, $user, 'logout');
        }

        Auth::logout(); // Logs out the user

        $request->session()->invalidate(); // Invalidates the session
        $request->session()->regenerateToken(); // Regenerates the CSRF token

        // Add a logout message to the session
        return redirect('/login')->with('success', 'You have been logged out successfully.');
    }
    //admin create
    public function adminindex(Request $request)
    {
        $user = auth()->user();
        if (!in_array($user->type, ['super-admin'])) {
            abort(403, 'Unauthorized');
        }
        $status = $request->input('status'); // 'active', 'inactive', or null
        $startDate = $request->input('start_date'); // format: Y-m-d
        $endDate = $request->input('end_date');     // format: Y-m-d
        $search = $request->input('search'); // search term for name, email, or phone

        $query = User::where('type', 'admin');

        if ($status === 'active') {
            $query->where('status', 1);
        } elseif ($status === 'inactive') {
            $query->where('status', 0);
        }

        // Filter by start date
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        // Filter by end date
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Search by name, email, or phone
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('unique_id', 'like', '%' . $search . '%');
            });
        }

        $perPage = $request->input('per_page', 5);
        $admins = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return view('admin.superadmin.adminmodule.index', compact('admins', 'status', 'startDate', 'endDate', 'search'));
    }
    // Show the form to create a new admin (for super-admins)
    public function createAdmin()
    {
        // Only allow super-admins
        if (auth()->user()->type !== 'super-admin') {
            abort(403, 'Unauthorized');
        }

        return view('admin.superadmin.adminmodule.create');
    }
    // Handle the creation of a new admin by super admin
    public function storeOrUpdateAdmin(Request $request, $id = null)
    {
        if (!auth()->check() || auth()->user()->type !== 'super-admin') {
            abort(403, 'Unauthorized');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email' . ($id ? ',' . $id : ''),
            'password' => $id ? 'nullable|string|min:6|confirmed' : 'required|string|min:6|confirmed',
            'password_confirmation' => $id ? 'nullable|string|min:6' : 'required|string|min:6',
            'phone' => 'required|string|max:20',
            'profile_image' => 'nullable|image|max:10240',
            'status' => 'required|boolean',
        ];

        $request->validate($rules);

        $data = $request->only(['name', 'email', 'phone', 'street', 'area', 'town', 'postal_code', 'status']);
        $data['type'] = 'admin';
        $data['is_user'] = 1;

        // Unique ID generator
        $generateUniqueId = function ($name) {
            $firstChar = strtoupper(substr($name, 0, 1));
            $existingIds = \App\Models\User::where('unique_id', 'like', $firstChar . '%')
                ->pluck('unique_id')
                ->map(function ($id) use ($firstChar) {
                    return (int) str_replace($firstChar, '', $id);
                })
                ->toArray();
            $next = 1;
            while (in_array($next, $existingIds)) $next++;
            return $firstChar . $next;
        };

        $plainPassword = null;
        if ($request->filled('password')) {
            $plainPassword = $request->password;
            $data['password'] = bcrypt($plainPassword);
        }

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/profile_images'), $imageName);
            $data['profile_image'] = 'uploads/profile_images/' . $imageName;
        }

        if ($id) {
            // Update admin
            $admin = \App\Models\User::where('id', $id)->where('type', 'admin')->firstOrFail();
            $admin->update($data);
            $message = 'Admin updated successfully.';
        } else {
            // Create admin
            $data['unique_id'] = $generateUniqueId($data['name']);
            $admin = \App\Models\User::create($data);
            $message = 'Admin created successfully.';

            // Send email only if setting is enabled
            // Try to get the setting from the database (WebSetting model)
            try {
                $webSetting = \App\Models\WebSetting::first();
                $sendAdminCreationEmail = $webSetting && $webSetting->send_admin_creation_email;
            } catch (\Exception $e) {
                $sendAdminCreationEmail = false;
            }

            if ($sendAdminCreationEmail && $plainPassword) {
                try {
                    \Mail::to($admin->email)->send(new \App\Mail\AdminCreatedMail($admin, $plainPassword));
                } catch (\Exception $e) {
                    // Optionally log the error or notify admin
                    // \Log::error('Admin creation email failed: ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('admin.list')->with('success', $message);
    }

    // Edit admin function
    public function editAdmin($unique_id)
    {
        // Only allow super-admins
        if (auth()->user()->type !== 'super-admin') {
            abort(403, 'Unauthorized');
        }

        $admin = User::where('unique_id', $unique_id)->where('type', 'admin')->firstOrFail();
        return view('admin.superadmin.adminmodule.create', compact('admin'));
    }
    // Delete admin function
    public function deleteAdmin($id)
    {
        // Only allow super-admins
        if (auth()->user()->type !== 'super-admin') {
            abort(403, 'Unauthorized');
        }

        $admin = User::where('id', $id)->where('type', 'admin')->firstOrFail();
        $admin->delete();

        return redirect()->route('admin.list')->with('success', 'Admin deleted successfully.');
    }
    //guest page
    public function guestindex(Request $request)
    {
        $user = auth()->user();
        if (!in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $status = $request->input('status'); // 'active', 'inactive', or null
        $startDate = $request->input('start_date'); // format: Y-m-d
        $endDate = $request->input('end_date');     // format: Y-m-d
        $search = $request->input('search'); // search term for name, email, phone, or unique_id
        $alphabet = $request->input('alphabet'); // filter by first letter of unique_id only

        // Query for guest members only
        $query = User::orderBy('created_at', 'desc')
            ->where('type', 'member')
            ->where('is_guest', 1);

        if ($status === 'active') {
            $query->where('status', 1);
        } elseif ($status === 'inactive') {
            $query->where('status', 0);
        }

        // Filter by start date
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        // Filter by end date
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Search by name, email, phone, or unique_id
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('unique_id', 'like', '%' . $search . '%');
            });
        }

        // Alphabet filter: filter by first letter of unique_id only
        if ($alphabet && strlen($alphabet) === 1 && preg_match('/^[A-Z]$/i', $alphabet)) {
            $query->whereRaw('LEFT(unique_id, 1) = ?', [strtoupper($alphabet)]);
        }

        $perPage = $request->input('per_page', 10); // Allow per page selection, default 5
        $members = $query->paginate($perPage);

        // Pass the alphabet filter to the view for UI state
        return view('admin.superadmin.memebermodule.guest', compact('members', 'status', 'startDate', 'endDate', 'search', 'alphabet'));
    }
    // Memeber Crud
    public function memeberindex(Request $request)
    {
        $user = auth()->user();
        if (!in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $status = $request->input('status'); // 'active', 'inactive', or null
        $startDate = $request->input('start_date'); // format: Y-m-d
        $endDate = $request->input('end_date');     // format: Y-m-d
        $search = $request->input('search'); // search term for name, email, phone, or unique_id
        $alphabet = $request->input('alphabet'); // filter by first letter of unique_id only
        $cover = $request->input('cover'); // new filter for cover type
        $memberStatus = $request->input('member_status'); // new filter for member_status
        $year = $request->input('year'); // new filter for year (ANNUAL FEE YEAR FILTER)

        // Query for members (not admins), only real members (not guests)
        $query = User::orderBy('created_at', 'desc')
            ->where('type', 'member')
            ->where('is_guest', 0);

        if ($status === 'active') {
            $query->where('status', 1);
        } elseif ($status === 'inactive') {
            $query->where('status', 0);
        }

        // Filter by start date
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        // Filter by end date
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Filter by cover type if provided and not empty
        if ($cover !== null && $cover !== '') {
            $query->where('cover', $cover);
        }

        // Filter by member_status if provided and not empty
        if ($memberStatus !== null && $memberStatus !== '') {
            $query->where('member_status', $memberStatus);
        }

        // --- ANNUAL FEE YEAR FILTER ---
        // If year is provided, filter members who have paid annual fee for that year (based on transactions)
        if ($year !== null && $year !== '') {
            // Only include members who have at least one transaction in the given year
            $query->whereHas('transactions', function ($q) use ($year) {
                $q->whereYear('date', $year)
                  ->where('status', 'debit'); // Only consider annual fee (debit) transactions
            });
        }

        // Search by name, email, phone, or unique_id
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('unique_id', 'like', '%' . $search . '%');
            });
        }

        // Alphabet filter: filter by first letter of unique_id only
        if ($alphabet && strlen($alphabet) === 1 && preg_match('/^[A-Z]$/i', $alphabet)) {
            $query->whereRaw('LEFT(unique_id, 1) = ?', [strtoupper($alphabet)]);
        }

        $perPage = $request->input('per_page', 10); // Allow per page selection, default 10
        $members = $query->paginate($perPage);

        // Pass the alphabet filter, cover, member_status, and year to the view for UI state
        return view('admin.superadmin.memebermodule.index', compact('members', 'status', 'startDate', 'endDate', 'search', 'alphabet', 'cover', 'memberStatus', 'year'));
    }
    public function createMember()
    {
        // Allow both super-admin and admin to create members
        if (!in_array(auth()->user()->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        return view('admin.superadmin.memebermodule.create');
    }

    public function storeOrUpdateMember(Request $request, $unique_id = null)
    {
        // Only allow super-admins and admin
        if (!in_array(auth()->user()->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        // For update, use the actual user's numeric id for unique email validation
        if ($unique_id) {
            $member = User::where('unique_id', $unique_id)->where('type', 'member')->firstOrFail();
            $userIdForUnique = $member->id;
        } else {
            $userIdForUnique = null;
        }

        // Validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                // Email can be same (no unique validation)
            ],
            'password' => $unique_id ? 'nullable|string|min:6|confirmed' : 'required|string|min:6|confirmed',
            'password_confirmation' => $unique_id ? 'nullable|string|min:6' : 'required|string|min:6',
            'phone' => 'required|string|max:20',
            'street' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'town' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'profile_image' => 'nullable|image|max:10240',
            'cover' => 'nullable|string|max:255',
            'status' => 'required|boolean',
            'member_status' => 'nullable|string|max:255',
        ];

        $messages = [
            'email.unique' => 'The email has already been taken.',
        ];

        $request->validate($rules, $messages);

        $data = $request->only(['name', 'email', 'phone', 'street', 'area', 'town', 'postal_code', 'status', 'cover', 'member_status']);
        $data['type'] = 'member';
        $data['is_user'] = 2;

        // Store plain password for email (only for new member or when changed)
        $plainPassword = $request->password;

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/profile_images'), $imageName);
            $data['profile_image'] = 'uploads/profile_images/' . $imageName;
        }

        $authUser = auth()->user();

        if ($unique_id) {
            // --- Member Update ---
            $isFakeBefore = str_starts_with($member->email, 'member_') && str_ends_with($member->email, '@gmail.com');
            $emailChanged = $member->email !== $data['email'];
            $newEmailNotFake = !str_starts_with($data['email'], 'member_') || !str_ends_with($data['email'], '@gmail.com');

            $member->update($data);
            $message = 'Member updated successfully.';

            // Activity log
            $activity = "User '{$authUser->name}' (ID: {$authUser->id}) updated member '{$member->name}' (Unique ID: {$member->unique_id})";
            log_activity($activity, $authUser);

            // Check if fake email changed to real one → then send GuestPromotedEmail
            if ($isFakeBefore && $emailChanged && $newEmailNotFake) {
                $webSetting = \App\Models\WebSetting::orderBy('id', 'desc')->first();
                $sendEmail = $webSetting && $webSetting->send_guest_promoted_email;

                if ($sendEmail) {
                    try {
                        Log::info('Sending GuestPromotedEmail after fake email updated.');
                        // If plainPassword is empty, pass null so the email can show (Password not available) and show password toggle
                        $plainPasswordForMail = !empty($plainPassword) ? $plainPassword : null;
                        Mail::to($member->email)->send(new \App\Mail\GuestPromotedEmail($member, $plainPassword));
                    } catch (\Exception $e) {
                        Log::error("Failed to send GuestPromotedEmail: " . $e->getMessage());
                    }
                }
            }
        } else {
            // --- Member Creation ---
            $userModel = new User();
            $data['unique_id'] = $userModel->generateUniqueId($data['name']);
            $createdMember = User::create($data);
            $message = 'Member created successfully.';

            // Activity log
            $activity = "{$authUser->name} created member '{$createdMember->name}' (Unique ID: {$createdMember->unique_id})";
            log_activity($activity, $authUser);

            // Send MemberCreatedMail if toggle enabled
            $webSetting = \App\Models\WebSetting::orderBy('id', 'desc')->first();
            $sendEmail = $webSetting && $webSetting->send_member_creation_email;

            if ($sendEmail) {
                try {
                    Log::info('Sending MemberCreatedMail to: ' . $createdMember->email);
                    Mail::to($createdMember->email)->send(new \App\Mail\MemberCreatedMail($createdMember, $plainPassword));
                } catch (\Exception $e) {
                    Log::error("Failed to send MemberCreatedMail: " . $e->getMessage());
                }
            }
        }

        return redirect()->route('member.list')->with('success', $message);
    }

    public function editMember($unique_id)
    {
        // Only allow super-admins
        if (!in_array(auth()->user()->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }


        $member = User::where('unique_id', $unique_id)->where('type', 'member')->firstOrFail();
        return view('admin.superadmin.memebermodule.create', compact('member'));
    }
    public function deleteMember($id)
    {
        // Only allow super-admins
        if (!in_array(auth()->user()->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $authUser = auth()->user();
        $member = User::where('id', $id)->where('type', 'member')->firstOrFail();
        $memberName = $member->name;
        $memberUniqueId = $member->unique_id;

        $member->delete();

        // Activity log: show who deleted, and which member (name and unique_id)
        $activity = "User '{$authUser->name}' (ID: {$authUser->id}) deleted member '{$memberName}' (Unique ID: {$memberUniqueId})";
        log_activity($activity, $authUser);

        return redirect()->route('member.list')->with('success', 'Member deleted successfully.');
    }
    //profile
    public function profile()
    {
        $user = auth()->user();
        return view('admin.login.profile', compact('user'));
    }
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
        ];

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/profile_images'), $imageName);
            $data['profile_image'] = 'uploads/profile_images/' . $imageName;

            // Optionally delete old image
            if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                @unlink(public_path($user->profile_image));
            }
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',      // at least one uppercase letter
                'regex:/[a-z]/',      // at least one lowercase letter
                'regex:/[0-9]/',      // at least one number
            ],
        ], [
            'password.regex' => 'Password must be at least 8 characters and contain at least one uppercase letter, one lowercase letter, and one number.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully.');
    }
    //password reset
    public function showResetForm()
    {
        return view('admin.login.forget');
    }
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'We can\'t find a user with that email address.']);
        }

        // Generate a 6-digit OTP
        $otp = rand(100000, 999999);

        // Store OTP in cache for 5 minutes (you can use DB if preferred)
        Cache::put('otp_' . $user->email, $otp, now()->addMinutes(5));

        // Email template is still same — just pass OTP instead of reset link
        Mail::send('emails.password_reset', ['user' => $user, 'otp' => $otp], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Reset Password OTP');
        });

        // Add a success message
        return redirect()->route('otp.verify.view')->with([
            'email' => $user->email,
            'success' => 'An OTP has been sent to your email address. Please check your inbox.'
        ]);
    }
    //OTP
    public function showOtpForm(Request $request)
    {
        $email = session('email') ?? $request->email;

        if (!$email) {
            return redirect()->route('reset')->withErrors(['email' => 'Email is required.']);
        }

        return view('admin.login.otp', compact('email'));
    }
    // OTP verfied
    public function checkOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        // Compare with stored OTP (assuming you're storing OTP in DB or session)
        $storedOtp = session('otp_code'); // or get from DB

        if ($validated['otp'] === $storedOtp) {
            return response()->json(['valid' => true]);
        }

        return response()->json(['valid' => false]);
    }

    public function verifyOtpLive(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
            'email' => 'required|email',
        ]);

        $cachedOtp = Cache::get('otp_' . $request->email);

        if ((int)$request->otp == $cachedOtp) {
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'failed']);
    }
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6'
        ]);

        $cachedOtp = Cache::get('otp_' . $request->email);

        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        // OTP is valid — allow user to reset password
        return redirect()->route('password.reset.otp', ['email' => $request->email])
            ->with('success', 'OTP verified successfully. Please reset your password.');
    }
    //password reset
    public function showPasswordResetFormOtp(Request $request)
    {
        $email = $request->query('email');
        return view('admin.login.reset', compact('email'));
    }
    //password reset submission
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        Cache::forget('otp_' . $request->email); // Remove used OTP

        return redirect('/login')->with('success', 'Your password has been reset!');
    }
    // Membership Settings
    public function Membershipindex()
    {
        $user = auth()->user();
        if (!in_array($user->type, ['super-admin'])) {
            abort(403, 'Unauthorized');
        }
        // Show all membership fee settings
        $perPage = request()->input('per_page', 10);
        $settings = MembershipFeeSetting::paginate($perPage);
        return view('admin.superadmin.Membershipmodule.inedx', compact('settings'));
    }
    public function Membershipstore(Request $request)
    {
        $request->validate([
            'member_type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'year' => 'required|integer|min:1900|max:2100',
        ]);

        // Check if a setting with the same member_type and year already exists
        $exists = MembershipFeeSetting::where('member_type', $request->member_type)
            ->where('year', $request->year)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['year' => 'A membership fee setting for this member type and year already exists.']);
        }

        MembershipFeeSetting::create([
            'member_type' => $request->member_type,
            'amount' => $request->amount,
            'year' => $request->year,
        ]);

        return redirect()->back()->with('success', 'Membership fee setting created successfully.');
    }
    public function Membershipedit($id)
    {
        $setting = MembershipFeeSetting::findOrFail($id);
        return response()->json($setting);
    }
    public function Membershipupdate(Request $request, $id)
    {
        $request->validate([
            'member_type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'year' => 'required|integer|min:1900|max:2100',
        ]);

        // Check if a setting with the same member_type and year already exists (excluding current record)
        $exists = MembershipFeeSetting::where('member_type', $request->member_type)
            ->where('year', $request->year)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['year' => 'A membership fee setting for this member type and year already exists.']);
        }

        $setting = MembershipFeeSetting::findOrFail($id);
        $setting->update([
            'member_type' => $request->member_type,
            'amount' => $request->amount,
            'year' => $request->year,
        ]);
        return redirect()->back()->with('success', 'Membership fee setting updated successfully.');
    }
    //Transaction import
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,xls'
        ]);


        try {
            // Create import instance
            $import = new \App\Imports\TransactionImport();
            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));

            // Get results from the import class
            $flaggedRecords = $import->getFlagStatusZeroRecords(); // These are the actual flagged transactions (duplicates)
            $flagStatusZeroRecords = $import->getFlagStatusZeroRecords();
            $newTransactions = $import->getNewTransactions();
            $guestUsers = $import->getGuestUsers();
            $noIdFlaggedTransactions = $import->getNoIdFlaggedTransactions();

            // Log flagged transactions as activity
            foreach ($flaggedRecords as $record) {
                $userName = $record['user_name'] ?? 'Unknown';
                $userCode = $record['user_code'] ?? '';
                $date = $record['date'] ?? '';
                $amount = $record['amount'] ?? '';
                $account = $record['account'] ?? '';
                $reason = $record['reason'] ?? 'Duplicate data, flagged because a transaction for this user already exists on the same date.';

                $activityMsg = "Flagged transaction import for user {$userName} ({$userCode}) on {$date} (amount: {$amount}, account: {$account}) - Reason: {$reason}";
                log_activity($activityMsg);
            }

            $importedCount = is_array($newTransactions) ? count($newTransactions) : 0;
            $flaggedCount = is_array($flaggedRecords) ? count($flaggedRecords) : 0;
            $flagStatusZeroCount = is_array($flagStatusZeroRecords) ? count($flagStatusZeroRecords) : 0;
            $noIdFlaggedCount = is_array($noIdFlaggedTransactions) ? count($noIdFlaggedTransactions) : 0;

            // Only log if there is something to report
            if ($importedCount > 0 || $flaggedCount > 0 || $flagStatusZeroCount > 0 || $noIdFlaggedCount > 0) {
                $activityMsg = "Transaction import completed.";
                $details = [];
                if ($importedCount > 0) $details[] = "Imported: {$importedCount}";
                if ($flaggedCount > 0) $details[] = "Flagged: {$flaggedCount}";
                if ($flagStatusZeroCount > 0) $details[] = "Flag status zero: {$flagStatusZeroCount}";
                if ($noIdFlaggedCount > 0) $details[] = "No ID flagged: {$noIdFlaggedCount}";
                if (!empty($details)) {
                    $activityMsg .= ' ' . implode(', ', $details);
                }
                log_activity($activityMsg);
            }

            // Log the results for debugging
            Log::info('🎯 Import completed successfully');
            Log::info('🚩 Flagged records count: ' . $flaggedCount);
            Log::info('🚩 Flag status zero records count: ' . $flagStatusZeroCount);
            Log::info('✅ New transactions count: ' . $importedCount);
            Log::info('👥 Guest users count: ' . (is_array($guestUsers) ? count($guestUsers) : 0));
            Log::info('🔍 No ID flagged count: ' . $noIdFlaggedCount);
            Log::info('📊 Total count: ' . ($flaggedCount + $importedCount + $noIdFlaggedCount));
            Log::info('📋 Response data: ' . json_encode([
                'flagged_transactions' => $flaggedRecords,
                'successful_transactions' => $newTransactions,
                'guest_users' => $guestUsers,
                'no_id_flagged_transactions' => $noIdFlaggedTransactions,
                'flagged_count' => $flaggedCount,
                'successful_count' => $importedCount,
                'guest_count' => is_array($guestUsers) ? count($guestUsers) : 0,
                'no_id_flagged_count' => $noIdFlaggedCount,
                'total_count' => $flaggedCount + $importedCount + $noIdFlaggedCount,
            ]));

            // Return JSON response as expected by the frontend
            return response()->json([
                'success' => true,
                'flagged_transactions' => $flaggedRecords,
                'successful_transactions' => $newTransactions,
                'guest_users' => $guestUsers,
                'no_id_flagged_transactions' => $noIdFlaggedTransactions,
                'flagged_count' => $flaggedCount,
                'successful_count' => $importedCount,
                'guest_count' => is_array($guestUsers) ? count($guestUsers) : 0,
                'no_id_flagged_count' => $noIdFlaggedCount,
                'total_count' => $flaggedCount + $importedCount + $noIdFlaggedCount,
                'message' => 'Import completed successfully'
            ]);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $messages = [];
            foreach ($failures as $failure) {
                $messages[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            Log::error('Import validation failed: ' . implode(' | ', $messages));
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . implode(' | ', $messages)
            ], 422);
        } catch (\Exception $e) {
            Log::error('Import failed with exception: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }
    // import view
    public function importView()
    {
        // Fetch recent transactions with user info to show in the import view
        $transactions = \App\Models\Transaction::with('user')
            ->orderBy('date', 'desc')
            ->limit(50) // Increased limit to show more transactions
            ->get();

        // Get guest count (set to 0 if you don't want to show guest count)
        $guestCount = 0; // \App\Models\User::where('is_guest', 1)->count();

        // Get no ID flagged transactions count
        $noIdFlaggedCount = \App\Models\Transaction::where('no_id_flaged', 0)->count();

        return view('admin.superadmin.transactionmodule.import', [
            'transactions' => $transactions,
            'guestCount' => $guestCount,
            'noIdFlaggedCount' => $noIdFlaggedCount
        ]);
    }

    // flagged transactions view with all problematic transactions
    public function flaggedTransactionsView(Request $request)
    {
        // Get filter parameters
        $filter = $request->get('filter', 'all'); // all, flagged, no_id
        $search = $request->get('search', '');
        $perPage = $request->get('per_page', 10);

        // Base query for all problematic transactions
        $query = \App\Models\Transaction::with('user');

        // Apply filters
        switch ($filter) {
            case 'flagged':
                $query->where('flag_status', 0)->whereNotNull('user_id');
                break;
            case 'no_id':
                $query->where('no_id_flaged', 0);
                break;
            case 'all':
            default:
                // Show both flagged and no_id transactions
                $query->where(function($q) {
                    $q->where('flag_status', 0)->orWhere('no_id_flaged', 0);
                });
                break;
        }

        // Apply search
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('account', 'LIKE', "%{$search}%")
                  ->orWhere('amount', 'LIKE', "%{$search}%")
                  ->orWhere('date', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%")
                               ->orWhere('email', 'LIKE', "%{$search}%")
                               ->orWhere('unique_id', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Get paginated results
        $transactions = $query->orderBy('date', 'desc')->paginate($perPage);

        // Get statistics for all categories
        $totalFlagged = \App\Models\Transaction::where('flag_status', 0)->whereNotNull('user_id')->count();
        $totalNoId = \App\Models\Transaction::where('no_id_flaged', 0)->count();
        $totalAll = \App\Models\Transaction::where(function($q) {
            $q->where('flag_status', 0)->orWhere('no_id_flaged', 0);
        })->count();

        $totalFlaggedAmount = \App\Models\Transaction::where('flag_status', 0)->whereNotNull('user_id')->sum('amount');
        $totalNoIdAmount = \App\Models\Transaction::where('no_id_flaged', 0)->sum('amount');
        $totalAllAmount = \App\Models\Transaction::where(function($q) {
            $q->where('flag_status', 0)->orWhere('no_id_flaged', 0);
        })->sum('amount');

        return view('admin.superadmin.transactionmodule.flagged', [
            'transactions' => $transactions,
            'totalFlagged' => $totalFlagged,
            'totalNoId' => $totalNoId,
            'totalAll' => $totalAll,
            'totalFlaggedAmount' => $totalFlaggedAmount,
            'totalNoIdAmount' => $totalNoIdAmount,
            'totalAllAmount' => $totalAllAmount,
            'currentFilter' => $filter,
            'currentSearch' => $search,
            'currentPerPage' => $perPage
        ]);
    }
    //approve flagged
    public function acceptFlaggedTransaction($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);

            // Check if it's actually a flagged transaction
            if ($transaction->flag_status != 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'This transaction is not flagged.'
                ], 400);
            }

            // Update flag_status to 1 (successful)
            $transaction->update(['flag_status' => 1]);

            // Log the activity
            $activityMsg = "Flagged transaction accepted for user {$transaction->user->name} ({$transaction->user->unique_id}) on {$transaction->date} (amount: £{$transaction->amount})";
            log_activity($activityMsg);

            // Show success message (for API/JSON response)
            return response()->json([
                'success' => true,
                'message' => 'Transaction accepted successfully!',
                'transaction' => $transaction->load('user'),
                'show_success' => true // This can be used by frontend to trigger a toast/snackbar
            ]);
        } catch (\Exception $e) {
            \Log::error('Error accepting flagged transaction: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error accepting transaction: ' . $e->getMessage()
            ], 500);
        }
    }
    // ignore/delete flagged
    public function ignoreFlaggedTransaction($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);

            // Check if it's actually a flagged transaction
            if ($transaction->flag_status != 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'This transaction is not flagged.'
                ], 400);
            }

            // Store transaction details for logging before deletion
            $userName = $transaction->user->name ?? 'Unknown';
            $userUniqueId = $transaction->user->unique_id ?? 'N/A';
            $date = $transaction->date;
            $amount = $transaction->amount;

            // Delete the transaction
            $transaction->delete();

            // Log the activity
            $activityMsg = "Flagged transaction ignored/deleted for user {$userName} ({$userUniqueId}) on {$date} (amount: £{$amount})";
            log_activity($activityMsg);

            // Show success message (for API/JSON response)
            return response()->json([
                'success' => true,
                'message' => 'Transaction ignored and deleted successfully! ',
                'show_success' => true // This can be used by frontend to trigger a toast/snackbar
            ]);
        } catch (\Exception $e) {
            \Log::error('Error ignoring flagged transaction: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error ignoring transaction: ' . $e->getMessage()
            ], 500);
        }
    }
    // All transaction index
    public function transactionindex()
    {
        $query = Transaction::with('user');

        // Alphabet filter
        if ($alphabet = request()->input('alphabet')) {
            $query->where(function ($q) use ($alphabet) {
                // Filter by user name/email (for transactions with users)
                $q->whereHas('user', function ($sub) use ($alphabet) {
                    $sub->where(function ($userSub) use ($alphabet) {
                        $userSub->where('name', 'like', $alphabet . '%')
                            ->orWhere('email', 'like', $alphabet . '%');
                    });
                })
                // Also filter by transaction name field (for flagged transactions without users)
                ->orWhere('name', 'like', $alphabet . '%');
            });
        }

        // Filter by member name
        if ($memberName = request()->input('member_name')) {
            $query->where(function ($q) use ($memberName) {
                // Filter by user name (for transactions with users)
                $q->whereHas('user', function ($sub) use ($memberName) {
                    $sub->where('name', 'like', '%' . $memberName . '%');
                })
                // Also filter by transaction name field (for flagged transactions without users)
                ->orWhere('name', 'like', '%' . $memberName . '%');
            });
        }

        // Filter by unique_id
        if ($uniqueId = request()->input('unique_id')) {
            $query->whereHas('user', function ($q) use ($uniqueId) {
                $q->where('unique_id', 'like', '%' . $uniqueId . '%');
            });
        }

        // Filter by transaction type (debit/cash)
        $type = request()->input('type');
        if ($type && in_array($type, ['debit', 'cash','credit'])) {
            $query->where('status', $type);
        }

        // Filter by flag_status
        $flagStatus = request()->input('flag_status');
        if ($flagStatus !== null && $flagStatus !== '') {
            // Accepts 0, 1, or null (for "all")
            if ($flagStatus === '0' || $flagStatus === 0) {
                $query->where('flag_status', 0);
            } elseif ($flagStatus === '1' || $flagStatus === 1) {
                $query->where('flag_status', 1);
            }
            // If flag_status is not 0 or 1, do not filter (show all)
        }

        // Filter by guest user status
        $isGuest = request()->input('is_guest');
        if ($isGuest !== null && $isGuest !== '') {
            $query->whereHas('user', function ($q) use ($isGuest) {
                $q->where('is_guest', $isGuest);
            });
        }

        // Filter by year (takes precedence over start/end date)
        $year = request()->input('year');
        $startDate = request()->input('start_date');
        $endDate = request()->input('end_date');

        if ($year) {
            $query->whereYear('date', $year);
            $activeFilter = 'yearly';
        } else {
            if ($startDate) {
                $query->whereDate('date', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('date', '<=', $endDate);
            }
            $activeFilter = 'range';
        }

        $perPage = request()->input('per_page', 10);
        $transactions = $query->latest()->paginate($perPage);

        // --- Progress Bar Data by Year ---
        // Get all years present in transactions
        $years = Transaction::selectRaw('YEAR(date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // For each year, get total transaction amount (optionally filter by type)
        $yearlyAmounts = [];
        foreach ($years as $y) {
            $amountQuery = Transaction::whereYear('date', $y);
            if ($type && in_array($type, ['debit', 'cash'])) {
                $amountQuery->where('status', $type);
            }
            if ($flagStatus !== null && $flagStatus !== '') {
                if ($flagStatus === '0' || $flagStatus === 0) {
                    $amountQuery->where('flag_status', 0);
                } elseif ($flagStatus === '1' || $flagStatus === 1) {
                    $amountQuery->where('flag_status', 1);
                }
            }
            if ($isGuest !== null && $isGuest !== '') {
                $amountQuery->whereHas('user', function ($q) use ($isGuest) {
                    $q->where('is_guest', $isGuest);
                });
            }
            $yearlyAmounts[$y] = $amountQuery->sum('amount');
        }

        // Pass filter values and progress bar data back to the view for form repopulation
        return view(
            'admin.superadmin.transactionmodule.index',
            compact(
                'transactions',
                'memberName',
                'uniqueId',
                'startDate',
                'endDate',
                'year',
                'activeFilter',
                'type',
                'flagStatus',
                'isGuest',
                'years',
                'yearlyAmounts'
            )
        );
    }
    //delete transaction
    public function deleteTransaction($id)
    {
        // Find the transaction by ID
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return redirect()->back()->withErrors(['Transaction not found.']);
        }

        // Only allow super-admin and admin to delete
        if (!in_array(auth()->user()->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        try {
            $transaction->delete();

            // Log activity using the helper
              // Log activity with transaction details: user name/email and amount
             $userInfo = $transaction->user ? ($transaction->user->name ?? $transaction->user->email ?? 'Unknown User') : 'Unknown User';
             $amount = $transaction->amount ?? 'N/A';
             log_activity("Deleted transaction ID: {$id}, User: {$userInfo}, Amount: {$amount}");

            return redirect()->back()->with('success', 'Transaction deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Failed to delete transaction.']);
        }
    }
    // member transaction + profile
    public function memberTransactionDetail($name, $unique_id)
    {
        // Find the member by unique_id and type 'member'
        $user = User::where('unique_id', $unique_id)
            ->where('type', 'member')
            ->first();

        if (!$user) {
            return redirect()->back()->withErrors(['Member not found.']);
        }

        // Get start and end date from request (for filtering)
        $startDate = request()->input('start_date');
        $endDate = request()->input('end_date');
        // Get year filter from request (for yearly filtering)
        $year = request()->input('year');
        // Get flag_status filter from request
        $flagStatus = request()->input('flag_status');

        // Build query for transactions
        $query = Transaction::where('user_id', $user->id);

        // Add flag_status filter if provided
        if ($flagStatus !== null && $flagStatus !== '') {
            if ($flagStatus === '0' || $flagStatus === 0) {
                $query->where('flag_status', 0);
            } elseif ($flagStatus === '1' || $flagStatus === 1) {
                $query->where('flag_status', 1);
            }
            // If flag_status is not 0 or 1, do not filter (show all)
        }

        // If year is provided, filter by year only (ignore start/end date if year is set)
        if ($year) {
            $query->whereYear('date', $year);
            $activeFilter = 'yearly';
        } else {
            if ($startDate) {
                $query->whereDate('date', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('date', '<=', $endDate);
            }
            $activeFilter = 'range';
        }

        $transactions = $query->orderBy('date', 'desc')->paginate(10);

        // Pass start and end date and year and flag_status back to view for form repopulation
        return view('admin.superadmin.transactionmodule.member_detail', compact('user', 'transactions', 'startDate', 'endDate', 'year', 'activeFilter', 'flagStatus'));
    }
    // admin activity log + profile
    public function AdminDetail($name, $unique_id)
    {
        // Find the admin by unique_id and type 'admin'
        $user = User::where('unique_id', $unique_id)
            ->where('type', 'admin')
            ->first();

        if (!$user) {
            return redirect()->back()->withErrors(['Admin not found.']);
        }

        // Optionally, you can check if the $name matches $user->name for extra validation
        // if (strtolower($user->name) !== strtolower(str_replace('-', ' ', $name))) {
        //     return redirect()->back()->withErrors(['Admin name does not match.']);
        // }

        // Get start and end date from request (for filtering)
        $startDate = request()->input('start_date');
        $endDate = request()->input('end_date');
        // Get year filter from request (for yearly filtering)
        $year = request()->input('year');

        // Build query for activity logs
        $query = \DB::table('activity_logs')->where('user_id', $user->id);

        // If year is provided, filter by year only (ignore start/end date if year is set)
        if ($year) {
            $query->whereYear('created_at', $year);
            $activeFilter = 'yearly';
        } else {
            if ($startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            }
            $activeFilter = 'range';
        }

        $activityLogs = $query->orderBy('created_at', 'desc')->paginate(5);

        // Pass start and end date and year back to view for form repopulation
        return view('admin.superadmin.transactionmodule.admin_detail', compact('user', 'activityLogs', 'startDate', 'endDate', 'year', 'activeFilter'));
    }
    // archive
    public function archivetransactionindex()
    {
        $request = request();
        $query = Transaction::with('user');

        // Filter by member name
        if ($memberName = $request->input('member_name')) {
            $query->whereHas('user', function ($q) use ($memberName) {
                $q->where('name', 'like', '%' . $memberName . '%');
            });
        }

        // Filter by unique_id
        if ($uniqueId = $request->input('unique_id')) {
            $query->whereHas('user', function ($q) use ($uniqueId) {
                $q->where('unique_id', 'like', '%' . $uniqueId . '%');
            });
        }

        // Filter by transaction type (debit/credit)
        $transactionType = $request->input('transaction_type');
        if ($transactionType) {
            // Assuming 'status' field is used for credit/debit
            // You may need to adjust this if your field is named differently
            $query->where(function ($q) use ($transactionType) {
                if (strtolower($transactionType) == 'credit') {
                    $q->where('status', 'cash'); // or whatever value means credit
                } elseif (strtolower($transactionType) == 'debit') {
                    $q->where('status', 'debit'); // or whatever value means debit
                }
            });
        }

        // Filter by year (but only show years NOT equal to current year)
        $year = $request->input('year');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $currentYear = now()->year;

        if ($year && $year != $currentYear) {
            $query->whereYear('date', $year);
            $activeFilter = 'yearly';
        } else {
            // Only show transactions NOT in the current year
            $query->whereYear('date', '!=', $currentYear);

            if ($startDate) {
                $query->whereDate('date', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('date', '<=', $endDate);
            }
            $activeFilter = 'range';
        }
        $perPage = $request->input('per_page', 10);
        $transactions = $query->latest()->paginate($perPage);

        // Pass filter values back to the view for form repopulation
        return view('admin.superadmin.transactionmodule.archiveindex', compact('transactions', 'memberName', 'uniqueId', 'startDate', 'endDate', 'year', 'activeFilter', 'transactionType'));
    }
    //    Debiet transaction
    public function debeittransactionindex(Request $request)
    {
        $query = Transaction::with('user');

        // Only show debit transactions (assuming 'status' field is used for type)
        $query->where('status', 'debit');

        // Filter by member name
        if ($memberName = $request->input('member_name')) {
            $query->whereHas('user', function ($q) use ($memberName) {
                $q->where('name', 'like', '%' . $memberName . '%');
            });
        }

        // Filter by unique_id
        if ($uniqueId = $request->input('unique_id')) {
            $query->whereHas('user', function ($q) use ($uniqueId) {
                $q->where('unique_id', 'like', '%' . $uniqueId . '%');
            });
        }

        // Filter by year (takes precedence over start/end date)
        $year = $request->input('year');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($year) {
            $query->whereYear('date', $year);
            $activeFilter = 'yearly';
        } else {
            if ($startDate) {
                $query->whereDate('date', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('date', '<=', $endDate);
            }
            $activeFilter = 'range';
        }

        $perPage = $request->input('per_page', 10);
        $transactions = $query->latest()->paginate($perPage);

        // Pass filter values back to the view for form repopulation
        return view('admin.superadmin.transactionmodule.debeit', compact('transactions', 'memberName', 'uniqueId', 'startDate', 'endDate', 'year', 'activeFilter'));
    }
    //Credit transaction
    public function credittransactionindex(Request $request)
    {
        $query = Transaction::with('user');

        // Only show credit transactions
        $query->where('status', 'cash');

        // Filter by member name
        if ($memberName = $request->input('member_name')) {
            $query->whereHas('user', function ($q) use ($memberName) {
                $q->where('name', 'like', '%' . $memberName . '%');
            });
        }

        // Filter by unique_id
        if ($uniqueId = $request->input('unique_id')) {
            $query->whereHas('user', function ($q) use ($uniqueId) {
                $q->where('unique_id', 'like', '%' . $uniqueId . '%');
            });
        }

        // Filter by year (takes precedence over start/end date)
        $year = $request->input('year');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($year) {
            $query->whereYear('date', $year);
            $activeFilter = 'yearly';
        } else {
            if ($startDate) {
                $query->whereDate('date', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('date', '<=', $endDate);
            }
            $activeFilter = 'range';
        }

        $perPage = $request->input('per_page', 10);
        $transactions = $query->latest()->paginate($perPage);

        // Pass filter values back to the view for form repopulation
        return view('admin.superadmin.transactionmodule.credit', compact('transactions', 'memberName', 'uniqueId', 'startDate', 'endDate', 'year', 'activeFilter'));
    }
    // Guest User Management Methods
    public function promoteGuestUser($id)
    {
        try {
            $guestUser = User::where('id', $id)->where('is_guest', 1)->firstOrFail();

            // Use original_unique_id if available, otherwise generate new one
            $newUniqueId = $guestUser->original_unique_id ?: $guestUser->generateUniqueId($guestUser->name);

            // Update guest user to regular member
            $guestUser->update([
                'unique_id' => $newUniqueId,
                'is_guest' => 0,
                'original_unique_id' => null, // Clear the original_unique_id since it's now the main unique_id
                'email' => 'member_' . $newUniqueId . '@gmail.com',
            ]);

            // Log the activity
            $activityMsg = "Guest user promoted to member: {$guestUser->name} (ID: {$guestUser->id}, New Unique ID: {$newUniqueId})";
            log_activity($activityMsg);

            return response()->json([
                'success' => true,
                'message' => 'Guest user promoted successfully!',
                'user' => $guestUser
            ]);
        } catch (\Exception $e) {
            \Log::error('Error promoting guest user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error promoting guest user: ' . $e->getMessage()
            ], 500);
        }
    }
    public function assignGuestToMember($id, Request $request)
    {
        try {
            $guestUser = User::where('id', $id)->where('is_guest', 1)->firstOrFail();
            $memberId = $request->input('member_id');

            if (!$memberId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Member ID is required'
                ], 400);
            }

            $member = User::where('id', $memberId)->where('type', 'member')->firstOrFail();

            // Update all transactions from guest user to member
            Transaction::where('user_id', $guestUser->id)->update(['user_id' => $member->id]);

            // Delete the guest user
            $guestUser->delete();

            // Log the activity
            $activityMsg = "Guest user assigned to member: {$member->name} (ID: {$member->id})";
            log_activity($activityMsg);

            return response()->json([
                'success' => true,
                'message' => 'Guest user assigned to member successfully!',
                'member' => $member
            ]);
        } catch (\Exception $e) {
            \Log::error('Error assigning guest user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error assigning guest user: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getMembersList()
    {
        try {
            $members = User::where('type', 'member')
                ->where('is_guest', 0)
                ->select('id', 'name', 'unique_id', 'email')
                ->get();

            return response()->json([
                'success' => true,
                'members' => $members
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting members list: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting members list: ' . $e->getMessage()
            ], 500);
        }
    }
    //Manually transacion
    public function Manuallycreate()
    {
        $members = User::where('type', 'member')->get(); // Adjust 'type' condition as needed
        return view('admin.superadmin.transactionmodule.Manually', compact('members'));
    }

    // Auto Annual fee complition mail
    // Use the queued job to send the annual fee completion email (as in SendAnnualFeeEmailJob.php)
    protected function checkAndSendAnnualFeeEmail($userId, $year)
    {
        // Get user and check role
        $user = \App\Models\User::find($userId);
        if (!$user || $user->type !== 'member') {
            return false;
        }

        // Get the required annual fee for the year
        $feeSetting = \App\Models\MembershipFeeSetting::where('member_type', 'annual_fee')
            ->where('year', $year)
            ->first();

        if (!$feeSetting) {
            return false;
        }

        $requiredAmount = $feeSetting->amount;

        // Calculate total paid for this user and year
        $totalPaid = \App\Models\Transaction::where('user_id', $userId)
            ->whereYear('date', $year)
            ->sum('amount');

        // Check if already sent by searching the activity log
        $memberName = $user->name ?? 'Unknown';
        $memberuniqueid = $user->unique_id ?? 'Unknown';
        $activityMsg = "Annual fee completion email sent to member {$memberName} (user ID {$memberuniqueid}) for year {$year}.";
        $alreadySent = \App\Models\ActivityLog::where('activity', $activityMsg)->exists();

        // Check system setting
        $webSetting = \App\Models\WebSetting::first();
        $shouldSend = $webSetting && $webSetting->send_fee_completion_email;

        if ($totalPaid >= $requiredAmount && !$alreadySent && $shouldSend) {
            // Dispatch the queued job to send the email and log the activity
            \App\Jobs\SendAnnualFeeEmailJob::dispatch($userId, $year);

            return true;
        }
        return false;
    }
    public function Manuallystore(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            // 'status' => 'required|string', // Remove strict validation for status
        ]);

        // Always set flag_status to 1
        $validated['flag_status'] = 1;

        // Set status to null if not provided, or accept any value
        $validated['status'] = $request->input('status', null);

         $transaction = \App\Models\Transaction::create($validated);

         $user = \App\Models\User::find($transaction->user_id);
         $userName = $user ? $user->name : 'Unknown';
         $uniqueId = $user ? $user->unique_id : 'Unknown';
         $amount = $transaction->amount;
         log_activity("Added a new transaction for user: {$userName} (Unique ID: {$uniqueId}), Amount: £{$amount}");

        // After storing, check and send annual fee completion email if needed
        $year = \Carbon\Carbon::parse($transaction->date)->year;
        $this->checkAndSendAnnualFeeEmail($transaction->user_id, $year);

        return redirect()->route('transactions.list')->with('success', 'Transaction added successfully.');
    }

    //websetting
    public function websettingindex()
    {
        $user = auth()->user();
        if (!in_array($user->type, ['super-admin'])) {
            abort(403, 'Unauthorized');
        }
        $setting = WebSetting::first();

        if (!$setting) {
            $setting = WebSetting::create([
                'send_member_creation_email' => true,
                'send_admin_creation_email' => true,
                'send_fee_completion_email' => true,
                'send_guest_promoted_email' => true,
                'send_contact_us_email' => true,
                'send_newsletter_email' => true,
                'email1' => null,
                'email2' => null,
                'phone1' => null,
                'phone2' => null,
                'address' => null,
                'address_link' => null,
                'facebook_link' => null,
                'youtube_link' => null,
                'insta_link' => null,
                'linkdin_link' => null,
                'copy_right' => null,
                'favicon_icon' => null,
            ]);
        }

        return view('admin.websetting.index', compact('setting'));
    }
    public function websettingupdate(Request $request)
    {
        $request->validate([
            'send_member_creation_email' => 'required|boolean',
            'send_admin_creation_email' => 'required|boolean',
            'send_fee_completion_email' => 'required|boolean',
            'send_guest_promoted_email' => 'required|boolean',
            'send_contact_us_email' => 'required|boolean',
            'send_newsletter_email' => 'required|boolean',
            'email1' => 'nullable|email',
            'email2' => 'nullable|email',
            'phone1' => 'nullable|string|max:255',
            'phone2' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'address_link' => 'nullable|string',
            'facebook_link' => 'nullable|string|max:255',
            'youtube_link' => 'nullable|string|max:255',
            'insta_link' => 'nullable|string|max:255',
            'linkdin_link' => 'nullable|string|max:255',
            'copy_right' => 'nullable|string|max:255',
            // Accept only file uploads for favicon_icon, and restrict to image and icon types
            'favicon_icon' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,ico,webp|max:10240',
        ], [
            'favicon_icon.file' => 'The favicon icon must be a file.',
            'favicon_icon.mimes' => 'The favicon icon must be a file of type: jpeg, png, jpg, gif, svg, ico, webp.',
        ]);

        $setting = WebSetting::first();

        $messages = [];

        if ($setting->send_member_creation_email != $request->send_member_creation_email) {
            $messages[] = $request->send_member_creation_email
                ? 'Member creation email notifications enabled.'
                : 'Member creation email notifications disabled.';
        }

        if ($setting->send_admin_creation_email != $request->send_admin_creation_email) {
            $messages[] = $request->send_admin_creation_email
                ? 'Admin creation email notifications enabled.'
                : 'Admin creation email notifications disabled.';
        }

        if ($setting->send_fee_completion_email != $request->send_fee_completion_email) {
            $messages[] = $request->send_fee_completion_email
                ? 'Fee completion email notifications enabled.'
                : 'Fee completion email notifications disabled.';
        }

        if ($setting->send_guest_promoted_email != $request->send_guest_promoted_email) {
            $messages[] = $request->send_guest_promoted_email
                ? 'Guest promoted email notifications enabled.'
                : 'Guest promoted email notifications disabled.';
        }

        if ($setting->send_contact_us_email != $request->send_contact_us_email) {
            $messages[] = $request->send_contact_us_email
                ? 'Contact Us email notifications enabled.'
                : 'Contact Us email notifications disabled.';
        }

        if ($setting->send_newsletter_email != $request->send_newsletter_email) {
            $messages[] = $request->send_newsletter_email
                ? 'Newsletter email notifications enabled.'
                : 'Newsletter email notifications disabled.';
        }

        if ($setting->email1 !== $request->email1) {
            $messages[] = 'Primary email updated.';
        }
        if ($setting->email2 !== $request->email2) {
            $messages[] = 'Secondary email updated.';
        }
        if ($setting->phone1 !== $request->phone1) {
            $messages[] = 'Primary phone updated.';
        }
        if ($setting->phone2 !== $request->phone2) {
            $messages[] = 'Secondary phone updated.';
        }
        if ($setting->address !== $request->address) {
            $messages[] = 'Address updated.';
        }
        if ($setting->address_link !== $request->address_link) {
            $messages[] = 'Address link updated.';
        }
        if ($setting->facebook_link !== $request->facebook_link) {
            $messages[] = 'Facebook link updated.';
        }
        if ($setting->youtube_link !== $request->youtube_link) {
            $messages[] = 'YouTube link updated.';
        }
        if ($setting->insta_link !== $request->insta_link) {
            $messages[] = 'Instagram link updated.';
        }
        if ($setting->linkdin_link !== $request->linkdin_link) {
            $messages[] = 'LinkedIn link updated.';
        }
        if ($setting->copy_right !== $request->copy_right) {
            $messages[] = 'Copyright updated.';
        }

        // Handle favicon_icon upload
        $faviconPath = $setting->favicon_icon;
        if ($request->hasFile('favicon_icon')) {
            $favicon = $request->file('favicon_icon');
            // Double check file is valid
            if ($favicon->isValid()) {
                $faviconName = 'favicon_' . time() . '_' . uniqid() . '.' . $favicon->getClientOriginalExtension();
                $favicon->move(public_path('uploads/websetting'), $faviconName);
                $faviconPath = 'uploads/websetting/' . $faviconName;
                $messages[] = 'Favicon icon updated.';
            } else {
                return back()->withErrors(['favicon_icon' => 'The uploaded favicon icon is not valid.']);
            }
        }

        $setting->update([
            'send_member_creation_email' => $request->send_member_creation_email,
            'send_admin_creation_email' => $request->send_admin_creation_email,
            'send_fee_completion_email' => $request->send_fee_completion_email,
            'send_guest_promoted_email' => $request->send_guest_promoted_email,
            'send_contact_us_email' => $request->send_contact_us_email,
            'send_newsletter_email' => $request->send_newsletter_email,
            'email1' => $request->email1,
            'email2' => $request->email2,
            'phone1' => $request->phone1,
            'phone2' => $request->phone2,
            'address' => $request->address,
            'address_link' => $request->address_link,
            'facebook_link' => $request->facebook_link,
            'youtube_link' => $request->youtube_link,
            'insta_link' => $request->insta_link,
            'linkdin_link' => $request->linkdin_link,
            'copy_right' => $request->copy_right,
            'favicon_icon' => $faviconPath,
        ]);

        if (empty($messages)) {
            $messages[] = 'No changes were made to the settings.';
        } else {
            $messages[] = 'Settings updated successfully.';
        }

        return back()->with('success', implode(' ', $messages));
    }
    //Newsletter
    public function showNewsletterAdmin(Request $request)
    {
        // Only allow access for admin users
        $user = auth()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $perPage = (int) $request->input('per_page', 10);

        // Filtering logic
        $query = \App\Models\Newsletter::query();

        // Status filter
        $status = $request->input('status');
        if ($status === 'subscribed') {
            $query->where('is_subscribed', 1);
        } elseif ($status === 'unsubscribed') {
            $query->where('is_subscribed', 0);
        }

        // Date range filter
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Search filter (by email)
        $search = $request->input('search');
        if ($search) {
            $query->where('email', 'like', '%' . $search . '%');
        }

        $subscribers = $query->orderBy('created_at', 'desc')->paginate($perPage)->appends($request->all());

        // Calculate total new newsletter subscribers (e.g., in the last 7 days)
        $newSubscribersCount = \App\Models\Newsletter::where('created_at', '>=', now()->subDays(7))->count();

        // Calculate total newsletter subscribers (all time)
        $totalSubscribersCount = \App\Models\Newsletter::count();

        return view('admin.superadmin.newsletter.index', [
            'subscribers' => $subscribers,
            'totalSubscribersCount' => $totalSubscribersCount,
            'newSubscribersCount' => $newSubscribersCount,
        ]);
    }
    //delete newsletter
    public function deleteNewsletterSubscriber(Request $request, $id)
    {
        // Only allow access for admin users
        $user = auth()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $subscriber = \App\Models\Newsletter::findOrFail($id);

        // Log activity before deleting
        log_activity("Deleted newsletter subscriber: {$subscriber->email}", $user);

        $subscriber->delete();

        return redirect()->back()->with('success', 'Subscriber deleted successfully.');
    }
    // Show Contact Us entries with filters
    public function contactUsIndex(Request $request)
    {
        // Only allow access for admin users
        $user = auth()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $query = \App\Models\ContactUs::query();

        // Filter: start date
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }

        // Filter: end date
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }

        // Filter: name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        // Filter: email
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
        }

        // Filter: phone
        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->input('phone') . '%');
        }

        // Filter: address
        if ($request->filled('address')) {
            $query->where('address', 'like', '%' . $request->input('address') . '%');
        }

        // Pagination
        $perPage = $request->input('per_page', 10);
        $contacts = $query->orderBy('created_at', 'desc')->paginate($perPage)->appends($request->all());

        return view('admin.superadmin.contactus.index', [
            'contacts' => $contacts,
        ]);
    }

    // Delete Contact Us entry
    public function deleteContactUs(Request $request, $id)
    {
        // Only allow access for admin users
        $user = auth()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $contact = \App\Models\ContactUs::findOrFail($id);

        // Log activity before deleting
        if (function_exists('log_activity')) {
            log_activity("Deleted Contact Us entry: {$contact->email}", $user, 'contact_us');
        }

        $contact->delete();

        return redirect()->back()->with('success', 'Contact Us entry deleted successfully.');
    }

    public function myTransactions(Request $request)
    {
        $user = auth()->user();

        // Only allow members to view their own transactions
        if (!$user || $user->type !== 'member') {
            abort(403, 'Unauthorized');
        }

        // Filters from the form
        $type = $request->input('type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $year = $request->input('year');
        $month = $request->input('month');
        // Show all transactions flagged or unflagged by default
        $flagStatus = $request->input('flag_status', '');

        $query = Transaction::where('user_id', $user->id);

        // Filter: type (debit/cash)
        if (!empty($type)) {
            $query->where('type', $type);
        }

        // Filter: start date
        if (!empty($startDate)) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        // Filter: end date
        if (!empty($endDate)) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Filter: year
        if (!empty($year)) {
            $query->whereYear('created_at', $year);
        }

        // Filter: month
        if (!empty($month)) {
            $query->whereMonth('created_at', $month);
        }

        // Only filter by flag_status if it is set (not default)
        if ($flagStatus !== '' && $flagStatus !== null) {
            $query->where('flag_status', $flagStatus);
        }
        // If flag_status is not set, show all (both 0 and 1)

        $perPage = $request->input('per_page', 10);
        $transactions = $query->orderBy('created_at', 'desc')->paginate($perPage)->appends($request->all());

        return view('admin.superadmin.memebermodule.transactions', [
            'transactions' => $transactions,
            'type' => $type,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'year' => $year,
            'month' => $month,
            'flag_status' => $flagStatus,
        ]);
    }

    // Get all members for searchable dropdown
    public function getAllMembers()
    {
        try {
            $members = \App\Models\User::where('type', 'member')
                ->where('is_guest', 0)
                // ->where('status', 1)  // Temporarily commented out for debugging
                ->select('id', 'name', 'email', 'unique_id')
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'members' => $members
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load members: ' . $e->getMessage()
            ], 500);
        }
    }

    // Ignore no ID transaction
    public function ignoreNoIdTransaction(Request $request)
    {
        try {
            $transactionId = $request->input('transaction_id');

            $transaction = \App\Models\Transaction::find($transactionId);
            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found'
                ], 404);
            }

            if ($transaction->no_id_flaged != 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'This is not a no-ID flagged transaction'
                ], 400);
            }

            // Delete the transaction
            $transaction->delete();

            log_activity("Ignored no-ID transaction (ID: {$transactionId}, Amount: £{$transaction->amount}, Date: {$transaction->date})");

            return response()->json([
                'success' => true,
                'message' => 'Transaction ignored successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to ignore transaction: ' . $e->getMessage()
            ], 500);
        }
    }

    // Assign no ID transaction to existing member
    public function assignNoIdTransaction(Request $request)
    {
        try {
            $transactionId = $request->input('transaction_id');
            $memberId = $request->input('member_id');

            $transaction = \App\Models\Transaction::find($transactionId);
            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found'
                ], 404);
            }

            if ($transaction->no_id_flaged != 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'This is not a no-ID flagged transaction'
                ], 400);
            }

            $member = \App\Models\User::find($memberId);
            if (!$member || $member->type !== 'member' || $member->is_guest == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid member selected'
                ], 400);
            }

            // Update transaction
            $transaction->update([
                'user_id' => $member->id,
                'no_id_flaged' => 1
            ]);

            log_activity("Assigned no-ID transaction (ID: {$transactionId}, Amount: £{$transaction->amount}) to member: {$member->name} ({$member->unique_id})");

            return response()->json([
                'success' => true,
                'message' => 'Transaction assigned successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign transaction: ' . $e->getMessage()
            ], 500);
        }
    }

    // Create new member and assign no ID transaction
    public function createMemberAndAssignNoIdTransaction(Request $request)
    {
        try {
            $transactionId = $request->input('transaction_id');
            $memberName = $request->input('member_name');
            $memberEmail = $request->input('member_email');

            $transaction = \App\Models\Transaction::find($transactionId);
            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found'
                ], 404);
            }

            if ($transaction->no_id_flaged != 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'This is not a no-ID flagged transaction'
                ], 400);
            }

            // Validate input
            if (empty($memberName) || empty($memberEmail)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Member name and email are required'
                ], 400);
            }

            // Check if email already exists
            $existingUser = \App\Models\User::where('email', $memberEmail)->first();
            if ($existingUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'A user with this email already exists'
                ], 400);
            }

            // Create new member
            $user = new \App\Models\User();
            $uniqueId = $user->generateUniqueId($memberName);

            $newMember = \App\Models\User::create([
                'unique_id' => $uniqueId,
                'name' => $memberName,
                'email' => $memberEmail,
                'password' => bcrypt('member123'), // Default password
                'type' => 'member',
                'is_user' => 1,
                'is_guest' => 0,
                'status' => 1,
            ]);

            // Update transaction
            $transaction->update([
                'user_id' => $newMember->id,
                'no_id_flaged' => 1
            ]);

            log_activity("Created new member: {$newMember->name} ({$newMember->unique_id}) and assigned no-ID transaction (ID: {$transactionId}, Amount: £{$transaction->amount})");

            return response()->json([
                'success' => true,
                'message' => 'New member created and transaction assigned successfully',
                'member' => [
                    'id' => $newMember->id,
                    'name' => $newMember->name,
                    'email' => $newMember->email,
                    'unique_id' => $newMember->unique_id
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create member: ' . $e->getMessage()
            ], 500);
        }
    }

    // Preview transactions for reservation (deletion)
    public function previewReserveTransactions(Request $request)
    {
        try {
            $month = $request->input('month');
            $year = $request->input('year');

            if (!$month || !$year) {
                return response()->json([
                    'success' => false,
                    'message' => 'Month and year are required'
                ], 400);
            }

            // Get transactions for the specified month and year
            $transactions = Transaction::with('user')
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->get();

            $totalAmount = $transactions->sum('amount');

            return response()->json([
                'success' => true,
                'month' => $month,
                'year' => $year,
                'transactions' => $transactions,
                'totalAmount' => $totalAmount,
                'count' => $transactions->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error previewing reserve transactions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to preview transactions: ' . $e->getMessage()
            ], 500);
        }
    }

    // Reserve (delete) transactions for a specific month and year
    public function reserveTransactions(Request $request)
    {
        try {
            $month = $request->input('month');
            $year = $request->input('year');

            if (!$month || !$year) {
                return response()->json([
                    'success' => false,
                    'message' => 'Month and year are required'
                ], 400);
            }

            // Get transactions for the specified month and year
            $transactions = Transaction::whereYear('date', $year)
                ->whereMonth('date', $month)
                ->get();

            if ($transactions->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No transactions found for the specified period'
                ], 404);
            }

            $totalAmount = $transactions->sum('amount');
            $transactionCount = $transactions->count();

            // Log the reservation action
            log_activity(
                "Reserved {$transactionCount} transactions for {$month}/{$year} (Total: £{$totalAmount})",
                auth()->user(),
                'reserve'
            );

            // Delete the transactions
            $transactions->each(function ($transaction) {
                $transaction->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Transactions reserved successfully',
                'reservedCount' => $transactionCount,
                'totalAmount' => $totalAmount,
                'month' => $month,
                'year' => $year
            ]);

        } catch (\Exception $e) {
            Log::error('Error reserving transactions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reserve transactions: ' . $e->getMessage()
            ], 500);
        }
    }
}
