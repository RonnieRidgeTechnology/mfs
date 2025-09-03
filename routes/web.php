<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BasicController;
use App\Http\Controllers\PublicController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\MemberMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use Illuminate\Support\Facades\Route;


//login
Route::get('/login', [AuthController::class, 'loginpage'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
//forget
Route::get('/forget-password', [AuthController::class, 'showResetForm'])->name('reset');
Route::post('/forget-password', [AuthController::class, 'sendResetLinkEmail'])->name('reset.submit');
//reset
Route::get('/reset-password-form', [AuthController::class, 'showPasswordResetFormOtp'])->name('password.reset.otp');
Route::post('password/reset', [AuthController::class, 'resetPassword'])->name('password.update');
//OTP
Route::get('/verify-otp', [AuthController::class, 'showOtpForm'])->name('otp.verify.view');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/otp/check', [AuthController::class, 'checkOtp'])->name('otp.check');
Route::post('/verify-otp-live', [AuthController::class, 'verifyOtpLive'])->name('otp.verify.live');

// superadmin
Route::middleware(['auth', SuperAdminMiddleware::class])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashbaord'])->name('dashboard');
    //profile
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password.update');
    // Admin module
    Route::get('admin', [AuthController::class, 'adminindex'])->name('admin.list');
    Route::post('admin', [AuthController::class, 'storeOrUpdateAdmin'])->name('admin.store');
    Route::put('admin/{id}', [AuthController::class, 'storeOrUpdateAdmin'])->name('admin.update');
    Route::get('admin/create', [AuthController::class, 'createAdmin'])->name('admin.create');
    Route::get('admin/{unique_id}/edit', [AuthController::class, 'editAdmin'])->name('admin.edit');
    Route::delete('admin/{id}', [AuthController::class, 'deleteAdmin'])->name('admin.destroy');
    // Memeber module
    Route::get('member', [AuthController::class, 'memeberindex'])->name('member.list');
    Route::post('member', [AuthController::class, 'storeOrUpdateMember'])->name('member.store');
    Route::put('member/{unique_id}', [AuthController::class, 'storeOrUpdateMember'])->name('member.update');
    Route::get('member/create', [AuthController::class, 'createMember'])->name('member.create');
    Route::get('member/{unique_id}/edit', [AuthController::class, 'editMember'])->name('member.edit');
    Route::delete('member/{id}', [AuthController::class, 'deleteMember'])->name('member.destroy');
    //guest
    // Guest user module
    Route::get('all-guest', [AuthController::class, 'guestindex'])->name('guest.list');
    //Membership Setting
    Route::get('membership-setting', [AuthController::class, 'Membershipindex'])->name('membership.setting');
    Route::post('membership-setting', [AuthController::class, 'Membershipstore'])->name('membership.setting.store');
    Route::get('membership-setting/{id}/edit', [AuthController::class, 'Membershipedit'])->name('membership.setting.edit');
    Route::put('membership-setting/{id}', [AuthController::class, 'Membershipupdate'])->name('membership.setting.update');
    //transaction
    Route::get('transactions', [AuthController::class, 'transactionindex'])->name('transactions.list');
    Route::post('admin/transactions/import', [AuthController::class, 'import'])->name('admin.transactions.import');
    Route::delete('transactions/{id}', [AuthController::class, 'deleteTransaction'])->name('transactions.destroy');

    //Arhive transactions
    Route::get('transactions/archive', [AuthController::class, 'archivetransactionindex'])->name('transactions.archive');
    //Debeit transactions
    // Route::get('transactions/debit', [AuthController::class, 'debeittransactionindex'])->name('transactions.debit');
    //Credit transactions
    Route::get('transactions/credit', [AuthController::class, 'credittransactionindex'])->name('transactions.credit');
    //import view
    Route::get('/transactions/import', [AuthController::class, 'importView'])->name('admin.transactions.import.view');
    // flagged transactions page
    Route::get('/transactions/flagged', [AuthController::class, 'flaggedTransactionsView'])->name('admin.transactions.flagged.view');
    // accept flagged
    Route::post('/transactions/flagged/accept/{id}', [AuthController::class, 'acceptFlaggedTransaction'])->name('admin.transactions.flagged.accept');
    // ignore flagged
    Route::delete('/transactions/flagged/ignore/{id}', [AuthController::class, 'ignoreFlaggedTransaction'])->name('admin.transactions.flagged.ignore');
    // Guest user management
    Route::post('/guest/promote/{id}', [AuthController::class, 'promoteGuestUser'])->name('admin.guest.promote');
    Route::post('/guest/assign/{id}', [AuthController::class, 'assignGuestToMember'])->name('admin.guest.assign');
    Route::get('/members/list', [AuthController::class, 'getMembersList'])->name('admin.members.list');
    // No ID transaction management
    Route::get('/admin/get-all-members', [AuthController::class, 'getAllMembers'])->name('admin.get.all.members');
    Route::post('/admin/transactions/ignore-no-id', [AuthController::class, 'ignoreNoIdTransaction'])->name('admin.transactions.ignore.no.id');
    Route::post('/admin/transactions/assign-no-id', [AuthController::class, 'assignNoIdTransaction'])->name('admin.transactions.assign.no.id');
    Route::post('/admin/transactions/create-member-assign-no-id', [AuthController::class, 'createMemberAndAssignNoIdTransaction'])->name('admin.transactions.create.member.assign.no.id');

    // Debug route for checking transaction names
    Route::get('/debug/transaction-names', function() {
        $transactions = \App\Models\Transaction::where('no_id_flaged', 0)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get(['id', 'name', 'created_at']);

        $output = "<h2>Debug: Transaction Names</h2>";
        $output .= "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        $output .= "<tr><th>ID</th><th>Name</th><th>Length</th><th>Created</th></tr>";

        foreach($transactions as $transaction) {
            $output .= "<tr>";
            $output .= "<td>{$transaction->id}</td>";
            $output .= "<td style='max-width: 300px; word-wrap: break-word;'>" . htmlspecialchars($transaction->name) . "</td>";
            $output .= "<td>" . strlen($transaction->name) . "</td>";
            $output .= "<td>{$transaction->created_at}</td>";
            $output .= "</tr>";
        }

        $output .= "</table>";
        return $output;
    });

    // API route for loading all members (for assign functionality)
    Route::get('/api/members/all', function() {
        try {
            \Log::info('API: Loading members...');

            $members = \App\Models\User::where('type', 'member')
                ->where('is_guest', 0)
                ->select(['id', 'name', 'email', 'unique_id'])
                ->orderBy('name')
                ->get();

            \Log::info('API: Found ' . $members->count() . ' members');

            return response()->json([
                'success' => true,
                'members' => $members,
                'count' => $members->count()
            ]);
        } catch (\Exception $e) {
            \Log::error('API: Error loading members: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'members' => []
            ], 500);
        }
    });

    // Test route to check database connection and user data
    Route::get('/api/members/test', function() {
        try {
            $totalUsers = \App\Models\User::count();
            $memberUsers = \App\Models\User::where('type', 'member')->count();
            $nonGuestMembers = \App\Models\User::where('type', 'member')->where('is_guest', 0)->count();

            $sampleMembers = \App\Models\User::where('type', 'member')
                ->where('is_guest', 0)
                ->take(5)
                ->get(['id', 'name', 'email', 'unique_id', 'type', 'is_guest']);

            return response()->json([
                'success' => true,
                'stats' => [
                    'total_users' => $totalUsers,
                    'member_users' => $memberUsers,
                    'non_guest_members' => $nonGuestMembers
                ],
                'sample_members' => $sampleMembers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });

    // Debug route to test member loading directly
    Route::get('/debug/members', function() {
        echo "<h2>Member Loading Debug</h2>";

        try {
            $totalUsers = \App\Models\User::count();
            $memberUsers = \App\Models\User::where('type', 'member')->count();
            $nonGuestMembers = \App\Models\User::where('type', 'member')->where('is_guest', 0)->count();
            $activeNonGuestMembers = \App\Models\User::where('type', 'member')->where('is_guest', 0)->where('status', 1)->count();

            echo "<p>Total Users: $totalUsers</p>";
            echo "<p>Member Users: $memberUsers</p>";
            echo "<p>Non-Guest Members: $nonGuestMembers</p>";
            echo "<p>Active Non-Guest Members: $activeNonGuestMembers</p>";

            $sampleMembers = \App\Models\User::where('type', 'member')
                ->where('is_guest', 0)
                ->where('status', 1)
                ->take(10)
                ->get(['id', 'name', 'email', 'unique_id', 'status']);

            echo "<h3>Sample Active Members:</h3>";
            echo "<table border='1'>";
            echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Unique ID</th><th>Status</th></tr>";
            foreach($sampleMembers as $member) {
                echo "<tr>";
                echo "<td>{$member->id}</td>";
                echo "<td>{$member->name}</td>";
                echo "<td>{$member->email}</td>";
                echo "<td>{$member->unique_id}</td>";
                echo "<td>{$member->status}</td>";
                echo "</tr>";
            }
            echo "</table>";

            echo "<h3>Test API Call:</h3>";
            echo "<button onclick=\"testAPI()\">Test /admin/get-all-members</button>";
            echo "<div id='apiResult'></div>";

            echo "<script>
            function testAPI() {
                fetch('/admin/get-all-members', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('API Response:', data);
                    document.getElementById('apiResult').innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('apiResult').innerHTML = '<p style=\"color: red;\">Error: ' + error.message + '</p>';
                });
            }
            </script>";

        } catch (\Exception $e) {
            echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
        }
    });

    //Manually transaction
    Route::get('/transactions/create', [AuthController::class, 'Manuallycreate'])->name('Manually.transactions.create');
    Route::post('/transactions', [AuthController::class, 'Manuallystore'])->name('Manually.transactions.store');
    //websetting
    Route::get('/web-settings', [AuthController::class, 'websettingindex'])->name('websettings.index');
    Route::post('/web-settings', [AuthController::class, 'websettingupdate'])->name('websettings.update');

    //FAQ
    Route::get('admin/faqs', [PublicController::class, 'faqIndex'])->name('faqs.index');
    Route::get('admin/faqs/create', [PublicController::class, 'createOrEditFaqForm'])->name('faqs.create');
    Route::post('admin/faqs', [PublicController::class, 'storeOrUpdateFaq'])->name('faqs.store');
    Route::put('admin/faqs/{faq}', [PublicController::class, 'storeOrUpdateFaq'])->name('faqs.update');
    Route::get('admin/faqs/{id}/edit', [PublicController::class, 'createOrEditFaqForm'])->name('faqs.edit');
    Route::delete('admin/faqs/{id}', [PublicController::class, 'deleteFaq'])->name('faqs.destroy');
    // Rules & Regulations
    Route::get('rules', [PublicController::class, 'ruleIndex'])->name('rules.index');
    Route::get('rules/create', [PublicController::class, 'ruleForm'])->name('rules.create');
    Route::get('rules/{id}/edit', [PublicController::class, 'ruleForm'])->name('rules.edit');
    Route::post('rules', [PublicController::class, 'ruleSave'])->name('rules.store');
    Route::put('rules/{id}', [PublicController::class, 'ruleSave'])->name('rules.update');
    Route::delete('rules/{id}', [PublicController::class, 'ruleDelete'])->name('rules.destroy');
    //newsletter
    Route::get('admin/newsletter', [AuthController::class, 'showNewsletterAdmin'])->name('admin.newsletter.index');
    Route::delete('admin/newsletter/{id}', [AuthController::class, 'deleteNewsletterSubscriber'])->name('admin.newsletter.delete');
    // Contact Us
    Route::get('admin/contactus', [AuthController::class, 'contactUsIndex'])->name('admin.contactus.index');
    Route::delete('admin/contactus/{id}', [AuthController::class, 'deleteContactUs'])->name('admin.contactus.delete');
    // Burial Council (admin)
    Route::get('admin/burial-council', [PublicController::class, 'burialCouncilEdit'])->name('burial_council.index');
    Route::post('admin/burial-council/{id}', [PublicController::class, 'burialCouncilUpdate'])->name('burial_council.update');
    // HMBC (admin)
    Route::get('admin/hmbc', [PublicController::class, 'hmbcEdit'])->name('hmbc.index');
    Route::post('admin/hmbc/{id?}', [PublicController::class, 'hmbcUpdate'])->name('hmbc.update');
    // FAQ Update
    Route::get('admin/faq-update', [PublicController::class, 'faqUpdateEdit'])->name('faq_update.edit');
    Route::post('admin/faq-update', [PublicController::class, 'faqUpdateUpdate'])->name('faq_update.update');
    // Five Pillars (admin)
    Route::get('admin/five-pillars', [PublicController::class, 'fivePillars'])->name('five_pillars.index');
    Route::get('admin/five-pillars/create', [PublicController::class, 'pillarsCreate'])->name('five_pillars.create');
    Route::get('admin/five-pillars/{id}/edit', [PublicController::class, 'pillarsCreate'])->name('five_pillars.edit');
    Route::post('admin/five-pillars', [PublicController::class, 'fivePillarsStore'])->name('five_pillars.store');
    Route::put('admin/five-pillars/{id}', [PublicController::class, 'fivePillarsStore'])->name('five_pillars.update');
    Route::delete('admin/five-pillars/{id}', [PublicController::class, 'fivePillarsDelete'])->name('five_pillars.destroy');
    // Home Update (admin)
    Route::get('admin/home-update', [PublicController::class, 'homeUpdateEdit'])->name('home_update.edit');
    Route::post('admin/home-update', [PublicController::class, 'homeUpdateUpdate'])->name('home_update.update');
    // Member proifle
    Route::get('member/{name}/{unique_id}', [AuthController::class, 'memberTransactionDetail'])->name('member.transactions.detail');
    //Admin profile
    Route::get('admin/{name}/{unique_id}', [AuthController::class, 'AdminDetail'])->name('admin.transactions.detail');
    // About Us (admin)
    Route::get('admin/about-us', [PublicController::class, 'aboutUsEdit'])->name('about_us.edit');
    Route::post('admin/about-us', [PublicController::class, 'aboutUsUpdate'])->name('about_us.update');
    // New Member (admin)
    Route::get('admin/new-member', [PublicController::class, 'newMemberEdit'])->name('new_member.edit');
    Route::post('admin/new-member', [PublicController::class, 'newMemberUpdate'])->name('new_member.update');
    // Payment Info (admin)
    Route::get('admin/payment-info', [PublicController::class, 'paymentInfoEdit'])->name('payment_info.edit');
    Route::post('admin/payment-info', [PublicController::class, 'paymentInfoUpdate'])->name('payment_info.update');
    // Payment Status (admin)
    Route::get('admin/payment-status', [PublicController::class, 'paymentStatusEdit'])->name('payment_status.edit');
    Route::post('admin/payment-status', [PublicController::class, 'paymentStatusUpdate'])->name('payment_status.update');
    // Member's own transactions
    Route::get('member/my-transactions', [AuthController::class, 'myTransactions'])->name('member.my_transactions');
    // Contact Update (admin)
    Route::get('admin/contact-update', [PublicController::class, 'contactUpdateEdit'])->name('contact_update.edit');
    Route::post('admin/contact-update', [PublicController::class, 'contactUpdateStore'])->name('contact_update.update');
    // Rules & Regulation Update (admin)
    Route::get('admin/rules-regulation-update', [PublicController::class, 'rulesRegulationUpdateEdit'])->name('rules_regulation_update.edit');
    Route::post('admin/rules-regulation-update', [PublicController::class, 'rulesRegulationUpdateUpdate'])->name('rules_regulation_update.update');
});



// web routes
//index
Route::get('/', [BasicController::class, 'index'])->name('index');
//faq
Route::get('/faq', [BasicController::class, 'faq'])->name('faq.public');
// Newsletter subscription route
Route::post('/newsletter/subscribe', [PublicController::class, 'newsletterStore'])->name('newsletter.subscribe');
//rules&reguation
Route::get('/rules-regulations', [BasicController::class, 'rules'])->name('rules.public');
//contact us
Route::get('/contact-us', [BasicController::class, 'contactus'])->name('contact.public');
//contact-us store
Route::post('/contact-us', [PublicController::class, 'contactUsStore'])->name('contactus.store');
//hmbc
Route::get('/hmbc', [BasicController::class, 'hmbc'])->name('hmbc.public');
//concil
Route::get('/what-to-do-during-a-bereavement', [BasicController::class, 'council'])->name('council.public');
//about us
Route::get('/about-us', [BasicController::class, 'about'])->name('about.public');
// new member
Route::get('/new-membership', [BasicController::class, 'newmember'])->name('newmember.public');
//payement
Route::get('/payment-info', [BasicController::class, 'payment'])->name('payment.public');
//payment success
Route::get('/membership-payment-status', [BasicController::class, 'paymentsucces'])->name('paymentsucces.public');

// Transaction Progress Lookup API (Public)
Route::get('/api/transaction-progress/{uniqueId}/{year}', [BasicController::class, 'getTransactionProgress'])->name('api.transaction.progress');
