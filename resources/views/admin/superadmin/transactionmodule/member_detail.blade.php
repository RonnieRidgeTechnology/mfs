@extends('layouts.admin')
<style>
    @keyframes stripes-move {
        0% {
            background-position: 0 0;
        }
        100% {
            background-position: 32px 0;
        }
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.6;
        }
    }

    .status-badge.active {
        background: linear-gradient(90deg, #22c55e 0%, #16a34a 100%);
        color: #fff;
        border-radius: 7px;
        padding: 0.25em 0.9em;
        font-size: 0.98em;
        font-weight: 600;
    }
    .status-badge.inactive {
        background: linear-gradient(90deg, #f16818 0%, #e67d4d 100%);
        color: #fff;
        border-radius: 7px;
        padding: 0.25em 0.9em;
        font-size: 0.98em;
        font-weight: 600;
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 8px rgba(99, 102, 241, 0.06);
    }
    .data-table th,
    .data-table td {
        padding: 0.85em 1.1em;
        text-align: left;
    }
    .data-table th {
        background: #f1f5f9;
        color: #334155;
        font-weight: 700;
        font-size: 1.01em;
    }
    .data-table tr:nth-child(even) {
        background: #f9fafb;
    }
    .data-table tr:hover {
        background: #e0e7ef;
    }
    @media (max-width: 600px) {
        .user-profile-card {
            padding: 1.2rem 0.7rem 1rem 0.7rem;
        }
        .data-table th,
        .data-table td {
            padding: 0.6em 0.5em;
            font-size: 0.97em;
        }
    }
    .progress-bar-container {
        margin-top: 0.1em;
    }
    .progress-bar-outer {
        background: #f1f5f9;
        border-radius: 8px;
        height: 13px;
        width: 100%;
        overflow: hidden;
    }
    .progress-bar-inner.progress-bar-blue {
        background: #6366f1;
        height: 100%;
        transition: width 0.4s;
    }
    .progress-bar-inner.progress-bar-green {
        background: #22c55e;
        height: 100%;
        transition: width 0.4s;
    }
    .table-header form input[type="date"]:focus,
    .table-header form select:focus {
        outline: 2px solid #6366f1;
        border-color: #6366f1;
        background: #eef2ff;
    }
    .table-header form button:hover {
        background: #4f46e5;
    }
    .table-header form a:hover {
        background: #e0e7ff;
        color: #3730a3;
    }

    /* Skeleton loader styles */
    .skeleton-cell {
        height: 20px;
        background: #f1f5f9;
        border-radius: 6px;
        margin: 8px 0;
        animation: pulse 1.8s infinite ease-in-out;
    }
</style>
@section('content')
    <main class="main-content">
        @include('layouts.header')
        <div class="content">
             <!-- User Profile Section -->
            <div class="user-profile-card"
                style="height: fit-content ; margin: 0 0px 2.5rem 0px; background: #fff; border-radius: 18px; box-shadow: 0 2px 16px rgba(99,102,241,0.08); padding: 2rem 2.2rem 1.5rem 2.2rem; display: flex; flex-direction: column; align-items: center;">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=120" alt="{{ $user->name }}"
                    style="width: 90px; height: 90px; border-radius: 50%; margin-bottom: 1.1rem; box-shadow: 0 1px 8px rgba(99,102,241,0.10);">
                <h2 style="font-size: 1.45rem; font-weight: 700; color: #1e293b; margin-bottom: 0.3rem;">{{ $user->name }}
                </h2>
                <div style="color: #64748b; font-size: 1.08rem; margin-bottom: 0.2rem;">
                    <i class="fa-light fa-envelope" style="margin-right: 7px;"></i> {{ $user->email }}
                </div>
                <div style="color: #64748b; font-size: 1.08rem; margin-bottom: 0.2rem;">
                    <i class="fa-light fa-id-card" style="margin-right: 7px;"></i> <span
                        style="font-weight: 500;">ID:</span> {{ $user->unique_id }}
                </div>
                <div style="color: #64748b; font-size: 1.08rem; margin-bottom: 0.2rem;">
                    <i class="fa-light fa-users" style="margin-right: 7px;"></i>
                    <span style="font-weight: 500;">Cover:</span>
                    @if(isset($user->cover) && $user->cover)
                        <span style="color: #2563eb; font-weight: 600; text-transform: capitalize;">
                            {{ $user->cover }}
                        </span>
                    @else
                        <span style="color: #ef4444; font-weight: 600;">
                            No
                        </span>
                    @endif
                </div>
                <div style="color: #64748b; font-size: 1.08rem; margin-bottom: 0.2rem;">
                    <i class="fa-light fa-user-shield" style="margin-right: 7px;"></i>
                    <span style="font-weight: 500;">Role:</span> {{ ucfirst($user->type ?? $user->role ?? 'N/A') }}
                </div>
                <div style="color: #64748b; font-size: 1.08rem;">
                    <i class="fa-light fa-phone" style="margin-right: 7px;"></i> {{ $user->phone }}
                 </div>

                   {{-- Member Status Badge (Modern Sleek Style) --}}
                  @php
                      $memberStatus = $user->member_status ?? null;
                      $badgeText = null;
                      $badgeBg = null;
                      $badgeColor = '#fff';
                      $badgeIcon = 'fa-id-badge';
                      if ($memberStatus === 'Blank Membership') {
                          $badgeText = 'Blank Membership';
                          $badgeBg = 'linear-gradient(90deg, #a78bfa 0%, #6366f1 100%)'; // purple gradient
                          $badgeColor = '#fff';
                          $badgeIcon = 'fa-circle-dot';
                      } elseif ($memberStatus === 'Member deceased - membership cancelled') {
                          $badgeText = 'Membership Cancelled';
                          $badgeBg = 'linear-gradient(90deg, #cbd5e1 0%, #64748b 100%)'; // slate/gray gradient
                          $badgeColor = '#334155';
                          $badgeIcon = 'fa-ban';
                      } elseif ($memberStatus === 'Member deceased - Family still on cover') {
                          $badgeText = 'Family Still Covered';
                          $badgeBg = 'linear-gradient(90deg, #38bdf8 0%, #0ea5e9 100%)'; // blue gradient
                          $badgeColor = '#fff';
                          $badgeIcon = 'fa-users';
                      }
                  @endphp
                  @if($badgeText)
                      <div style="margin-top: 0.9rem; margin-bottom: 0.2rem; display: flex; justify-content: center;">
                          <span style="
                              display: inline-flex;
                              align-items: center;
                              gap: 0.6em;
                              background: {{ $badgeBg }};
                              color: {{ $badgeColor }};
                              font-weight: 600;
                              font-size: 1.08em;
                              border-radius: 999px;
                              padding: 0.38em 1.4em;
                              box-shadow: 0 2px 12px rgba(99,102,241,0.10);
                              letter-spacing: 0.01em;
                              transition: box-shadow 0.2s;
                              border: none;
                              outline: none;
                              min-width: 0;
                              user-select: none;
                              cursor: default;
                              position: relative;
                              overflow: hidden;
                          ">
                              <i class="fa-solid {{ $badgeIcon }}" style="font-size: 1.1em; opacity: 0.85;"></i>
                              <span style="white-space: nowrap;">{{ $badgeText }}</span>
                          </span>
                      </div>
                  @endif

                  @if(($user->type ?? $user->role ?? null) === 'member')
                    @php
                        $currentYear = now()->year;
                        // Use the selected year from filter, fallback to current year if not set
                        $selectedYear = $year ?? $currentYear;

                        $annualFeeSetting = \App\Models\MembershipFeeSetting::where(function($query) use ($user) {
                                $query->where('member_type', $user->member_type)
                                      ->orWhere('member_type', 'annual_fee');
                            })
                            ->where('year', $selectedYear)
                            ->first();
                        $annualFeeAmount = $annualFeeSetting ? $annualFeeSetting->amount : 0;
                        $selectedYearPaid = $transactions
                            ->where('flag_status', 1)
                            ->filter(function ($t) use ($selectedYear) {
                                return date('Y', strtotime($t->date)) == $selectedYear;
                            })
                            ->sum('amount');
                        $progressPercent = $annualFeeAmount > 0 ? min(100, ($selectedYearPaid / $annualFeeAmount) * 100) : 0;
                        $isVerified = $progressPercent >= 100 && $annualFeeAmount > 0;
                    @endphp
                    <div style="margin-top: 1.5rem; width: 100%;">
                        <div
                            style="display: flex; align-items: center; justify-content: space-between; background: linear-gradient(90deg, #f1f5f9 60%, #e0e7ff 100%); border-radius: 12px; padding: 1.1rem 1.4rem; box-shadow: 0 2px 12px rgba(99,102,241,0.07); margin-bottom: 0.7rem;">
                             <span style="font-size: 1.08em; color: #3730a3; font-weight: 500;">
                                <i class="fa-light fa-coins" style="margin-right: 7px; color: #6366f1;"></i>
                                Annual Fee ({{ $selectedYear }}): <strong style="color: #6366f1;">£{{ number_format($annualFeeAmount, 2) }}</strong>
                                <span style="margin-left: 1.2em; color: #64748b; font-weight: 400; font-size: 0.98em;">
                                    Paid: <strong>£{{ number_format($selectedYearPaid, 2) }}</strong>
                                </span>
                            </span>
                            @if($isVerified)
                                <span
                                    style="color: #22c55e; font-weight: 700; font-size: 1.08em; display: flex; align-items: center; background: #e7fbe9; border-radius: 8px; padding: 0.35em 1em; box-shadow: 0 1px 4px rgba(34,197,94,0.08);">
                                    <span style="font-size: 1.2em; margin-right: 7px;">
                                        <i class="fa-solid fa-badge-check" style="color: #22c55e;"></i>
                                    </span>
                                    Verified <span style="margin-left: 7px; color: #16a34a; font-weight: 600;">(100% Complete)</span>
                                </span>
                            @else
                                <span
                                    style="color: #6366f1; font-weight: 700; font-size: 1.08em; display: flex; align-items: center; background: #eef2ff; border-radius: 8px; padding: 0.35em 1em; box-shadow: 0 1px 4px rgba(99,102,241,0.08);">
                                    <i class="fa-regular fa-hourglass-half" style="margin-right: 7px; font-size: 1.2em;"></i>
                                    {{ number_format($progressPercent, 1) }}% <span
                                        style="margin-left: 7px; color: #6366f1; font-weight: 600;">Complete</span>
                                </span>
                            @endif
                        </div>
                        <div class="progress-bar-container" style="width: 100%; max-width: 1000px; margin: 0 auto;">
                            <div class="progress-bar-outer">
                                <div class="progress-bar-inner {{ $isVerified ? 'progress-bar-green' : 'progress-bar-blue' }}" style="width: {{ $progressPercent }}%;">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <!-- Transactions Table Section -->
            <div class="table-section">
                <div class="table-header" style="display: flex; align-items: center; justify-content: space-between;">
                    <h2>Transactions</h2>
                    @php
                        // Only sum amounts where flag_status == 1
                        $totalPaid = $transactions->where('flag_status', 1)->sum('amount');
                        $maxAmount = 80;
                        $progress = min(max($totalPaid, 0), $maxAmount);
                        $progressPercent = ($progress / $maxAmount) * 100;
                        $isFull = $progress == $maxAmount;
                        // This year paid (flag_status == 1 and year == current)
                        $currentYear = now()->year;
                        $thisYearPaid = $transactions->where('flag_status', 1)
                            ->filter(function ($t) use ($currentYear) {
                                return date('Y', strtotime($t->date)) == $currentYear;
                            })
                            ->sum('amount');
                        // Flagged amount (flag_status == 0)
                        $flaggedAmount = $transactions->where('flag_status', 0)->sum('amount');
                    @endphp
                    <div style="margin-left: 1.5rem;">
                        <div style="font-size: 0.98em; color: #64748b; margin-bottom: 0.3em;">
                            <span style="display: inline-block; margin-right: 1.2em;">
                                Total: <strong>£{{ number_format($totalPaid, 2) }}</strong>
                            </span>
                            <span style="display: inline-block; margin-right: 1.2em;">
                                This Year: <strong>£{{ number_format($thisYearPaid, 2) }}</strong>
                            </span>
                            <span style="display: inline-block;">
                                Flagged: <strong style="color: #ef4444;">£{{ number_format($flaggedAmount, 2) }}</strong>
                            </span>
                        </div>
                        {{-- <div class="progress-bar-container" style="width: 28%; max-width: 200px;">
                            <div class="progress-bar-outer"
                                style="background: #f1f5f9; border-radius: 8px; height: 13px; width: 100%; overflow: hidden; position: relative;">
                                <div class="progress-bar-inner {{ $isFull ? 'progress-bar-green' : 'progress-bar-blue' }}"
                                    style="height: 100%; width: {{ $progressPercent }}%; background: {{ $isFull ? '#22c55e' : '#6366f1' }}; transition: width 0.4s; position: relative; overflow: hidden;">
                                    <span class="animated-stripes" style="position: absolute;top: 0; left: 0; right: 0; bottom: 0;width: 100%; height: 100%;pointer-events: none;background: repeating-linear-gradient(135deg,rgba(255,255,255,0.18) 0px,rgba(255,255,255,0.18) 8px,transparent 8px,transparent 16px);animation: stripes-move 2.0s linear infinite;border-radius: 8px;z-index: 2;"></span>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="sleek-filter-container">
                    <div style="margin: 18px 0 8px 0;">
                        <div class="sleek-filter-header">
                            <i class="fa-solid fa-sliders"></i>
                            Advanced Filters
                        </div>
                        <p style="color: #475569; font-size: 0.85em; margin-bottom: 7px;">
                            Use these filters to quickly find transactions by date, year, or flagged status.
                            This helps you efficiently review and manage this member's transaction history.
                        </p>
                    </div>
                    <form method="GET" action=""
                        style="display: flex; justify-content:end; align-items: end; gap: 1.1rem; padding:1.5rem 0 0 0;">
                        <div style="display: flex; flex-direction: column; margin-right: 0.7em;">
                            <label for="start_date" style="font-size: 13px; color: #64748b; margin-bottom: 0.2em;">Start
                                Date</label>
                            <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                                style="padding:4px 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; background: #fff;">
                        </div>
                        <div style="display: flex; flex-direction: column; margin-right: 0.7em;">
                            <label for="end_date" style="font-size: 13px; color: #64748b; margin-bottom: 0.2em;">End
                                Date</label>
                            <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                                style="padding:4px 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; background: #fff;">
                        </div>
                        <div style="display: flex; flex-direction: column; margin-right: 0.7em;">
                            <label for="year" style="font-size: 13px; color: #64748b; margin-bottom: 0.2em;">Year</label>
                            <select id="year" name="year"
                                style="padding:4px 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; background: #fff;">
                                <option value="">All</option>
                                @php
                                    $currentYear = now()->year;
                                    $minYear = $transactions->count() ? substr($transactions->last()->date, 0, 4) : $currentYear - 5;
                                    $minYear = min($minYear, $currentYear - 25);
                                @endphp
                                @for($y = $currentYear; $y >= $minYear; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div style="display: flex; flex-direction: column; margin-right: 0.7em;">
                            <label for="flag_status" style="font-size: 13px; color: #64748b; margin-bottom: 0.2em;">Flagged
                                Status</label>
                            <select id="flag_status" name="flag_status"
                                style="padding:4px 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; background: #fff;">
                                <option value="">All</option>
                                <option value="1" {{ $flagStatus === '1' || $flagStatus === 1 ? 'selected' : '' }}>Not Flagged
                                </option>
                                <option value="0" {{ $flagStatus === '0' || $flagStatus === 0 ? 'selected' : '' }}>Flagged</option>
                            </select>
                        </div>
                        <button type="submit"
                            style="background: #6366f1; color: #fff; display:flex; gap:4px; align-items:center; border: none; border-radius: 6px; padding:5px 10px; font-size: 14px; font-weight: 600; cursor: pointer; box-shadow: 0 1px 4px rgba(99,102,241,0.10); transition: background 0.2s;">
                            <i class="fa-light fa-filter" style="margin-right: 0.5em;"></i>Filter
                        </button>
                        @if($startDate || $endDate || $year || ($flagStatus !== null && $flagStatus !== ''))
                            <a href="{{ route('member.transactions.detail', ['name' => str_replace(' ', '-', $user->name), 'unique_id' => $user->unique_id]) }}"
                                style=" display:flex; gap:4px; align-items:center; margin-left: 5px; color: #64748b; background: #f1f5f9; border: 1px solid #e5e7eb; border-radius: 6px; padding:5px 10px; font-size: 14px; text-decoration: none; font-weight: 500; transition: background 0.2s;">
                                <i class="fa-light fa-xmark" style="margin-right: 0.4em; "></i>Reset
                            </a>
                        @endif
                    </form>
                </div>
                <div class="table-controls-container"
                    style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 16px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); border: 1px solid #e2e8f0;">
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                        <div>
                            <div style="margin: 18px 0 8px 0;">
                                <h3 style="margin: 0; color: #1e293b; font-size: 1.1em; font-weight: 600;">
                                    <i class="fa-solid fa-table" style="margin-right: 8px; color: #6366f1;"></i>
                                    Table Controls
                                </h3>
                                <p style="color: #475569; font-size: 0.85em; margin-bottom: 7px;">
                                    Use the table controls below to show or hide columns, making it easy to customize your view and focus on the transaction details that matter most to you.
                                </p>
                            </div>
                            <!-- Column Visibility Controls -->
                            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showIndex" checked onchange="toggleColumn('index')" style="margin: 0;">
                                    ID
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showMethod" checked onchange="toggleColumn('method')" style="margin: 0;">
                                    Method
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showDate" checked onchange="toggleColumn('date')" style="margin: 0;">
                                    Date
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showAmount" checked onchange="toggleColumn('amount')" style="margin: 0;">
                                    Amount
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showStatus" checked onchange="toggleColumn('status')" style="margin: 0;">
                                    Status
                                </span>
                                <!-- Add more columns as needed -->
                            </div>
                        </div>
                        <!-- Quick Actions -->
                        <div style="display: flex; gap: 10px;">
                            <button type="button" onclick="showAllColumns()" class="quick-action-btn"
                                style="background: linear-gradient(135deg, #6366f1 0%, #60a5fa 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.9em; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                                <i class="fa-solid fa-eye" style="margin-right: 5px;"></i>
                                Show All
                            </button>
                            <button type="button" onclick="hideAllColumns()" class="quick-action-btn"
                                style="background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); color: #64748b; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.9em; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                                <i class="fa-solid fa-eye-slash" style="margin-right: 5px;"></i>
                                Hide All
                            </button>
                        </div>
                    </div>
                </div>


                <!-- Skeleton Loader -->
                <div id="skeleton-loader" style="display: block;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Method</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 5; $i++)
                                <tr>
                                    <td><div class="skeleton-cell" style="width: 40px;"></div></td>
                                    <td><div class="skeleton-cell" style="width: 80px;"></div></td>
                                    <td><div class="skeleton-cell" style="width: 100px;"></div></td>
                                    <td><div class="skeleton-cell" style="width: 60px;"></div></td>
                                    <td><div class="skeleton-cell" style="width: 70px;"></div></td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>

                <!-- Actual Table (Hidden Initially) -->
                <div id="actual-table" style="display: none;">
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Method</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $i => $transaction)
                                    <tr>
                                        <td>
                                            {{ $i + 1 }}
                                            @if(isset($transaction->flag_status) && $transaction->flag_status === 0)
                                                <span class="flagged-badge"
                                                    style="background: #fee2e2; color: #dc2626; border-radius: 4px; padding: 2px 7px; font-size: 12px; margin-left: 7px;">
                                                    <i class="fa fa-flag" style="margin-right: 2px;"></i>
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="status-badge {{ strtolower($transaction->status) == 'cash' ? 'active' : 'inactive' }}">
                                                {{ strtolower($transaction->status) == 'cash' ? 'cash' : $transaction->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <span>{{ $transaction->date }}</span>
                                        </td>
                                        <td>
                                            <span>£{{ $transaction->amount }}</span>
                                        </td>
                                        <td>
                                            <span>
                                                @if(isset($transaction->flag_status) && $transaction->flag_status === 0)
                                                    <span class="flagged-badge"
                                                        style="background: #fee2e2; color: #dc2626; border-radius: 4px; padding: 2px 7px; font-size: 12px; margin-left: 7px;">
                                                        <i class="fa fa-flag" style="margin-right: 2px; color: #dc2626;"></i>Flagged
                                                    </span>
                                                @elseif(isset($transaction->flag_status) && $transaction->flag_status === 1)
                                                    <span class="flagged-badge"
                                                        style="background: #dcfce7; color: #16a34a; border-radius: 4px; padding: 2px 7px; font-size: 12px; margin-left: 7px;">
                                                        <i class="fa fa-check" style="margin-right: 2px; color: #16a34a;"></i>Success
                                                    </span>
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" style="text-align: center; padding: 30px; color: #888;">
                                            <i class="fa-light fa-circle-exclamation"
                                                style="font-size: 2em; color: #ff9800; margin-bottom: 8px;"></i>
                                            <div style="margin-top: 8px;">No transactions found.</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div id="pagination-container" style="margin-top: 1.2rem;">
                            @include('layouts.custom_pagination', ['paginator' => $transactions])
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Script: Show skeleton for 3 seconds, then show actual table -->

    </main>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const skeleton = document.getElementById("skeleton-loader");
            const table = document.getElementById("actual-table");

            setTimeout(() => {
                skeleton.style.display = "none";
                table.style.display = "block";
            }, 3000);
        });
    </script>
      <script>
        function toggleColumn(col) {
            var table = document.querySelectorAll('.data-table');
            var colIndex = {
                'index': 0,
                'method': 1,
                'date': 2,
                'amount': 3,
                'status': 4
            }[col];
            if (colIndex === undefined) return;
            var checked = document.getElementById('show' + col.charAt(0).toUpperCase() + col.slice(1)).checked;
            table.forEach(function(tbl) {
                // header
                var th = tbl.querySelectorAll('thead tr th')[colIndex];
                if (th) th.style.display = checked ? '' : 'none';
                // body
                tbl.querySelectorAll('tbody tr').forEach(function(row) {
                    var td = row.children[colIndex];
                    if (td) td.style.display = checked ? '' : 'none';
                });
            });
        }
        function showAllColumns() {
            ['index','method','date','amount','status'].forEach(function(col) {
                var cb = document.getElementById('show' + col.charAt(0).toUpperCase() + col.slice(1));
                if (cb && !cb.checked) {
                    cb.checked = true;
                    toggleColumn(col);
                }
            });
        }
        function hideAllColumns() {
            ['index','method','date','amount','status'].forEach(function(col) {
                var cb = document.getElementById('show' + col.charAt(0).toUpperCase() + col.slice(1));
                if (cb && cb.checked) {
                    cb.checked = false;
                    toggleColumn(col);
                }
            });
        }
    </script>
@endsection
