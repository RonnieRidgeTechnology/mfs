<header class="header">
    <div class="search-wrapper" style="position: relative;">
        <div class="search-bar">
            <i class="fa-light fa-search"></i>
            <input type="text" id="search-input" placeholder="Search anything...">
            <div class="search-shortcuts"><span>⌘</span><span>K</span></div>
        </div>
        <div id="search-dropdown" class="search-dropdown" style="display: none;">
            @if(auth()->check() && (auth()->user()->type === 'super-admin' || auth()->user()->type === 'admin'))
                <div class="search-dropdown-inner">
                    @if(auth()->user()->type === 'super-admin')

                        <a href="{{ route('admin.list') }}" class="search-item" data-title="Add Admin">
                            <i class="fa-light fa-circle-plus"></i> Add Admin
                        </a>
                        <a href="{{ route('transactions.list') }}" class="search-item" data-title="All Transactions">
                            <i class="fa-light fa-money-check-dollar"></i> Transactions
                        </a>
                        <a href="{{ route('faqs.index') }}" class="search-item" data-title="All Transactions">
                            <i class="fa-light fa-question-circle"></i> FAQs
                        </a>
                        <a href="{{ route('rules.index') }}" class="search-item" data-title="Rules">
                            <i class="fa-light fa-gavel"></i> Rules & Regulations
                        </a>
                    @endif

                    <a href="{{ route('member.list') }}" class="search-item" data-title="Add Members">
                        <i class="fa-light fa-table-list"></i> Add Members
                    </a>
                    @if(auth()->user()->type === 'super-admin')
                        {{-- <a href="{{ route('transactions.debit') }}" class="search-item" data-title="Debit Transactions">
                            <i class="fa-light fa-arrow-down"></i> Debit Transactions
                        </a> --}}
                        <a href="{{ route('transactions.credit') }}" class="search-item" data-title="Credit Transactions">
                            <i class="fa-light fa-arrow-up"></i> Credit Transactions
                        </a>
                        <a href="{{ route('transactions.archive') }}" class="search-item" data-title="Archived Transactions">
                            <i class="fa-light fa-archive"></i> Archived Transactions
                        </a>
                        <a href="{{ route('admin.transactions.import.view') }}" class="search-item" data-title="Import">
                            <i class="fa-light fa-file-import"></i> Import Transactions
                        </a>
                        <a href="{{ route('membership.setting') }}" class="search-item" data-title="Membership Settings">
                            <i class="fa-light fa-id-card-clip"></i> Membership Settings
                        </a>
                        <a href="{{ route('websettings.index') }}" class="search-item" data-title="Web Settings">
                            <i class="fa-light fa-gear"></i> Web Settings
                        </a>
                        <a href="{{ route('admin.contactus.index') }}" class="search-item" data-title="Contact Us">
                            <i class="fa-light fa-envelope"></i> Contact Us
                        </a>
                        <a href="{{ route('admin.newsletter.index') }}" class="search-item" data-title="Newsletter Subscribers">
                            <i class="fa-light fa-newspaper"></i> Newsletter Subscribers
                        </a>
                        <a href="{{ route('guest.list') }}" class="search-item" data-title="All Guests">
                            <i class="fa-light fa-users"></i> All Guests
                        </a>
                        <a href="{{ route('burial_council.index') }}" class="search-item" data-title="Burial Council">
                            <i class="fa-light fa-people-carry-box"></i> Burial Council
                        </a>
                        <a href="{{ route('about_us.edit') }}" class="search-item" data-title="About Us">
                            <i class="fa-light fa-circle-info"></i> About Us
                        </a>
                        <a href="{{ route('hmbc.index') }}" class="search-item" data-title="HMBC">
                            <i class="fa-light fa-mosque"></i> HMBC
                        </a>
                        <a href="{{ route('faq_update.edit') }}" class="search-item" data-title="FAQ Update">
                            <i class="fa-light fa-pen-to-square"></i> FAQ Update
                        </a>
                        <a href="{{ route('five_pillars.index') }}" class="search-item" data-title="Five Pillars">
                            <i class="fa-light fa-hand-holding-heart"></i> Five Pillars
                        </a>
                        <a href="{{ route('home_update.edit') }}" class="search-item" data-title="Home Update">
                            <i class="fa-light fa-house"></i> Home Update
                        </a>
                        <a href="{{ route('new_member.edit') }}" class="search-item" data-title="New Member">
                            <i class="fa-light fa-user-plus"></i> New Member
                        </a>
                        <a href="{{ route('payment_info.edit') }}" class="search-item" data-title="Payment Info">
                            <i class="fa-light fa-credit-card"></i> Payment Info
                        </a>
                        <a href="{{ route('payment_status.edit') }}" class="search-item" data-title="Payment Status">
                            <i class="fa-light fa-badge-dollar"></i> Payment Status
                        </a>
                        <a href="{{ route('contact_update.edit') }}" class="search-item" data-title="Contact Update">
                            <i class="fa-light fa-address-book"></i> Contact Update
                        </a>
                        <a href="{{ route('rules_regulation_update.edit') }}" class="search-item" data-title="Rules & Regulation Update">
                            <i class="fa-light fa-gavel"></i> Rules & Regulation Update
                        </a>
                    @endif
                    @if(auth()->check() && auth()->user()->type === 'member')
                        <a href="{{ route('member.my_transactions') }}" class="search-item" data-title="My Transactions">
                            <i class="fa-light fa-money-check-dollar"></i> My Transactions
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
    <div class="header-right">
        {{-- <div class="notification">
            <i class="fa-light fa-bell"></i>
            <div class="notification-badge">3</div>
            <div class="notification-dropdown">
                <div class="notification-header">
                    03 Notifiacations
                </div>
                <div class="notification-body">
                    <div class="notification-item">
                        <div class="notification-icon success">
                            <i class="fa-light fa-check"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">Complete Today Task</div>
                            <div class="notification-time">1 Mins ago</div>
                        </div>
                    </div>
                    <div class="notification-item">
                        <div class="notification-icon warning">
                            <i class="fa-light fa-file"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">Director Metting</div>
                            <div class="notification-time">20 Mins ago</div>
                        </div>
                    </div>
                    <div class="notification-item">
                        <div class="notification-icon info">
                            <i class="fa-light fa-gear"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">Update Password</div>
                            <div class="notification-time">45 Mins ago</div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="user-profile" id="user-profile" style="position: relative;">
            <div class="profile-trigger" id="profile-trigger" style="cursor:pointer;">
                <img src="{{ Auth::user()->profile_image ? asset(Auth::user()->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                    alt="User" width="32" height="32">
                <i class="fa-light fa-chevron-down"></i>
            </div>
            <div class="profile-dropdown" id="profile-dropdown">
                <a href="{{ route('profile') }}" class="dropdown-item">
                    <i class="fa-light fa-user"></i>
                    <span>Profile</span>
                </a>
                @if(auth()->check() && auth()->user()->type === 'super-admin')
                    <a href="{{ route('websettings.index') }}" class="dropdown-item">
                        <i class="fa-light fa-gear"></i>
                        <span>Web Settings</span>
                    </a>
                @endif
                <a href="#" class="dropdown-item"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa-light fa-right-from-bracket"></i>
                    <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</header>
<style>
    .search-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 8px 12px;
        background: #fff;
    }

    .search-bar input {
        border: none;
        outline: none;
        flex: 1;
    }

    .search-shortcuts {
        background: #f0f0f0;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 12px;
        font-family: monospace;
        display: flex;
        gap: 2px;
    }

    .search-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        max-height: 300px;
        z-index: 1000;
        backdrop-filter: blur(10px);
        background-color: rgba(255, 255, 255, 0.7);
        border: 1px solid #ccc;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .search-dropdown-inner {
        max-height: 300px;
        overflow-y: auto;
    }

    .search-item {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        gap: 12px;
        text-decoration: none;
        color: #222;
        font-weight: 500;
        font-size: 15px;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .search-item:hover {
        background-color: rgba(240, 240, 240, 0.8);
        transform: scale(1.02);
        color: #000;
    }

    .profile-dropdown {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 6px;
        min-width: 200px;
        z-index: 1000;
    }

    .user-profile.active .profile-dropdown {
        display: block;
    }

    .dropdown-item {
        padding: 10px 16px;
        display: flex;
        gap: 10px;
        color: #333;
        text-decoration: none;
    }

    .dropdown-item:hover {
        background: #f4f4f4;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- Profile Dropdown Fix ---
        const userProfile = document.getElementById('user-profile');
        const profileTrigger = document.getElementById('profile-trigger');
        const profileDropdown = document.getElementById('profile-dropdown');

        // --- Notification Dropdown ---
        const notification = document.querySelector('.notification');
        const notificationIcon = notification ? notification.querySelector('i.fa-bell') : null;
        const notificationDropdown = notification ? notification.querySelector('.notification-dropdown') : null;

        // --- Search Dropdown ---
        const searchInput = document.getElementById('search-input');
        const dropdown = document.getElementById('search-dropdown');
        const searchItems = dropdown ? dropdown.querySelectorAll('.search-item') : [];

        // Helper to close all dropdowns
        function closeAllDropdowns(e) {
            // Profile dropdown
            if (userProfile && !userProfile.contains(e.target)) {
                userProfile.classList.remove('active');
            }
            // Notification dropdown
            if (notification && !notification.contains(e.target)) {
                notification.classList.remove('active');
            }
            // Search dropdown
            if (dropdown && !dropdown.contains(e.target) && (!searchInput || !searchInput.contains(e.target))) {
                dropdown.style.display = 'none';
            }
        }

        // Toggle profile dropdown
        if (profileTrigger && userProfile) {
            profileTrigger.addEventListener('click', function (e) {
                e.stopPropagation();
                // Close other dropdowns
                if (notification) notification.classList.remove('active');
                if (dropdown) dropdown.style.display = 'none';
                // Toggle profile
                userProfile.classList.toggle('active');
            });
        }

        // Prevent dropdown from closing when clicking inside profile dropdown
        if (profileDropdown) {
            profileDropdown.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        }
        // Prevent dropdown from closing when clicking inside userProfile (covers all children)
        if (userProfile) {
            userProfile.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        }

        // Close on outside click
        document.addEventListener('click', closeAllDropdowns);

        // Close on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === "Escape") {
                if (userProfile) userProfile.classList.remove('active');
                if (notification) notification.classList.remove('active');
                if (dropdown) dropdown.style.display = 'none';
            }
        });

        // --- Notification Dropdown ---
        if (notificationIcon && notificationDropdown) {
            notificationIcon.addEventListener('click', function (e) {
                e.stopPropagation();
                // Close other dropdowns
                if (userProfile) userProfile.classList.remove('active');
                if (dropdown) dropdown.style.display = 'none';
                notification.classList.toggle('active');
            });

            notificationDropdown.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        }

        // --- Search Dropdown ---
        document.addEventListener('keydown', function (event) {
            if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
                event.preventDefault();
                if (dropdown) dropdown.style.display = 'block';
                if (searchInput) searchInput.focus();
            }
        });

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const value = this.value.toLowerCase().trim();
                let hasMatch = false;

                searchItems.forEach(item => {
                    const title = item.dataset.title ? item.dataset.title.toLowerCase() : '';
                    if (title.includes(value)) {
                        item.style.display = 'flex';
                        hasMatch = true;
                    } else {
                        item.style.display = 'none';
                    }
                });

                if (dropdown) dropdown.style.display = hasMatch ? 'block' : 'none';
            });

            searchInput.addEventListener('click', () => {
                if (dropdown) dropdown.style.display = 'block';
            });
        }
    });

    // Search shortcut (for focus)
    document.addEventListener("keydown", function (e) {
        if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            const input = document.querySelector('.search-bar input');
            if (input) input.focus();
        }
    });
</script>
