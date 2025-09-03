<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use App\Models\Faq;
use App\Models\Newsletter;
use App\Models\RulesRegulation;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    // FAQ INDEX
    public function faqIndex(Request $request)
    {
        $user = auth()->user();
        if (!in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $query = Faq::query();

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', 1);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', 0);
            }
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter by search (question or answer)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                    ->orWhere('answer', 'like', "%{$search}%");
            });
        }
        $totalfaqs = FAQ::count();
        $perPage = $request->input('per_page', 5);
        $faqs = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return view('admin.public.faq.index', compact('faqs', 'totalfaqs'));
    }
    // FAQ STORE/UPDATE
    public function storeOrUpdateFaq(Request $request)
    {
        $user = auth()->user();
        if (!in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'question'   => 'required|string',
            'answer'     => 'required|string',
            'is_active'  => 'required|boolean',
            'faq_id'     => 'nullable|exists:faqs,id', // For update
        ]);

        $faq = Faq::updateOrCreate(
            ['id' => $request->faq_id],
            [
                'question'  => $request->question,
                'answer'    => $request->answer,
                'is_active' => $request->is_active,
            ]
        );

        // Activity log
        if (function_exists('log_activity')) {
            $actionType = $request->faq_id ? 'updated' : 'created';
            log_activity("FAQ {$actionType}: {$faq->question}", $user, $actionType);
        }

        return redirect()->route('faqs.index')->with('success', $request->faq_id ? 'FAQ updated' : 'FAQ created');
    }
    // FAQ EDIT
    public function editFaq($id)
    {
        $user = auth()->user();
        if (!in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $faq = Faq::findOrFail($id);

        return view('admin.public.faq.edit', compact('faq'));
    }
    // FAQ CREATE
    public function createOrEditFaqForm($id = null)
    {
        $user = auth()->user();
        if (!in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $faq = null;
        if ($id) {
            $faq = Faq::findOrFail($id);
        }

        return view('admin.public.faq.create', compact('faq', 'id')); // Use a single form view for both create and edit
    }
    // FAQ DELETE
    public function deleteFaq($id)
    {
        $user = auth()->user();
        if (!in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $faq = Faq::findOrFail($id);
        $question = $faq->question;
        $faq->delete();

        // Activity log
        if (function_exists('log_activity')) {
            $actionType = 'delete';
            log_activity("FAQ deleted: {$question}", $user, $actionType);
        }

        return back()->with('success', 'FAQ deleted successfully.');
    }

    // RULES & REGULATIONS
    // rules index
    public function ruleIndex(Request $request)
    {
        $user = auth()->user();
        if (!in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        // Filtering
        $query = RulesRegulation::query();

        // Status filter
        $status = $request->input('status');
        if ($status === 'active') {
            $query->where('is_active', 1);
        } elseif ($status === 'inactive') {
            $query->where('is_active', 0);
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

        // Search filter (title or points)
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhereJsonContains('points', $search);
            });
        }
        $totalrules = RulesRegulation::count();
        // Pagination: default 10 per page, allow user to set per_page
        $perPage = (int) $request->input('per_page', 5);
        $perPage = $perPage > 0 ? $perPage : 10;

        $rules = $query->orderBy('created_at', 'desc')->paginate($perPage)->appends($request->all());

        return view('admin.public.rules.index', compact('rules', 'totalrules'));
    }
    // Show create/edit  rule create
    public function ruleForm($id = null)
    {
        $user = auth()->user();
        if (!in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $rule = null;
        if ($id) {
            $rule = RulesRegulation::findOrFail($id);
        }

        return view('admin.public.rules.create', compact('rule', 'id'));
    }
    // Store or update rule
    public function ruleSave(Request $request, $id = null)
    {
        $user = auth()->user();
        if (!in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'title' => 'required|string|min:2',
            'points' => 'required|array|min:1',
            'points.*' => 'required|string|min:2',
            'is_active' => 'nullable|boolean',
        ]);

        // Default to active if not set (for backwards compatibility)
        $isActive = $request->has('is_active') ? (bool)$request->input('is_active') : true;

        if ($id) {
            $rule = RulesRegulation::findOrFail($id);
            $rule->update([
                'title' => $validated['title'],
                'points' => array_values($validated['points']),
                'is_active' => $isActive,
            ]);
            $actionType = 'updated';
            $message = "Rule updated: " . $rule->title;
        } else {
            $rule = RulesRegulation::create([
                'title' => $validated['title'],
                'points' => array_values($validated['points']),
                'is_active' => $isActive,
            ]);
            $actionType = 'created';
            $message = "Rule created: " . $rule->title;
        }

        // Activity log
        if (function_exists('log_activity')) {
            log_activity($message, $user, $actionType);
        }

        return redirect()->route('rules.index')->with('success', 'Rule saved successfully.');
    }
    // Delete a rule
    public function ruleDelete($id)
    {
        $user = auth()->user();
        if (!in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $rule = RulesRegulation::findOrFail($id);
        $title = $rule->title;
        $rule->delete();

        // Activity log
        if (function_exists('log_activity')) {
            $actionType = 'deleted';
            log_activity("Rule deleted: {$title}", $user, $actionType);
        }

        return back()->with('success', 'Rule deleted successfully.');
    }

    //Newsletter store
    public function newsletterStore(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        // Check if the email already exists in the newsletter table
        $newsletter = \App\Models\Newsletter::where('email', $validated['email'])->first();

        if ($newsletter) {
            if ($newsletter->is_subscribed) {
                // Log activity: already subscribed attempt
                if (function_exists('log_activity')) {
                    log_activity("Newsletter subscribe attempt: already subscribed ({$validated['email']})", null, 'newsletter');
                }
                return back()->with('success', 'You are already subscribed to the newsletter.');
            } else {
                $newsletter->update(['is_subscribed' => true]);
                // Log activity: re-subscribed
                if (function_exists('log_activity')) {
                    log_activity("Newsletter re-subscribed: {$validated['email']}", null, 'newsletter');
                }
                // Send welcome email on re-subscribe if enabled
                $webSetting = \App\Models\WebSetting::first();
                if ($webSetting && $webSetting->send_newsletter_email) {
                    $subject = "🎉 Welcome Back to the MFS Newsletter!";
                    $content = "Thank you for re-subscribing to the Muslim Funeral Society newsletter. We're excited to keep you updated with our latest news and events.";
                    \Mail::to($newsletter->email)->send(new \App\Mail\NewsletterMail($subject, $content));
                }
                return back()->with('success', 'You have been re-subscribed to the newsletter.');
            }
        } else {
            $newNewsletter = \App\Models\Newsletter::create([
                'email' => $validated['email'],
                'is_subscribed' => true,
            ]);
            // Log activity: new subscription
            if (function_exists('log_activity')) {
                log_activity("Newsletter subscribed: {$validated['email']}", null, 'newsletter');
            }
            // Send welcome email if enabled
            $webSetting = \App\Models\WebSetting::first();
            if ($webSetting && $webSetting->send_newsletter_email) {
                $subject = "🎉 Welcome to the MFS Newsletter!";
                $content = "Thank you for subscribing to the Muslim Funeral Society newsletter. We’re delighted to have you join our community. Stay tuned for updates, news, and more!";
                \Mail::to($newNewsletter->email)->send(new \App\Mail\NewsletterMail($subject, $content));
            }
            return back()->with('success', 'Thank you for subscribing to our newsletter!');
        }
    }
    //Contact-us store
    public function contactUsStore(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'message' => 'nullable|string',
        ]);

        // Store the contact us data (allow multiple submissions from the same email)
        $contact = \App\Models\ContactUs::create($validated);

        // Log activity for new contact submission
        if (function_exists('log_activity')) {
            log_activity("Contact Us form submitted: {$validated['email']}", null, 'contact_us');
        }

        // Check if sending contact us email is enabled in web settings
        $webSetting = \App\Models\WebSetting::first();
        if ($webSetting && $webSetting->send_contact_us_email) {
            // Send thank you email to the user
            \Mail::to($contact->email)->send(new \App\Mail\ContactUsNotification($contact));
            // Log activity for sending thank you email
            if (function_exists('log_activity')) {
                log_activity("Contact Us thank you email sent to: {$contact->email}", null, 'contact_us');
            }
        }

        // Redirect back with a success message
        return back()->with('success', 'Thank you for contacting us! We have received your message and will get back to you soon.');
    }

    // Show Burial Council
    public function burialCouncilEdit()
    {
        $user = auth()->guard()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $burialCouncil = \App\Models\BurialCouncil::first();
        if (!$burialCouncil) {
            $burialCouncil = new \App\Models\BurialCouncil();
        }
        return view('admin.public.burial_council.index', compact('burialCouncil'));
    }
    // Update the Burial Council
     public function burialCouncilUpdate(Request $request, $id)
    {
        $user = auth()->guard()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_desc' => 'nullable|string',
            'meta_keyword' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max
        ]);

        $burialCouncil = \App\Models\BurialCouncil::find($id);

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = uniqid('burial_') . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('uploads/burail'), $imageName);
            $validated['image'] = 'uploads/burail/' . $imageName;
        } else {
            unset($validated['image']);
        }

        if (!$burialCouncil) {
            // If not found, create a new one (since there should only be one)
            $burialCouncil = \App\Models\BurialCouncil::create($validated);
        } else {
            $burialCouncil->update($validated);
        }

        // Optionally log activity
        if (function_exists('log_activity')) {
            // Only pass $user to log_activity, as per helpers.php signature
            log_activity(
                "Burial Council updated: " . ($burialCouncil ? $burialCouncil->title : ''),
                $user // Pass the user model, or null
            );
        }

        return redirect()->back()->with('success', 'Burial Council updated successfully.');
    }
    // Show the edit form for HMBC
    public function hmbcEdit()
    {
        $user = auth()->guard()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $hmbc = \App\Models\HMBC::first();
        if (!$hmbc) {
            $hmbc = new \App\Models\HMBC();
        }
        return view('admin.public.hmbc.index', compact('hmbc'));
    }
    // Update or create HMBC
     public function hmbcUpdate(Request $request, $id = null)
    {
        $user = auth()->guard()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'location_title' => 'nullable|string|max:255',
            'location_desc' => 'nullable|string',
            'location_link' => 'nullable|string',
            'member_title' => 'nullable|string|max:255',
            'member_desc' => 'nullable|string',
            'pdf' => 'nullable|file|mimes:pdf|max:20480', // 20MB max
            'meta_title' => 'nullable|string|max:255',
            'meta_desc' => 'nullable|string',
            'meta_keyword' => 'nullable|string|max:255',
        ]);

        // Remove 'id' if present in validated data
        unset($validated['id']);

        // Handle PDF upload
        if ($request->hasFile('pdf')) {
            $pdfFile = $request->file('pdf');
            $pdfName = uniqid('hmbc_') . '.' . $pdfFile->getClientOriginalExtension();
            $pdfFile->move(public_path('uploads/hmbc'), $pdfName);
            $validated['pdf'] = 'uploads/hmbc/' . $pdfName;
        } else {
            unset($validated['pdf']);
        }

        $hmbc = null;
        if ($id) {
            $hmbc = \App\Models\HMBC::find($id);
        } else {
            $hmbc = \App\Models\HMBC::first();
        }

        if (!$hmbc) {
            $hmbc = \App\Models\HMBC::create($validated);
        } else {
            // If no new PDF uploaded, keep the old one
            if (!isset($validated['pdf'])) {
                unset($validated['pdf']);
            }
            $hmbc->update($validated);
        }

        // Optionally log activity
        if (function_exists('log_activity')) {
            log_activity(
                "HMBC updated: " . ($hmbc ? $hmbc->title : ''),
                $user
            );
        }

        return redirect()->back()->with('success', 'HMBC updated successfully.');
    }

    /**
     * Show the FAQ Update edit form.
     */
    public function faqUpdateEdit()
    {
        $user = auth()->guard()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $faqUpdate = \App\Models\FaqUpdate::first();
        if (!$faqUpdate) {
            $faqUpdate = new \App\Models\FaqUpdate();
        }
        return view('admin.public.faq.update', compact('faqUpdate'));
    }
    //   There is only one FAQ Update record (no id).
    public function faqUpdateUpdate(Request $request)
    {
        $user = auth()->guard()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,webp,bmp,svg|max:5120', // 5MB max, allow common image formats including webp
            'meta_title' => 'nullable|string|max:255',
            'meta_desc' => 'nullable|string',
            'meta_keyword' => 'nullable|string|max:255',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = uniqid('faq_update_') . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('uploads/faq-updates'), $imageName);
            $validated['image'] = 'uploads/faq-updates/' . $imageName;
        } else {
            unset($validated['image']);
        }

        $faqUpdate = \App\Models\FaqUpdate::first();

        if (!$faqUpdate) {
            $faqUpdate = \App\Models\FaqUpdate::create($validated);
        } else {
            // If no new image uploaded, keep the old one
            if (!isset($validated['image'])) {
                unset($validated['image']);
            }
            $faqUpdate->update($validated);
        }

        // Optionally log activity
        if (function_exists('log_activity')) {
            log_activity(
                "FAQ Update updated: " . ($faqUpdate ? $faqUpdate->title : ''),
                auth()->user()
            );
        }

        return redirect()->back()->with('success', 'FAQ Update saved successfully.');
    }

    // Show all pillars and the create/edit form (if $id provided, edit; else, create)
    public function fivePillars(Request $request, $id = null)
    {
        // Only allow super-admin or admin
        $user = auth()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $perPage = $request->input('per_page', 5); // Default to 5 per page, can be overridden by query param

        // Filtering logic
        $query = \App\Models\FivePillar::query();

        // Status filter
        $status = $request->input('status');
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
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

        // Search filter
        $search = $request->input('search');
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $pillars = $query->orderBy('id')->paginate($perPage)->appends($request->except('page'));

        $pillar = null;
        if ($id) {
            $pillar = \App\Models\FivePillar::find($id);
        }
        return view('admin.public.pillars.index', compact('pillars', 'pillar'));
    }

    public function pillarsCreate(Request $request, $id = null)
    {
        // Only allow super-admin or admin
        $user = auth()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $pillars = \App\Models\FivePillar::orderBy('id')->get();

        if ($id) {
            $pillar = \App\Models\FivePillar::find($id);
        } else {
            $pillar = new \App\Models\FivePillar();
        }

        return view('admin.public.pillars.create', compact('pillars', 'pillar'));
    }

    // Store or update a pillar
    public function fivePillarsStore(Request $request, $id = null)
    {
        $user = auth()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,webp,bmp,svg|max:5120',
            'is_active' => 'nullable|boolean',
        ];
        $validated = $request->validate($rules);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = uniqid('pillar_') . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('uploads/pillars'), $imageName);
            $validated['image'] = 'uploads/pillars/' . $imageName;
        } else {
            unset($validated['image']);
        }

        $validated['is_active'] = $request->has('is_active') ? (bool)$request->input('is_active') : false;

        if ($id) {
            $pillar = \App\Models\FivePillar::findOrFail($id);
            // If no new image uploaded, keep the old one
            if (!isset($validated['image'])) {
                unset($validated['image']);
            } else {
                // Optionally, delete old image file
                if ($pillar->image && file_exists(public_path($pillar->image))) {
                    @unlink(public_path($pillar->image));
                }
            }
            $pillar->update($validated);
            $msg = 'Pillar updated successfully.';
        } else {
            $pillar = \App\Models\FivePillar::create($validated);
            $msg = 'Pillar created successfully.';
        }

        // Optionally log activity
        if (function_exists('log_activity')) {
            log_activity(
                "Five Pillar " . ($id ? "updated" : "created") . ": " . $pillar->name,
                $user
            );
        }

        return redirect()->route('five_pillars.index')->with('success', $msg);
    }

    // Delete a pillar
    public function fivePillarsDelete($id)
    {
        // Only allow super-admin or admin
        $user = auth()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $pillar = \App\Models\FivePillar::findOrFail($id);
        // Delete image file if exists
        if ($pillar->image && file_exists(public_path($pillar->image))) {
            @unlink(public_path($pillar->image));
        }
        $pillar->delete();

        // Optionally log activity
        if (function_exists('log_activity')) {
            log_activity(
                "Five Pillar deleted: " . $pillar->name,
                $user
            );
        }

        return redirect()->route('five_pillars.index')->with('success', 'Pillar deleted successfully.');
    }

    // Show the edit form for HomeUpdate (no id, always editing the first/only record)
    public function homeUpdateEdit()
    {
        // Only allow super-admin or admin
        $user = auth()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $homeUpdate = \App\Models\HomeUpdate::first();
        if (!$homeUpdate) {
            $homeUpdate = \App\Models\HomeUpdate::create([]);
        }
        return view('admin.public.homeupdate.index', compact('homeUpdate'));
    }

    // Update the HomeUpdate record (no id, always updating the first/only record)
    public function homeUpdateUpdate(Request $request)
    {
        // Only allow super-admin or admin
        $user = auth()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'main_title' => 'nullable|string|max:255',
            'main_desc' => 'nullable|string',
            'main_image1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'main_image2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'section1_main_title' => 'nullable|string|max:255',
            'section1_title' => 'nullable|string|max:255',
            'section1_desc' => 'nullable|string',
            'section1_points' => 'nullable|array',
            'section1_points.*' => 'nullable|string',
            'section1_image1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'section1_image2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'section1_image3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'section2_title' => 'nullable|string|max:255',
            'section2_desc' => 'nullable|string',
            'section3_main_title' => 'nullable|string|max:255',
            'section3_title' => 'nullable|string|max:255',
            'section3_desc' => 'nullable|string',
            'section3_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'footer_main_title' => 'nullable|string|max:255',
            'footer_main_desc' => 'nullable|string',
            'footer_title' => 'nullable|string|max:255',
            'footer_desc' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_desc' => 'nullable|string',
            'meta_keyword' => 'nullable|string|max:255',
        ]);

        $homeUpdate = \App\Models\HomeUpdate::first();
        if (!$homeUpdate) {
            $homeUpdate = new \App\Models\HomeUpdate();
        }

        // Handle image uploads - store in public/uploads/homeupdate
        $imageFields = [
            'main_image1',
            'main_image2',
            'section1_image1',
            'section1_image2',
            'section1_image3',
            'section3_image'
        ];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old image if exists
                if ($homeUpdate->$field && file_exists(public_path($homeUpdate->$field))) {
                    @unlink(public_path($homeUpdate->$field));
                }
                $file = $request->file($field);
                $filename = uniqid($field . '_') . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('uploads/homeupdate');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $file->move($destinationPath, $filename);
                $validated[$field] = 'uploads/homeupdate/' . $filename;
            } else {
                // If not uploading new, don't overwrite with null
                unset($validated[$field]);
            }
        }

        // section1_points is an array, store as JSON (handled by $casts in model)
        if (isset($validated['section1_points'])) {
            // Remove empty values
            $validated['section1_points'] = array_filter($validated['section1_points'], function ($v) {
                return $v !== null && $v !== '';
            });
        }

        $homeUpdate->fill($validated);
        $homeUpdate->save();

        // Optionally log activity
        if (function_exists('log_activity')) {
            log_activity("HomeUpdate updated", $user);
        }

        return redirect()->back()->with('success', 'Home page content updated successfully.');
    }

    //about us
    public function aboutUsEdit()
    {
        $user = auth()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $aboutUs = \App\Models\AboutUs::first();
        if (!$aboutUs) {
            $aboutUs = new \App\Models\AboutUs();
            $aboutUs->title = [];
            $aboutUs->points = [];
        }

        return view('admin.public.aboutus.index', compact('aboutUs'));
    }

    /**
     * Expects 'title' as array of section titles, and 'points' as array of arrays.
     */
    public function aboutUsUpdate(Request $request)
    {
        $user = auth()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'title' => 'required|array',
            'title.*' => 'required|string|max:255',
            'points' => 'required|array',
            'points.*' => 'required|array',
            'points.*.*' => 'nullable|string|max:1000',
        ]);

        // Remove empty points from each section
        $cleanedPoints = [];
        foreach ($validated['points'] as $sectionPoints) {
            $cleanedPoints[] = array_values(array_filter($sectionPoints, function ($v) {
                return $v !== null && $v !== '';
            }));
        }

        $aboutUs = \App\Models\AboutUs::first();
        if (!$aboutUs) {
            $aboutUs = new \App\Models\AboutUs();
        }

        $aboutUs->meta_title = $validated['meta_title'] ?? null;
        $aboutUs->meta_description = $validated['meta_description'] ?? null;
        $aboutUs->meta_keywords = $validated['meta_keywords'] ?? null;
        $aboutUs->title = $validated['title'];
        $aboutUs->points = $cleanedPoints;
        $aboutUs->save();

        return redirect()->back()->with('success', 'About Us sections updated successfully.');
    }

    //new member
    public function newMemberEdit()
    {
        $user = auth()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }
        $newMember = \App\Models\NewMember::firstOrCreate([]);
        return view('admin.public.newmember.index', compact('newMember'));
    }

    /**
     * Update the New Member section.
     */
    public function newMemberUpdate(Request $request)
    {
        $user = auth()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'desc' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_desc' => 'nullable|string',
            'meta_keyword' => 'nullable|string|max:255',
            'pdf' => 'nullable|file|mimes:pdf|max:20480', // 20MB max
        ]);

        $newMember = \App\Models\NewMember::first();
        if (!$newMember) {
            $newMember = new \App\Models\NewMember();
        }

        $newMember->title = $validated['title'] ?? null;
        $newMember->desc = $validated['desc'] ?? null;
        $newMember->meta_title = $validated['meta_title'] ?? null;
        $newMember->meta_desc = $validated['meta_desc'] ?? null;
        $newMember->meta_keyword = $validated['meta_keyword'] ?? null;

        if ($request->hasFile('pdf')) {
            $pdf = $request->file('pdf');
            $pdfName = time() . '_' . uniqid() . '.' . $pdf->getClientOriginalExtension();
            $pdf->move(public_path('uploads/new_member'), $pdfName);
            $newMember->pdf = 'uploads/new_member/' . $pdfName;
        }

        $newMember->save();

        return redirect()->back()->with('success', 'New Member section updated successfully.');
    }

    //payment info
    /**
     * Show the Payment Info edit form.
     */
    public function paymentInfoEdit()
    {
        $user = auth()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }
        $paymentInfo = \App\Models\PaymentInfo::firstOrCreate([]);
        return view('admin.public.paymentinfo.index', compact('paymentInfo'));
    }

    /**
     * Update the Payment Info section.
     */
    public function paymentInfoUpdate(Request $request)
    {
        $user = auth()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'title' => 'nullable|array',
            'title.*' => 'nullable|string|max:255',
            'points' => 'nullable|array',
            'points.*' => 'nullable|array',
            'points.*.*' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        // Remove empty points from each section
        $cleanedPoints = [];
        if (isset($validated['points']) && is_array($validated['points'])) {
            foreach ($validated['points'] as $sectionPoints) {
                $cleanedPoints[] = array_values(array_filter($sectionPoints, function ($v) {
                    return $v !== null && $v !== '';
                }));
            }
        }

        $paymentInfo = \App\Models\PaymentInfo::first();
        if (!$paymentInfo) {
            $paymentInfo = new \App\Models\PaymentInfo();
        }

        $paymentInfo->meta_title = $validated['meta_title'] ?? null;
        $paymentInfo->meta_description = $validated['meta_description'] ?? null;
        $paymentInfo->meta_keywords = $validated['meta_keywords'] ?? null;
        $paymentInfo->title = $validated['title'] ?? [];
        $paymentInfo->points = $cleanedPoints;
        $paymentInfo->note = $validated['note'] ?? null;

        $paymentInfo->save();

        return redirect()->back()->with('success', 'Payment Info section updated successfully.');
    }

    /**
     * Show the form for editing the Payment Status.
     */
    public function paymentStatusEdit()
    {
        $user = auth()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }
        $paymentStatus = \App\Models\PaymentStatus::first();
        if (!$paymentStatus) {
            $paymentStatus = new \App\Models\PaymentStatus();
        }
        return view('admin.public.payment_status.index', compact('paymentStatus'));
    }
    /**
     * Update the Payment Status.
     */
    public function paymentStatusUpdate(Request $request)
    {
        $user = auth()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'excel_title' => 'nullable|string|max:255',
            'excel_file' => 'nullable|url',
        ]);

        $paymentStatus = \App\Models\PaymentStatus::first();
        if (!$paymentStatus) {
            $paymentStatus = new \App\Models\PaymentStatus();
        }

        $paymentStatus->title = $validated['title'] ?? null;
        $paymentStatus->description = $validated['description'] ?? null;
        $paymentStatus->meta_title = $validated['meta_title'] ?? null;
        $paymentStatus->meta_description = $validated['meta_description'] ?? null;
        $paymentStatus->meta_keywords = $validated['meta_keywords'] ?? null;
        $paymentStatus->excel_title = $validated['excel_title'] ?? null;

        // Save the excel_file as a link (URL)
        if (!empty($validated['excel_file'])) {
            $paymentStatus->excel_file = $validated['excel_file'];
        }

        $paymentStatus->save();

        return redirect()->back()->with('success', 'Payment Status updated successfully.');
    }

    /**
     * Show the edit form for Contact Update.
     */
    public function contactUpdateEdit()
    {
        $user = auth()->guard()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $contactUpdate = \App\Models\ContactUpdate::first();
        if (!$contactUpdate) {
            $contactUpdate = new \App\Models\ContactUpdate();
        }
        return view('admin.public.contact_update.index', compact('contactUpdate'));
    }

    /**
     * Update or create Contact Update.
     */
    public function contactUpdateStore(Request $request)
    {
        $user = auth()->guard()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'desc' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_desc' => 'nullable|string',
            'meta_keyword' => 'nullable|string|max:255',
        ]);

        // Remove 'id' if present in validated data
        unset($validated['id']);

        // Use firstOrCreate or update
        $contactUpdate = \App\Models\ContactUpdate::first();
        if (!$contactUpdate) {
            $contactUpdate = \App\Models\ContactUpdate::create($validated);
        } else {
            $contactUpdate->update($validated);
        }

        // Log activity
        if (function_exists('log_activity')) {
            log_activity(
                "Contact Update updated: " . ($contactUpdate ? $contactUpdate->title : ''),
                $user
            );
        }

        return redirect()->back()->with('success', 'Contact Update updated successfully.');
    }
    /**
     * Show the form for editing the Rules & Regulation Update.
     */
    public function rulesRegulationUpdateEdit()
    {
        $user = auth()->guard()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $rulesRegulationUpdate = \App\Models\RulesRegulationUpdate::first();
        if (!$rulesRegulationUpdate) {
            $rulesRegulationUpdate = \App\Models\RulesRegulationUpdate::create([]);
        }

        return view('admin.public.rules_regulation_update.index', compact('rulesRegulationUpdate'));
    }

    /**
     * Update or create Rules & Regulation Update.
     */
    public function rulesRegulationUpdateUpdate(Request $request)
    {
        $user = auth()->guard()->user();
        if (!$user || !in_array($user->type, ['super-admin', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'meta_title' => 'nullable|string|max:255',
            'meta_desc' => 'nullable|string',
            'meta_keyword' => 'nullable|string|max:255',
        ]);

        // Remove 'id' if present in validated data
        unset($validated['id']);

        $rulesRegulationUpdate = \App\Models\RulesRegulationUpdate::first();
        if (!$rulesRegulationUpdate) {
            $rulesRegulationUpdate = \App\Models\RulesRegulationUpdate::create($validated);
        } else {
            $rulesRegulationUpdate->update($validated);
        }

        // Log activity
        if (function_exists('log_activity')) {
            log_activity(
                "Rules & Regulation Update updated: " . ($rulesRegulationUpdate ? $rulesRegulationUpdate->meta_title : ''),
                $user
            );
        }

        return redirect()->back()->with('success', 'Rules & Regulation Update updated successfully.');
    }
}
