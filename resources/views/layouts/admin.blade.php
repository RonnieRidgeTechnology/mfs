<!DOCTYPE html>
<html lang="en">
@php
    use App\Models\WebSetting;
    use App\Models\HomeUpdate;

    $websetting = WebSetting::first();
    $homeupdate = HomeUpdate::first();
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MFS Admin Panel</title>
    <link rel="icon" type="image/x-icon"
        href="{{ isset($websetting->favicon_icon) ? asset($websetting->favicon_icon) : '' }}">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.4.0/css/all.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/styles/transaction.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/styles/import.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/styles/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/styles/snackbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/styles/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/styles/memberfee.css') }}">

</head>

<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="brand">
                <a href="{{ route('dashboard') }}" class="brand-logo">
                    @php
                        $user = Auth::user();
                    @endphp
                    <span>
                        @if ($user && $user->type === 'super-admin')
                            MFS Admin
                        @elseif($user && $user->type === 'admin')
                            MFS Admin
                        @elseif($user && $user->type === 'member')
                            MFS Member
                        @else
                            MFS
                        @endif
                    </span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-header">Main</div>
                <div class="nav-item active">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="fa-light fa-house"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
            </div>

            <div class="nav-section">
                <div class="nav-header">Management</div>
                @if (auth()->check() && (auth()->user()->type === 'super-admin' || auth()->user()->type === 'admin'))
                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fa-light fa-layer-group"></i>
                            <span>
                                @if (auth()->user()->type === 'admin')
                                    Member Module
                                @else
                                    Admin Module
                                @endif
                            </span>
                            <i class="fa-light fa-angle-right arrow"></i>
                        </a>
                        <div class="sub-nav">
                            @if (auth()->user()->type === 'super-admin')
                                <a href="{{ route('admin.list') }}" class="nav-link">
                                    <i class="fa-light fa-user"></i>
                                    <span>Add Admin</span>
                                </a>
                                <a href="{{ route('guest.list') }}" class="nav-link">
                                    <i class="fa-light fa-user-clock"></i>
                                    <span>Guest Members</span>
                                </a>
                            @endif
                            <a href="{{ route('member.list') }}" class="nav-link">
                                <i class="fa-light fa-users"></i>
                                <span>Add Members</span>
                            </a>
                        </div>
                    </div>
                @endif

                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fa-light fa-sitemap"></i>
                        <span>Transactions</span>
                        <i class="fa-light fa-angle-right arrow"></i>
                    </a>
                    <div class="sub-nav">
                        @if (auth()->check() && auth()->user()->type === 'super-admin')
                            <a href="{{ route('transactions.list') }}" class="nav-link">
                                <i class="fa-light fa-credit-card"></i>
                                <span>All Transactions</span>
                            </a>
                            {{-- <a href="{{ route('transactions.debit') }}" class="nav-link">
                                <i class="fa-light fa-arrow-down"></i>
                                <span>Debit Transactions</span>
                            </a> --}}
                            <a href="{{ route('transactions.credit') }}" class="nav-link">
                                <i class="fa-light fa-arrow-up"></i>
                                <span>Credit Transactions</span>
                            </a>
                            <a href="{{ route('admin.transactions.import.view') }}" class="nav-link">
                                <i class="fa-light fa-file-import"></i>
                                <span>Import Transactions</span>
                            </a>
                            <a href="{{ route('admin.transactions.flagged.view') }}" class="nav-link">
                                <i class="fa-light fa-flag"></i>
                                <span>Flagged Transactions</span>
                            </a>
                            <a href="{{ route('transactions.archive') }}" class="nav-link">
                                <i class="fa-light fa-box-archive"></i>
                                <span>Archives</span>
                            </a>
                        @endif
                        @if (auth()->check() && auth()->user()->type === 'member')
                            <a href="{{ route('member.my_transactions') }}" class="nav-link">
                                <i class="fa-light fa-wallet"></i>
                                <span>My Transactions</span>
                            </a>
                        @endif
                    </div>
                </div>
                @if (auth()->check() && (auth()->user()->type === 'super-admin' || auth()->user()->type === 'admin'))
                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fa-light fa-globe"></i>
                            <span>Pulbic</span>
                            <i class="fa-light fa-angle-right arrow"></i>
                        </a>
                        <div class="sub-nav">
                            <a href="{{ route('faqs.index') }}" class="nav-link">
                                <i class="fa-light fa-question-circle"></i>
                                <span>FAQs</span>
                            </a>
                            <a href="{{ route('faq_update.edit') }}" class="nav-link">
                                <i class="fa-light fa-pen-to-square"></i>
                                <span>FAQ Update</span>
                            </a>
                            <a href="{{ route('rules.index') }}" class="nav-link">
                                <i class="fa-light fa-gavel"></i>
                                <span>Rules & Regulations</span>
                            </a>
                            <a href="{{ route('rules_regulation_update.edit') }}" class="nav-link">
                                <i class="fa-light fa-pen-ruler"></i>
                                <span>Rules & Regulation Update</span>
                            </a>
                            <a href="{{ route('burial_council.index') }}" class="nav-link">
                                <i class="fa-light fa-users-viewfinder"></i>
                                <span>Burial Council</span>
                            </a>
                            <a href="{{ route('hmbc.index') }}" class="nav-link">
                                <i class="fa-light fa-building-columns"></i>
                                <span>HMBC</span>
                            </a>
                            <a href="{{ route('five_pillars.index') }}" class="nav-link">
                                <i class="fa-light fa-hand-holding-heart"></i>
                                <span>Five Pillars</span>
                            </a>
                            <a href="{{ route('home_update.edit') }}" class="nav-link">
                                <i class="fa-light fa-house"></i>
                                <span>Home Update </span>
                            </a>
                            <a href="{{ route('about_us.edit') }}" class="nav-link">
                                <i class="fa-light fa-circle-info"></i>
                                <span>About Us</span>
                            </a>
                            <a href="{{ route('contact_update.edit') }}" class="nav-link">
                                <i class="fa-light fa-address-book"></i>
                                <span>Contact Update</span>
                            </a>
                            <a href="{{ route('new_member.edit') }}" class="nav-link">
                                <i class="fa-light fa-user-plus"></i>
                                <span>New Member page</span>
                            </a>
                            <a href="{{ route('payment_info.edit') }}" class="nav-link">
                                <i class="fa-light fa-credit-card"></i>
                                <span>Payment Info</span>
                            </a>
                            <a href="{{ route('payment_status.edit') }}" class="nav-link">
                                <i class="fa-light fa-money-check-dollar"></i>
                                <span>Payment Status</span>
                            </a>

                        </div>

                    </div>
                @endif

                {{-- <div class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fa-light fa-users"></i>
                        <span>Employees</span>
                        <i class="fa-light fa-angle-right arrow"></i>
                    </a>
                    <div class="sub-nav">
                        <a href="#" class="nav-link">
                            <i class="fa-light fa-user-plus"></i>
                            <span>Add Employee</span>
                        </a>
                        <a href="#" class="nav-link">
                            <i class="fa-light fa-rectangle-list"></i>
                            <span>View Employees</span>
                        </a>
                    </div>
                </div> --}}
            </div>
            @if (auth()->user()->type === 'super-admin')
                <div class="nav-section">
                    <div class="nav-header">Settings</div>
                    <div class="nav-item">

                        <a href="{{ route('membership.setting') }}" class="nav-link">
                            <i class="fa-light fa-wallet"></i>
                            <span>Fee Management</span>
                        </a>
                        <a href="{{ route('admin.newsletter.index') }}" class="nav-link">
                            <i class="fa-light fa-envelope"></i>
                            <span>Newsletter</span>
                        </a>
                        <a href="{{ route('admin.contactus.index') }}" class="nav-link">
                            <i class="fa-light fa-address-book"></i>
                            <span>Contact Us</span>
                        </a>
                        <a href="{{ route('websettings.index') }}" class="nav-link">
                            <i class="fa-light fa-gear"></i>
                            <span>Web Settings</span>
                        </a>
                    </div>
                </div>
            @endif

        </aside>
        @yield('content')
    </div>

    <!-- Custom Toast Container -->
    <div class="custom-snackbar-container" id="custom-snackbar-container"></div>

    @if (session('success') || $errors->any())
        <script>
            function showCustomSnackbar(message, type = 'success') {
                const icons = {
                    success: '<i class="fa-solid fa-circle-check snackbar-icon"></i>',
                    error: '<i class="fa-solid fa-circle-xmark snackbar-icon"></i>',
                    info: '<i class="fa-solid fa-circle-info snackbar-icon"></i>',
                    warning: '<i class="fa-solid fa-triangle-exclamation snackbar-icon"></i>',
                };

                let container = document.getElementById('custom-snackbar-container');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'custom-snackbar-container';
                    container.className = 'custom-snackbar-container';
                    document.body.appendChild(container);
                }

                const snackbar = document.createElement('div');
                snackbar.className = `custom-snackbar ${type}`;
                snackbar.innerHTML = `
                            ${icons[type] || icons.info}
                            <div class="snackbar-message">${message}</div>
                            <button class="close-btn" title="Close">
                                <i class="fa-solid fa-times"></i>
                            </button>
                        `;

                snackbar.querySelector('.close-btn').addEventListener('click', () => {
                    snackbar.classList.remove('show');
                    setTimeout(() => snackbar.remove(), 500); // Wait for transition
                });

                container.appendChild(snackbar);

                setTimeout(() => {
                    snackbar.classList.add('show');
                }, 10);
            }

            @if (session('success'))
                showCustomSnackbar(@json(session('success')), 'success');
            @else
                showCustomSnackbar(@json($errors->first()), 'error');
            @endif
        </script>
    @endif
    <script src="{{ asset('assets/admin/js/admin.js') }}"></script>
    <script src="{{ asset('assets/admin/js/transaction.js') }}"></script>
    <script src="{{ asset('assets/admin/js/import.js') }}"></script>
    <script src="{{ asset('assets/admin/js/profile.js') }}"></script>
    <script src="{{ asset('assets/admin/js/memberfee.js') }}"></script>

    @yield('script')

</body>

</html>
