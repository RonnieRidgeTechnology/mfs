@extends('layouts.admin')
<style>
    .modern-stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 28px;
        margin-bottom: 32px;
        margin-top: 10px;
    }

    @media (max-width: 900px) {
        .modern-stats-grid {
            grid-template-columns: 1fr;
            gap: 18px;
        }
    }

    .modern-stat-card {
        border-radius: 14px;
        box-shadow: 0 2px 16px rgba(30, 41, 59, 0.08), 0 1.5px 4px rgba(0, 0, 0, 0.04);
        display: flex;
        align-items: center;
        padding: 18px 22px;
        min-height: 72px;
        transition: box-shadow 0.22s cubic-bezier(.4, 0, .2, 1), transform 0.22s cubic-bezier(.4, 0, .2, 1), background 0.22s cubic-bezier(.4, 0, .2, 1);
        cursor: pointer;
        border: 1px solid #e3e8ee;
        position: relative;
        overflow: hidden;
        background: #fff;
    }

    .modern-stat-card:active {
        transform: scale(0.98);
    }

    /* Card color themes */
    .modern-stat-card.admin {
        background: linear-gradient(90deg, #e3f0ff 0%, #f0f6ff 100%);
        border-color: #b6d4fe;
    }

    .modern-stat-card.members {
        background: linear-gradient(90deg, #e7fbe9 0%, #f0fff4 100%);
        border-color: #a7f3d0;
    }

    .modern-stat-card.transactions {
        background: linear-gradient(90deg, #fffbe7 0%, #fff9e6 100%);
        border-color: #fde68a;
    }

    .modern-stat-card.activity {
        background: linear-gradient(90deg, #f3e8ff 0%, #f8f0ff 100%);
        border-color: #d8b4fe;
    }

    .modern-stat-card.guests {
        background: linear-gradient(90deg, #e0f2fe 0%, #f0f9ff 100%);
        border-color: #7dd3fc;
    }

    .modern-stat-card.flagged {
        background: linear-gradient(90deg, #ffe4e6 0%, #fff1f2 100%);
        border-color: #fca5a5;
    }

    .modern-stat-card:hover {
        box-shadow: 0 6px 32px rgba(37, 99, 235, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
        transform: translateY(-2px) scale(1.025);
        filter: brightness(1.04);
    }

    .modern-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.55rem;
        margin-right: 18px;
        color: #fff;
        transition: background 0.22s;
    }

    /* Icon color themes */
    .modern-stat-icon.admin {
        background: linear-gradient(135deg, #2563eb 0%, #0052cc 100%);
    }

    .modern-stat-card:hover .modern-stat-icon.admin {
        background: linear-gradient(135deg, #0052cc 0%, #2563eb 100%);
    }

    .modern-stat-icon.members {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    }

    .modern-stat-card:hover .modern-stat-icon.members {
        background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
    }

    .modern-stat-icon.transactions {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e42 100%);
    }

    .modern-stat-card:hover .modern-stat-icon.transactions {
        background: linear-gradient(135deg, #f59e42 0%, #fbbf24 100%);
    }

    .modern-stat-icon.activity {
        background: linear-gradient(135deg, #a21caf 0%, #9333ea 100%);
    }

    .modern-stat-card:hover .modern-stat-icon.activity {
        background: linear-gradient(135deg, #9333ea 0%, #a21caf 100%);
    }

    .modern-stat-icon.guests {
        background: linear-gradient(135deg, #38bdf8 0%, #2563eb 100%);
    }

    .modern-stat-card:hover .modern-stat-icon.guests {
        background: linear-gradient(135deg, #2563eb 0%, #38bdf8 100%);
    }

    .modern-stat-icon.flagged {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    }

    .modern-stat-card:hover .modern-stat-icon.flagged {
        background: linear-gradient(135deg, #c82333 0%, #dc3545 100%);
    }

    .modern-stat-info h4 {
        margin: 0 0 2px 0;
        font-size: 1.22rem;
        font-weight: 700;
        color: #1e293b;
        letter-spacing: 0.5px;
    }

    .modern-stat-info p {
        margin: 0;
        font-size: 0.98rem;
        color: #64748b;
        font-weight: 500;
        letter-spacing: 0.2px;
    }

    /* Skeleton Loader Styles */
    .skeleton-loader {
        display: none;
        background: transparent;
        border-radius: 0;
        padding: 0;
    }

    .skeleton-loader.show {
        display: block;
    }

    .skeleton-line {
        height: 12px;
        background: #e9ecef;
        border-radius: 4px;
        margin-bottom: 8px;
    }

    .skeleton-line.short {
        width: 60%;
    }

    .skeleton-line.medium {
        width: 80%;
    }

    .skeleton-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        margin-right: 15px;
    }

    .skeleton-badge {
        width: 80px;
        height: 24px;
        background: #e9ecef;
        border-radius: 12px;
    }

    /* Filter Container Styles */
    .filter-container {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
    }

    .filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .filter-title {
        margin: 0;
        color: #1e293b;
        font-size: 1.1em;
        font-weight: 600;
    }

    .filter-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .reset-btn {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        border: none;
        border-radius: 6px;
        padding: 6px 16px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .reset-btn:hover {
        background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
        transform: translateY(-1px);
    }
</style>
<style>
    #activityLogChart {
        max-height: 220px !important;
        height: 220px !important;
    }

    .table-section canvas {
        max-height: 200px !important;
        height: 200px !important;
    }

    .table-section {
        padding: 12px 12px 12px 12px !important;
    }

    .table-header {
        margin-bottom: 10px !important;
    }
</style>
@section('content')
    <!-- Main Content -->
    <main class="main-content">
        @include('layouts.header')
        <div class="content">
            <div class="modern-stats-grid">
                @if(auth()->check() && auth()->user()->type === 'super-admin')
                    <a href="{{ route('admin.list') }}" style="text-decoration: none; color: inherit;">
                        <div class="modern-stat-card admin">
                            <div class="modern-stat-icon admin">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div class="modern-stat-info">
                                <h4>{{ $totalAdmins }}</h4>
                                <p>Total Admins</p>
                            </div>
                        </div>
                    </a>
                @endif

                @if(auth()->check() && (auth()->user()->type === 'admin' || auth()->user()->type === 'super-admin'))
                    <a href="{{ route('member.list') }}" style="text-decoration: none; color: inherit;">
                        <div class="modern-stat-card members">
                            <div class="modern-stat-icon members">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="modern-stat-info">
                                <h4>{{ $totalMembers }}</h4>
                                <p>Total Members</p>
                            </div>
                        </div>
                    </a>
                @endif
                @if(auth()->check() && auth()->user()->type === 'super-admin')
                    <a href="{{ route('guest.list') }}" style="text-decoration: none; color: inherit;">
                        <div class="modern-stat-card guests">
                            <div class="modern-stat-icon guests">
                                <i class="fas fa-user-clock"></i>
                            </div>
                            <div class="modern-stat-info">
                                <h4>{{ $totalGuests }}</h4>
                                <p>Total Guests</p>
                            </div>
                        </div>
                    </a>
                @endif

                @if(auth()->check() && auth()->user()->type === 'super-admin')
                    <a href="{{ route('transactions.list') }}" style="text-decoration: none; color: inherit;">
                        <div class="modern-stat-card transactions">
                            <div class="modern-stat-icon transactions">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                            <div class="modern-stat-info">
                                <h4>{{ $totalTransactions }}</h4>
                                <p>Total Transactions</p>
                            </div>
                        </div>
                    </a>
                @endif

                @if(auth()->check() && auth()->user()->type === 'super-admin')
                    <a href="{{ route('admin.transactions.flagged.view') }}" style="text-decoration: none; color: inherit;">
                        <div class="modern-stat-card flagged">
                            <div class="modern-stat-icon flagged">
                                <i class="fas fa-flag"></i>
                            </div>
                            <div class="modern-stat-info">
                                <h4>{{ $totalFlaggedTransactions }}</h4>
                                <p>Flagged Transactions</p>
                            </div>
                        </div>
                    </a>
                @endif
                @if(auth()->check() && auth()->user()->type === 'super-admin')
                    <a href="#" style="text-decoration: none; color: inherit;">
                        <div class="modern-stat-card activity">
                            <div class="modern-stat-icon activity">
                                <i class="fa-light fa-chart-line"></i>
                            </div>
                            <div class="modern-stat-info">
                                <h4>{{ $totalActivityLogs }}</h4>
                                <p>Total ActivityLogs</p>
                            </div>
                        </div>
                    </a>
                @endif
                @if(auth()->check() && auth()->user()->type === 'member')
                    <a href="{{ route('member.my_transactions') }}" style="text-decoration: none; color: inherit;">
                        <div class="modern-stat-card transactions">
                            <div class="modern-stat-icon transactions">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                            <div class="modern-stat-info">
                                <h4>{{ $myTotalTransactions }}</h4>
                                <p>Transactions</p>
                            </div>
                        </div>
                    </a>
                @endif
                @if(auth()->check() && auth()->user()->type === 'member')
                                    <a href="{{ route('member.my_transactions') }}?flag_status=0"
                                        style="text-decoration: none; color: inherit;">
                                        <div class="modern-stat-card flagged">
                                            <div class="modern-stat-icon flagged">
                                                <i class="fas fa-flag"></i>
                                            </div>
                                            <div class="modern-stat-info">
                                                <h4>
                                                    {{ \App\Models\Transaction::where('user_id', auth()->id())->where('flag_status', 0)->count() }}
                                                </h4>
                                                <p>Flagged</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" style="text-decoration: none; color: inherit;">
                                        <div class="modern-stat-card activity">
                                            <div class="modern-stat-icon activity">
                                                <i class="fa-light fa-user-clock"></i>
                                            </div>
                                            <div class="modern-stat-info">
                                                <h4>
                                                    {{
                    \App\Models\ActivityLog::where('user_id', auth()->id())->count()
                                                            }}
                                                </h4>
                                                <p>Total Activity</p>
                                            </div>
                                        </div>
                                    </a>
                @endif

            </div>
            @if(auth()->check() && auth()->user()->type === 'super-admin')
                <!-- Table Section with Skeleton Loader only on Table -->
                <div class="table-section">
                    <div class="table-header">
                        <h2>Recent Activities</h2>
                        <div style="display: flex;align-items: center; gap: 20px;">
                            @if(auth()->user()->type === 'super-admin')
                                <div class="table-actions">

                                </div>
                            @endif
                            @if(auth()->user()->type === 'admin')
                                <span
                                    style="background: #007bff; color: #fff; border-radius: 12px; padding: 4px 12px; font-size: 13px; vertical-align: middle; display: inline-block;">
                                    Total : {{ $totalActivityLogs }}
                                </span>
                            @else
                                <span
                                    style="background: #007bff; color: #fff; border-radius: 12px; padding: 4px 12px; font-size: 13px; vertical-align: middle; display: inline-block;">
                                    Total: {{ $totalActivityLogs }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="filter-container" style="margin-bottom: 24px;">
                        <div class="filter-header">
                            <h4 style="font-weight: 700; color: #6366f1; margin-bottom: 4px;">
                                <i class="fa-solid fa-chart-line" style="margin-right: 6px; color: #6366f1;"></i>
                                Activity Chart
                            </h4>
                            <p style="color: #475569; font-size: 0.85em; margin-bottom: 7px;">
                                Visual representation of recent activities.
                            </p>
                        </div>
                        <div style="padding: 16px 0;">
                            <canvas id="activityChart"></canvas>
                        </div>
                    </div>
                    <!-- Filter Container -->
                    <div class="filter-container">
                        <div class="filter-header">
                            <div style="margin: 18px 0 8px 0;">

                                <h4 style="font-weight: 700; color: #6366f1; margin-bottom: 4px;">
                                    <i class="fa-solid fa-sliders" style="margin-right: 6px; color: #6366f1;"></i>
                                    Advanced Filter
                                </h4>
                                <p style="color: #475569; font-size: 0.85em; margin-bottom: 7px;">
                                    Use the advanced filters below to efficiently find activity records by type or date
                                    range.
                                </p>
                            </div>
                            <div class="filter-actions">
                                <button type="button" onclick="resetFilters()" class="reset-btn">
                                    <i class="fa-light fa-rotate-left"></i>
                                    <span>Reset All</span>
                                </button>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                            <form method="GET" action="{{ route('dashboard') }}" class="custom-dropdown"
                                style="display:inline;">
                                @if(request('period'))
                                    <input type="hidden" name="period" value="{{ request('period') }}">
                                @endif
                                @if(request('per_page'))
                                    <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                                @endif
                                <select name="type" onchange="this.form.submit()" class="dropdown-trigger"
                                    style="font-size: 14px; padding: 6px;">
                                    <option value="" {{ request('type') === null ? 'selected' : '' }}>All Types</option>
                                    <option value="admin" {{ request('type') === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="member" {{ request('type') === 'member' ? 'selected' : '' }}>Member
                                    </option>
                                </select>
                            </form>
                            <form method="GET" action="{{ route('dashboard') }}" class="filter-form"
                                style="display: flex; align-items: center; gap: 15px;">
                                <!-- Preserve existing type filter -->
                                @if(request('type'))
                                    <input type="hidden" name="type" value="{{ request('type') }}">
                                @endif
                                @if(request('per_page'))
                                    <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                                @endif

                                <div class="custom-dropdown">
                                    <select name="period" onchange="this.form.submit()" class="dropdown-trigger"
                                        style="font-size: 14px; padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px; background: #f8fafc; color: #495057;">
                                        <option value="" {{ request('period') === null ? 'selected' : '' }}>All Time</option>
                                        <option value="today" {{ request('period') === 'today' ? 'selected' : '' }}>Today
                                        </option>
                                        <option value="week" {{ request('period') === 'week' ? 'selected' : '' }}>This Week
                                        </option>
                                        <option value="month" {{ request('period') === 'month' ? 'selected' : '' }}>This Month
                                        </option>
                                        <option value="year" {{ request('period') === 'year' ? 'selected' : '' }}>This Year
                                        </option>
                                        <option value="last_week" {{ request('period') === 'last_week' ? 'selected' : '' }}>
                                            Last Week</option>
                                        <option value="last_month" {{ request('period') === 'last_month' ? 'selected' : '' }}>
                                            Last Month</option>
                                        <option value="last_year" {{ request('period') === 'last_year' ? 'selected' : '' }}>
                                            Last Year</option>
                                    </select>
                                </div>

                                <button type="submit" class="action-button primary"
                                    style="padding: 8px 18px; border-radius: 6px; background: var(--primary); color: #fff; border: none; font-size:14px; display: flex; align-items: center; gap: 6px;display:none;">
                                    <i class="fa-light fa-magnifying-glass"></i>
                                    <span>Apply Filter</span>
                                </button>
                            </form>

                        </div>
                    </div>

                    <div class="table-container" style="position: relative;">
                        <!-- Skeleton Loader for Table Only -->
                        <div id="skeleton-table" class="skeleton-loader"
                            style="position: absolute; top: 0; left: 0; width: 100%; z-index: 2; background: #f8f9fa; border-radius: 8px; padding: 20px;">
                            @for($i = 0; $i < 10; $i++)
                                <div style="display: flex; align-items: center; padding: 15px; border-bottom: 1px solid #e9ecef;">
                                    <div style="width: 40px;">
                                        <div class="skeleton-line short"></div>
                                    </div>
                                    <div class="skeleton-avatar"></div>
                                    <div style="flex: 1;">
                                        <div class="skeleton-line short"></div>
                                        <div class="skeleton-line medium"></div>
                                    </div>
                                    <div class="skeleton-badge"></div>
                                    <div style="width: 200px;">
                                        <div class="skeleton-line short"></div>
                                    </div>
                                    <div style="width: 100px;">
                                        <div class="skeleton-line short"></div>
                                    </div>
                                    <div style="width: 100px;">
                                        <div class="skeleton-line short"></div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                        <!-- Real Table Content -->
                        <div id="real-table" style="">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>Activity</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($activityLogs->isEmpty())
                                        <tr>
                                            <td colspan="6" style="text-align:center; padding: 40px 0;">
                                                <div
                                                    style="display: flex; flex-direction: column; align-items: center; gap: 10px; color: #888;">
                                                    <i class="fa-light fa-circle-exclamation"
                                                        style="font-size: 2em; color: #ff9800; margin-bottom: 8px;"></i>
                                                    <div style="font-size: 1.1rem; font-weight: 500;">
                                                        No activity logs found.
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @else
                                        @foreach($activityLogs as $index => $log)
                                            @php
                                                // For admin, only show logs where user type is 'member'
                                                $showLog = true;
                                                if (auth()->user()->type === 'admin') {
                                                    $showLog = $log->user && $log->user->type === 'member';
                                                }
                                            @endphp
                                            @if($showLog)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        <div class="employee-info">
                                                            @php
                                                                $user = $log->user;
                                                                $userName = $user ? $user->name : 'System';
                                                                $userEmail = $user ? $user->email : '';
                                                                $avatarUrl = $user
                                                                    ? ($user->profile_image
                                                                        ? asset($user->profile_image)
                                                                        : 'https://ui-avatars.com/api/?name=' . urlencode($userName)
                                                                    )
                                                                    : 'https://ui-avatars.com/api/?name=System';
                                                            @endphp
                                                            <img src="{{ $avatarUrl }}" alt="{{ $userName }}">
                                                            <div>
                                                                <h4>{{ $userName }}</h4>
                                                                @if($userEmail)
                                                                    <span>{{ $userEmail }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="status-badge">
                                                            {{ $log->role ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        {{ $log->activity }}
                                                    </td>
                                                    <td>
                                                        <div class="activity-info">
                                                            <i class="fa-light fa-calendar"></i>
                                                            <span title="{{ $log->created_at->format('Y-m-d H:i:s') }}">
                                                                {{ $log->created_at->format('Y-m-d') }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="activity-info">
                                                            <i class="fa-light fa-clock"></i>
                                                            <span title="{{ $log->created_at->format('Y-m-d H:i:s') }}">
                                                                {{ $log->created_at->format('H:i:s') }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        {{-- If admin and no member logs were shown, show empty state --}}
                                        @if(
                                                auth()->user()->type === 'admin' && $activityLogs->filter(function ($log) {
                                                    return $log->user && $log->user->type === 'member';
                                                })->count() === 0
                                            )
                                                <tr>
                                                    <td colspan="6" style="text-align:center; padding: 40px 0;">
                                                        <div
                                                            style="display: flex; flex-direction: column; align-items: center; gap: 10px; color: #888;">
                                                            <i class="fa-light fa-circle-exclamation"
                                                                style="font-size: 2em; color: #ff9800; margin-bottom: 8px;"></i>
                                                            <div style="font-size: 1.1rem; font-weight: 500;">
                                                                No activity logs found.
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                        @endif
                                    @endif
                                </tbody>
                            </table>
                            <div id="pagination-container">
                                @include('layouts.custom_pagination', ['paginator' => $activityLogs])
                            </div>
                        </div>
                    </div>
                </div>
            @endif
         @if(auth()->check() && auth()->user()->type === 'member')
            @php
                $user = \Auth::user();
                $currentYear = now()->year;
                $startYear = 2015;
                $selectedYear = request('membership_fee_year', $currentYear);

                $annualFeeSetting = \App\Models\MembershipFeeSetting::where('member_type', 'annual_fee')
                    ->where('year', $selectedYear)
                    ->first();

                $annualFee = $annualFeeSetting ? $annualFeeSetting->amount : 0;
                $paid = \App\Models\Transaction::where('user_id', $user->id)
                    ->whereYear('date', $selectedYear)
                    ->sum('amount');
                $percent = $annualFee > 0 ? min(100, round(($paid / $annualFee) * 100)) : 0;
                $isComplete = $annualFee > 0 && $paid >= $annualFee;
            @endphp

            <div class="dashboard-card" style="background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 100%); border-radius: 16px; box-shadow: 0 4px 20px rgba(34,197,94,0.08); border: 1px solid #bbf7d0; padding: 2rem 2.5rem; margin-bottom: 2.5rem; max-width: 100%; margin-left: auto; margin-right: auto;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                    <h3 style="font-weight: 700; color: #22c55e; margin: 0; font-size: 1.35rem; display: flex; align-items: center;">
                        <i class="fa-solid fa-wallet" style="margin-right: 8px; color: #22c55e;"></i>
                         Fee Status
                    </h3>
                    <form method="GET" action="" id="membershipFeeYearForm" style="margin: 0;">
                        <select name="membership_fee_year" id="membershipFeeYearSelect" class="custom-select-input" style="padding: 6px 14px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; color: #334155; font-weight: 500; font-size: 1em;">
                            @for($y = $startYear; $y <= $currentYear; $y++)
                                <option value="{{ $y }}" @if($selectedYear == $y) selected @endif>{{ $y }}</option>
                            @endfor
                        </select>
                    </form>
                 </div>
                 <div style="display: flex; align-items: center; gap: 2.5rem;">
                    <div style="position: relative; width: 120px; height: 120px;">
                        <svg width="120" height="120">
                            <circle
                                cx="60"
                                cy="60"
                                r="54"
                                stroke="#e0e7ef"
                                stroke-width="12"
                                fill="none"
                            />
                            <circle
                                id="progressCircle"
                                cx="60"
                                cy="60"
                                r="54"
                                stroke="{{ $isComplete ? '#22b573' : '#2563eb' }}"
                                stroke-width="12"
                                fill="none"
                                stroke-dasharray="{{ 2 * pi() * 54 }}"
                                stroke-dashoffset="{{ 2 * pi() * 54 }}"
                                stroke-linecap="round"
                                style="transition: stroke 0.8s, stroke-dashoffset 0.8s;"
                            />
                        </svg>
                        <div style="position: absolute; top: 0; left: 0; width: 120px; height: 120px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                            <span id="progressPercentText" style="font-size: 1.5rem; font-weight: 700; color: {{ $isComplete ? '#22b573' : '#2563eb' }};">
                                {{ $percent }}%
                            </span>
                            <span style="font-size: 1rem; color: #2563eb;">Paid</span>
                        </div>
                    </div>
                    <div>
                        <div style="font-size: 1.13rem; margin-bottom: 0.5rem; color: #1e293b;">
                            <strong>Annual Fee:</strong>
                            <span style="color: #2563eb;">{{ number_format($annualFee, 2) }}</span>
                        </div>
                        <div style="font-size: 1.13rem; margin-bottom: 0.5rem; color: #1e293b;">
                            <strong>Paid:</strong>
                            <span style="color: #22b573;">{{ number_format($paid, 2) }}</span>
                        </div>
                        <div style="font-size: 1.13rem; color: #1e293b;">
                            <strong>Status:</strong>
                            @if($isComplete)
                                <span style="color: #22b573; font-weight: 600;">
                                    Complete <i class="fa fa-check-circle"></i>
                                </span>
                            @else
                                <span style="color: #2563eb; font-weight: 600;">
                                    Incomplete <i class="fa fa-exclamation-circle"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        // Animate the progress circle on reload
                        const circle = document.getElementById('progressCircle');
                        const percent = {{ $percent }};
                        const radius = 54;
                        const circumference = 2 * Math.PI * radius;
                        const targetOffset = circumference * (1 - percent / 100);

                        // Reset to 0 progress (full circle hidden)
                        circle.style.strokeDashoffset = circumference;

                        setTimeout(function () {
                            circle.style.strokeDashoffset = targetOffset;
                        }, 150); // slight delay for smooth animation
                    });
                </script>
            </div>
        @endif
        </div>
    </main>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Month filter UI
        document.addEventListener('DOMContentLoaded', function () {
            // Insert month filter above the chart
            const chartCard = document.getElementById('activityChart').closest('.table-section') || document.getElementById('activityChart').parentElement;
            if (chartCard && !document.getElementById('month-filter-container')) {
                const filterDiv = document.createElement('div');
                filterDiv.id = 'month-filter-container';
                filterDiv.style.display = 'flex';
                filterDiv.style.justifyContent = 'flex-end';
                filterDiv.style.alignItems = 'center';
                filterDiv.style.marginBottom = '10px';

                filterDiv.innerHTML = `
                                            <label for="month-filter" style="margin-right: 8px; font-weight: 500;">Filter by Month:</label>
                                            <input type="month" id="month-filter" name="month-filter" style="padding: 4px 8px; border-radius: 4px; border: 1px solid #ccc;">
                                        `;

                chartCard.insertBefore(filterDiv, chartCard.firstChild);
            }

            // Set chart canvas height to 300px
            const chartCanvas = document.getElementById('activityChart');
            if (chartCanvas) {
                chartCanvas.height = 300;
                chartCanvas.style.height = '300px';
            }
        });

        // Store all chartLogs for all months (passed from backend)
        const allChartLogs = @json($chartLogsByMonth ?? [$currentMonth => $chartLogs]);
        // Fallback for old code: if $chartLogsByMonth is not set, fallback to $chartLogs for current month
        const currentMonth = "{{ \Carbon\Carbon::now()->format('Y-m') }}";
        let selectedMonth = currentMonth;

        // Helper to get chartLogs for a given month
        function getChartLogsForMonth(month) {
            if (allChartLogs[month]) {
                return allChartLogs[month];
            }
            // fallback: empty 24 hours
            return Array.from({ length: 24 }, (_, i) => {
                const hourStr = String(i).padStart(2, '0') + ':00';
                return {
                    hour: hourStr,
                    login_count: 0,
                    logout_count: 0,
                    created_count: 0,
                    updated_count: 0,
                    deleted_count: 0,
                    login_users: [],
                    logout_users: [],
                    created_users: [],
                    updated_users: [],
                    deleted_users: []
                };
            });
        }

        // Chart.js instance
        let activityChartInstance = null;

        // Smooth color scheme for the chart
        const smoothColors = {
            login: { border: 'rgba(56, 189, 248, 1)', bg: 'rgba(56, 189, 248, 0.15)' }, // sky-400
            logout: { border: 'rgba(251, 191, 36, 1)', bg: 'rgba(251, 191, 36, 0.15)' }, // amber-400
            created: { border: 'rgba(34, 197, 94, 1)', bg: 'rgba(34, 197, 94, 0.15)' },  // green-500
            updated: { border: 'rgba(168, 85, 247, 1)', bg: 'rgba(168, 85, 247, 0.15)' }, // purple-500
            deleted: { border: 'rgba(239, 68, 68, 1)', bg: 'rgba(239, 68, 68, 0.15)' },  // red-500
        };

        function renderActivityChart(chartLogs) {
            const hours = chartLogs.map(item => item.hour);

            const datasets = [
                {
                    label: 'Login',
                    data: chartLogs.map(item => item.login_count),
                    borderColor: smoothColors.login.border,
                    backgroundColor: smoothColors.login.bg,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,
                    pointBackgroundColor: smoothColors.login.border,
                    pointHoverRadius: 5,
                },
                {
                    label: 'Logout',
                    data: chartLogs.map(item => item.logout_count),
                    borderColor: smoothColors.logout.border,
                    backgroundColor: smoothColors.logout.bg,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,
                    pointBackgroundColor: smoothColors.logout.border,
                    pointHoverRadius: 5,
                },
                {
                    label: 'Created',
                    data: chartLogs.map(item => item.created_count),
                    borderColor: smoothColors.created.border,
                    backgroundColor: smoothColors.created.bg,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,
                    pointBackgroundColor: smoothColors.created.border,
                    pointHoverRadius: 5,
                },
                {
                    label: 'Updated',
                    data: chartLogs.map(item => item.updated_count),
                    borderColor: smoothColors.updated.border,
                    backgroundColor: smoothColors.updated.bg,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,
                    pointBackgroundColor: smoothColors.updated.border,
                    pointHoverRadius: 5,
                },
                {
                    label: 'Deleted',
                    data: chartLogs.map(item => item.deleted_count),
                    borderColor: smoothColors.deleted.border,
                    backgroundColor: smoothColors.deleted.bg,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,
                    pointBackgroundColor: smoothColors.deleted.border,
                    pointHoverRadius: 5,
                }
            ];

            const ctx = document.getElementById('activityChart').getContext('2d');

            if (activityChartInstance) {
                activityChartInstance.destroy();
            }

            activityChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: hours,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Allow fixed height
                    interaction: {
                        mode: 'nearest',
                        intersect: false
                    },
                    plugins: {
                        tooltip: {
                            backgroundColor: 'rgba(30,41,59,0.95)',
                            titleColor: '#fff',
                            bodyColor: '#e0e7ef',
                            borderColor: '#64748b',
                            borderWidth: 1,
                            callbacks: {
                                afterBody: function (tooltipItems) {
                                    const hourIndex = tooltipItems[0].dataIndex;
                                    const action = tooltipItems[0].dataset.label.toLowerCase();
                                    const users = chartLogs[hourIndex][`${action}_users`] || [];

                                    if (users.length > 0) {
                                        return 'Users: ' + users.join(', ');
                                    } else {
                                        return 'No users';
                                    }
                                }
                            }
                        },
                        legend: {
                            position: 'top',
                            labels: {
                                color: '#334155',
                                font: { size: 13, weight: 'bold' }
                            }
                        },

                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Hour of Day',
                                color: '#64748b',
                                font: { size: 13, weight: 'bold' }
                            },
                            ticks: {
                                color: '#64748b'
                            },
                            grid: {
                                color: 'rgba(203,213,225,0.2)'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Activity Count',
                                color: '#64748b',
                                font: { size: 13, weight: 'bold' }
                            },
                            ticks: {
                                color: '#64748b'
                            },
                            grid: {
                                color: 'rgba(203,213,225,0.2)'
                            }
                        }
                    }
                }
            });
        }

        // Initial render
        renderActivityChart(getChartLogsForMonth(currentMonth));

        // Listen for month filter changes
        document.addEventListener('DOMContentLoaded', function () {
            const monthInput = document.getElementById('month-filter');
            if (monthInput) {
                // Set default value to current month
                monthInput.value = currentMonth;
                monthInput.addEventListener('change', function () {
                    selectedMonth = this.value;
                    renderActivityChart(getChartLogsForMonth(selectedMonth));
                });
            }
        });
    </script>
     <script>
        document.addEventListener('DOMContentLoaded', function () {
            const yearSelect = document.getElementById('membershipFeeYearSelect');
            if (yearSelect) {
                yearSelect.addEventListener('change', function () {
                    document.getElementById('membershipFeeYearForm').submit();
                });
            }
        });
    </script>

    <script>
        // Reset Filters Logic
        function resetFilters() {
            // Redirect to clean URL without any query parameters
            window.location.href = "{{ route('dashboard') }}";
        }

        // Skeleton Loader Logic
        document.addEventListener('DOMContentLoaded', function () {
            // Show skeleton loader and hide real table initially (only for table)
            var skeleton = document.getElementById('skeleton-table');
            var realTable = document.getElementById('real-table');
            if (skeleton && realTable) {
                skeleton.classList.add('show');
                realTable.style.visibility = 'hidden';
                realTable.style.position = 'relative';
                // After 1.5 seconds, hide skeleton and show real table
                setTimeout(function () {
                    skeleton.classList.remove('show');
                    realTable.style.visibility = '';
                    realTable.style.position = '';
                }, 1500);
            }
        });
    </script>
@endsection
