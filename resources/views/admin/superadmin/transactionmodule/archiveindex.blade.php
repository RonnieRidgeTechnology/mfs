@extends('layouts.admin')
<style>
    .custom-options input:focus {
        outline: none
    }
    .custom-select-wrapper input:focus {
        outline: none
    }
    .custom-select-wrapper {
        position: relative;
        width: 250px;
        font-family: sans-serif;
    }
    .custom-select-input {
        padding: 10px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        width: 100%;
        box-sizing: border-box;
        cursor: pointer;
        background-color: white;
    }
    .custom-options {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #cbd5e1;
        border-top: none;
        background: white;
        z-index: 10;
        display: none;
    }
    .custom-options div {
        padding: 10px;
        cursor: pointer;
    }
    .custom-options div:hover {
        background-color: #f0f0f0;
    }
    .custom-options input {
        padding: 10px;
        border: none;
        border-bottom: 1px solid #ccc;
        width: 100%;
        box-sizing: border-box;
    }
    .custom-options .no-results {
        color: #888;
        text-align: center;
        padding: 18px 10px;
        font-size: 1em;
        cursor: default;
        background: #f9fafb;
        user-select: none;
    }
    .show {
        display: block !important;
    }
    .progress-bar-container {
        width: 100px;
        max-width: 120px;
        min-width: 80px;
        margin: 0 auto;
    }
    .progress-bar-outer {
        background: #e5e7eb;
        border-radius: 5px;
        height: 12px;
        width: 100%;
        overflow: hidden;
        position: relative;
    }
    .progress-bar-inner {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        border-radius: 5px;
        font-size: 0.95rem;
        font-weight: 600;
        transition: width 0.4s cubic-bezier(.4, 2, .6, 1);
        position: relative;
        padding-right: 8px;
        color: #fff;
    }
    .progress-bar-green {
        background: linear-gradient(90deg, #22c55e 0%, #16a34a 100%);
    }
    .progress-bar-blue {
        background: linear-gradient(90deg, #6366f1 0%, #38bdf8 100%);
    }
    .progress-bar-label {
        position: absolute;
        right: 8px;
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        z-index: 2;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
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
</style>
@section('content')
<main class="main-content">
    @include('layouts.header')
    <div class="content">
        <div class="table-section">
            <div class="table-header">
                <div class="debit-header-container" style="width: 100%;">
                     <div style="display: flex;align-items: center;justify-content: space-between;margin-bottom: 16px;">
                        <div>
                            <h2>Archived Transactions</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-container">
                 <!-- Filter Container -->
                 <div class="filter-container" style="margin-bottom: 18px;">
                    <div class="filter-header">
                        <div style="margin: 18px 0 8px 0;">
                            <h3 class="filter-title">
                                <i class="fa-solid fa-filter" style="margin-right: 8px; color: #6366f1;"></i>
                                Advance Filters
                            </h3>
                            <p style="color: #475569; font-size: 0.85em; margin-bottom: 7px;">
                                Use the advanced filters below to quickly locate specific archived transactions by member,
                                unique ID, type, or year.
                            </p>
                        </div>
                    </div>
                    <form method="GET" action="{{ route('transactions.archive') }}" style="width: 100%;">
                        <div style="display: flex; gap: 30px; flex-wrap: wrap; align-items: flex-end;">
                            <!-- Member Name Filter -->
                            <div style="position: relative; min-width: 220px;">
                                <label for="memberInput"
                                    style="font-size: 0.95em; color: #64748b; margin-bottom: 0.2em;">Member Name</label>
                                <input type="text" id="memberInput" class="custom-select-input"
                                    placeholder="Search Member..." autocomplete="off"
                                    value="{{ request('member_name') }}" style="background: #fff; cursor: pointer;">
                                <div class="custom-select-wrapper"
                                    style="position: absolute; width: 100%; z-index: 10;">
                                    <div class="custom-options" id="memberOptions" style="display: none;">
                                        <input type="text" id="memberSearch" placeholder="Type to search...">
                                        <div class="option-item-member" data-value="">All</div>
                                        @php
                                            $memberNames = \App\Models\User::where('type', 'member')->pluck('name')->unique();
                                        @endphp
                                        @foreach($memberNames as $name)
                                            <div class="option-item-member" data-value="{{ $name }}">{{ $name }}</div>
                                        @endforeach
                                        <div class="no-results" style="display:none;">No members found.</div>
                                    </div>
                                    <input type="hidden" name="member_name" id="selectedMember"
                                        value="{{ request('member_name') }}">
                                </div>
                            </div>
                            <!-- Unique ID Filter -->
                            <div style="min-width: 180px;">
                                <div class="custom-select-wrapper">
                                    <label for="uniqueInput"
                                        style="font-size: 0.95em; color: #64748b; margin-bottom: 0.2em;">Unique
                                        ID</label>
                                    <input type="text" id="uniqueInput" class="custom-select-input"
                                        placeholder="Search Unique ID..." readonly>
                                    <div class="custom-options" id="optionsList">
                                        <input type="text" id="searchBox" placeholder="Type to search...">
                                        <div class="option-item" data-value="">All</div>
                                        @php
                                            $uniqueIds = \App\Models\User::where('type', 'member')->pluck('unique_id')->unique();
                                        @endphp
                                        @foreach($uniqueIds as $uid)
                                            <div class="option-item" data-value="{{ $uid }}">{{ $uid }}</div>
                                        @endforeach
                                        <div class="no-results" style="display:none;">No unique IDs found.</div>
                                    </div>
                                    <input type="hidden" name="unique_id" id="selectedUniqueId"
                                        value="{{ request('unique_id') }}">
                                </div>
                            </div>
                            <!-- Transaction Type Filter -->
                            <div style="display: flex;flex-direction: column; width: 155px;">
                                <label for="transaction_type"
                                    style="font-size: 0.95em; color: #64748b; margin-bottom: 0.2em;">Type</label>
                                <select id="transaction_type" name="transaction_type" class="custom-select-input" style="padding-right: 2em; width: 100% !important;">
                                    <option value="">All</option>
                                    <option value="credit" {{ request('transaction_type') == 'credit' ? 'selected' : '' }}>Credit</option>
                                    <option value="debit" {{ request('transaction_type') == 'debit' ? 'selected' : '' }}>Debit</option>
                                </select>
                            </div>
                            <!-- Year Filter -->
                            <div style="display: flex ;flex-direction: column;">
                                <label for="year"
                                    style="font-size: 0.95em; color: #64748b; margin-bottom: 0.2em;">Year</label>
                                <select id="year" name="year"
                                    style="min-width: 90px; padding: 0.4em 0.7em; border-radius: 6px; border: 1px solid #cbd5e1;">
                                    <option value="">All</option>
                                    @php
                                        $currentYear = date('Y');
                                    @endphp
                                    @for($y = $currentYear - 1; $y >= 2000; $y--)
                                        <option value="{{ $y }}" {{ (request('year') == $y) ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <!-- Filter/Reset Buttons -->
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <button type="submit"
                                    style="background: #6366f1; color: #fff; border: none; border-radius: 6px; padding: 0.5em 1.2em; font-weight: 600; margin-top: 1.5em; height: 2.4em;">
                                    <i class="fa-light fa-filter"></i> Filter
                                </button>
                                <a href="{{ route('transactions.archive') }}"
                                    style="background: #f1f5f9; color: #334155; border: none; border-radius: 6px; padding: 0.5em 1.2em; font-weight: 600; margin-top: 1em; height: 2.2em; text-decoration: none;">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            <!-- Table Controls Section -->
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
                                Use the table controls below to show or hide columns, making it easy to customize
                                your view and focus on the transaction details that matter most to you.
                            </p>
                        </div>
                        <!-- Column Visibility Controls -->
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <span class="badge"
                                style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                <input type="checkbox" id="showMemberId" checked data-col="member-id"
                                    style="margin: 0;">
                                Member ID
                            </span>
                            <span class="badge"
                                style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                <input type="checkbox" id="showName" checked data-col="name" style="margin: 0;">
                                Name
                            </span>
                            <span class="badge"
                                style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                <input type="checkbox" id="showPhone" checked data-col="phone" style="margin: 0;">
                                Phone
                            </span>
                            <span class="badge"
                                style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                <input type="checkbox" id="showAddress" checked data-col="address" style="margin: 0;">
                                Address
                            </span>
                            <span class="badge"
                                style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                <input type="checkbox" id="showStatus" checked data-col="status" style="margin: 0;">
                                Status
                            </span>
                            <span class="badge"
                                style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                <input type="checkbox" id="showDate" checked data-col="date" style="margin: 0;">
                                Date
                            </span>
                            <span class="badge"
                                style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                <input type="checkbox" id="showProgress" checked data-col="progress"
                                    style="margin: 0;">
                                Progress
                            </span>
                            <span class="badge"
                                style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                <input type="checkbox" id="showAmount" checked data-col="amount" style="margin: 0;">
                                Amount
                            </span>
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
            </div>
              <div class="table-container">
                <!-- Skeleton Loader -->
                  <div id="skeleton-loader" style="display: block; position: relative;">
                    <!-- Shinner overlay -->
                    <div class="shinner-overlay" style="
                        position: absolute;
                        top: 0; left: 0; right: 0; bottom: 0;
                        pointer-events: none;
                        z-index: 2;
                    ">
                        <div class="shinner-bar" style="
                            position: absolute;
                            top: 0; left: -60%;
                            width: 60%;
                            height: 100%;
                            background: linear-gradient(120deg, rgba(255,255,255,0) 0%, rgba(245,245,255,0.5) 50%, rgba(255,255,255,0) 100%);
                            animation: shinner-move 1.2s infinite;
                        "></div>
                    </div>
                    <table class="data-table" style="position: relative; z-index: 1;">
                        <thead>
                            <tr>
                                <th>Member ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Progress</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i = 0; $i < 6; $i++)
                                <tr>
                                    @for($j = 0; $j < 8; $j++)
                                        <td>
                                            <div style="height: 18px; width: 100%; background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%); background-size: 200% 100%; animation: skeleton-loading 1.2s infinite linear; border-radius: 4px; position: relative;"></div>
                                        </td>
                                    @endfor
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    <style>
                        @keyframes skeleton-loading {
                            0% {
                                background-position: 200% 0;
                            }
                            100% {
                                background-position: -200% 0;
                            }
                        }
                        @keyframes shinner-move {
                            0% {
                                left: -60%;
                            }
                            100% {
                                left: 100%;
                            }
                        }
                    </style>
                </div>
                <!-- Actual Table -->
                <table class="data-table" id="real-table" style="display: none;">
                    <thead>
                        <tr>
                            <th>Member ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Progress</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody id="transactionTableBody">
                        @if($transactions->isEmpty())
                            <tr>
                                <td colspan="9" style="text-align: center; padding: 30px; color: #888;">
                                    <i class="fa-light fa-circle-exclamation"
                                        style="font-size: 2em; color: #ff9800; margin-bottom: 8px;"></i>
                                    <div style="margin-top: 8px;">No Archive transactions found.</div>
                                </td>
                            </tr>
                        @else
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->user->unique_id }}</td>
                                    <td>
                                        <a href="{{ route('member.transactions.detail', ['name' => str_replace(' ', '-', $transaction->user->name), 'unique_id' => $transaction->user->unique_id]) }}" style="text-decoration: none; color: inherit;">
                                            <div class="employee-info">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($transaction->user->name) }}"
                                                    alt="{{ $transaction->user->name }}">
                                                <div>
                                                    <h4>{{ $transaction->user->name }}</h4>
                                                    <span>{{ $transaction->user->email }}</span>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="employee-info">
                                            <div>
                                                <h4>{{ $transaction->user->phone }}</h4>
                                                <span>{{ $transaction->user->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($transaction->user->street || $transaction->user->area || $transaction->user->town || $transaction->user->postal_code)
                                            <div style="font-size: 0.9em; color: #666; max-width: 200px;">
                                                @if($transaction->user->street)
                                                    <div style="margin-bottom: 2px;">{{ $transaction->user->street }}</div>
                                                @endif
                                                @if($transaction->user->area)
                                                    <div style="margin-bottom: 2px;">{{ $transaction->user->area }}</div>
                                                @endif
                                                @if($transaction->user->town)
                                                    <div style="margin-bottom: 2px;">{{ $transaction->user->town }}</div>
                                                @endif
                                                @if($transaction->user->postal_code)
                                                    <div style="font-weight: 600; color: #333;">{{ $transaction->user->postal_code }}</div>
                                                @endif
                                            </div>
                                        @else
                                            <span style="color: #999; font-style: italic; font-size: 0.9em;">No address</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="status-badge {{ strtolower($transaction->status) == 'cash' ? 'active' : 'inactive' }}"
                                            style="position: relative; overflow: hidden;">
                                            {{ strtolower($transaction->status) == 'cash' ? 'credit' : $transaction->status }}
                                            <span class="animated-stripes"></span>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="activity-info">
                                            <span>{{ $transaction->date }}</span>
                                        </div>
                                    </td>
                                     <td>
                                        @php
                                            // Dynamically determine the annual fee amount for this user and transaction year (not current year)
                                            $transactionYear = null;
                                            if (!empty($transaction->date)) {
                                                try {
                                                    $transactionYear = \Carbon\Carbon::parse($transaction->date)->format('Y');
                                                } catch (\Exception $e) {
                                                    $transactionYear = null;
                                                }
                                            }
                                            $currentYear = now()->year;
                                            // Only show progress if transaction year is NOT current year
                                            $showProgress = $transactionYear && $transactionYear != $currentYear;
                                            $annualFeeAmount = 0;
                                            if ($showProgress) {
                                                $annualFeeSetting = \App\Models\MembershipFeeSetting::where(function($query) use ($transaction) {
                                                        $query->where('member_type', $transaction->user->member_type)
                                                              ->orWhere('member_type', 'annual_fee');
                                                    })
                                                    ->where('year', $transactionYear)
                                                    ->first();
                                                $annualFeeAmount = $annualFeeSetting ? $annualFeeSetting->amount : 0;
                                            }
                                            $amount = (float) $transaction->amount;
                                            $progress = $showProgress && $annualFeeAmount > 0 ? min(max($amount, 0), $annualFeeAmount) : 0;
                                            $progressPercent = $annualFeeAmount > 0 ? min(100, ($progress / $annualFeeAmount) * 100) : 0;
                                            $isFull = $progressPercent >= 100 && $annualFeeAmount > 0;
                                        @endphp
                                        @if($showProgress && $annualFeeAmount > 0)
                                            <div class="progress-bar-container">
                                                <div class="progress-bar-outer" title="Amount: £{{ $transaction->amount }}">
                                                    <div class="progress-bar-inner {{ $isFull ? 'progress-bar-green' : 'progress-bar-blue' }}"
                                                        style="width: {{ $progressPercent }}%;">
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span style="color: #999; font-size: 0.95em;">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($showProgress && $annualFeeAmount > 0)
                                            <span>£{{ $transaction->amount }}/£{{ number_format($annualFeeAmount, 2) }}</span>
                                        @else
                                            <span style="color: #999; font-size: 0.95em;">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <div id="pagination-container" style="display: none;">
                    @include('layouts.custom_pagination', ['paginator' => $transactions])
                </div>
              </div>

        </div>
    </div>


</main>
<script>
    // Skeleton loader logic
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(function () {
            var skeleton = document.getElementById('skeleton-loader');
            var realTable = document.getElementById('real-table');
            var pagination = document.getElementById('pagination-container');
            if (skeleton) skeleton.style.display = 'none';
            if (realTable) realTable.style.display = '';
            if (pagination) pagination.style.display = '';
        }, 3000); // 0.9 sec
    });
  </script>
   <script>
                // Table column show/hide logic
                document.addEventListener('DOMContentLoaded', function () {
                    const colMap = {
                        'member-id': 0,
                        'name': 1,
                        'phone': 2,
                        'address': 3,
                        'status': 4,
                        'date': 5,
                        'progress': 6,
                        'amount': 7
                    };
                    function setColVisibility(col, visible) {
                        const table = document.querySelector('.data-table');
                        if (!table) return;
                        const idx = colMap[col];
                        table.querySelectorAll('tr').forEach(row => {
                            if (row.children[idx]) {
                                row.children[idx].style.display = visible ? '' : 'none';
                            }
                        });
                    }
                    Object.keys(colMap).forEach(col => {
                        const cb = document.getElementById('show' + col.replace(/(^|-)([a-z])/g, (m, _, c) => c.toUpperCase()));
                        if (cb) {
                            cb.addEventListener('change', function () {
                                setColVisibility(col, cb.checked);
                            });
                        }
                    });
                    window.showAllColumns = function () {
                        Object.keys(colMap).forEach(col => {
                            const cb = document.getElementById('show' + col.replace(/(^|-)([a-z])/g, (m, _, c) => c.toUpperCase()));
                            if (cb) {
                                cb.checked = true;
                                setColVisibility(col, true);
                            }
                        });
                    };
                    window.hideAllColumns = function () {
                        Object.keys(colMap).forEach(col => {
                            const cb = document.getElementById('show' + col.replace(/(^|-)([a-z])/g, (m, _, c) => c.toUpperCase()));
                            if (cb) {
                                cb.checked = false;
                                setColVisibility(col, false);
                            }
                        });
                    };
                });
            </script>
<script>
    // Member Name Dropdown
    document.addEventListener('DOMContentLoaded', function () {
        const memberInput = document.getElementById('memberInput');
        const memberOptions = document.getElementById('memberOptions');
        const memberSearch = document.getElementById('memberSearch');
        const selectedMember = document.getElementById('selectedMember');
        const optionItems = memberOptions.querySelectorAll('.option-item-member');
        const noResults = memberOptions.querySelector('.no-results');

        // Show dropdown on input click
        memberInput.addEventListener('click', function (e) {
            memberOptions.style.display = 'block';
            memberSearch.value = '';
            filterMemberOptions('');
            memberSearch.focus();
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!memberInput.contains(e.target) && !memberOptions.contains(e.target)) {
                memberOptions.style.display = 'none';
            }
        });

        // Filter options as user types
        memberSearch.addEventListener('input', function () {
            filterMemberOptions(this.value);
        });

        function filterMemberOptions(search) {
            let found = false;
            optionItems.forEach(function (item) {
                if (item.textContent.toLowerCase().includes(search.toLowerCase())) {
                    item.style.display = '';
                    found = true;
                } else {
                    item.style.display = 'none';
                }
            });
            noResults.style.display = found ? 'none' : '';
        }

        // Select option
        optionItems.forEach(function (item) {
            item.addEventListener('click', function () {
                memberInput.value = this.getAttribute('data-value');
                selectedMember.value = this.getAttribute('data-value');
                memberOptions.style.display = 'none';
            });
        });

        // If value is prefilled, show in input
        if (selectedMember.value) {
            memberInput.value = selectedMember.value;
        }
    });
</script>
<script>
    const input = document.getElementById('uniqueInput');
    const optionsList = document.getElementById('optionsList');
    const searchBox = document.getElementById('searchBox');
    const hiddenInput = document.getElementById('selectedUniqueId');
    const optionItems = optionsList.querySelectorAll('.option-item');
    const uniqueNoResults = optionsList.querySelector('.no-results');

    // Open dropdown
    input.addEventListener('click', () => {
        optionsList.classList.toggle('show');
        searchBox.value = '';
        searchBox.focus();
        filterOptions('');
    });

    // Close dropdown on outside click
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.custom-select-wrapper')) {
            optionsList.classList.remove('show');
        }
    });

    // Handle selection
    optionItems.forEach(item => {
        item.addEventListener('click', () => {
            input.value = item.textContent;
            hiddenInput.value = item.dataset.value;
            optionsList.classList.remove('show');
        });
    });

    // Search filter
    searchBox.addEventListener('keyup', () => {
        const filter = searchBox.value.toLowerCase();
        filterOptions(filter);
    });

    function filterOptions(filter) {
        let anyVisible = false;
        optionItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            const show = text.includes(filter);
            item.style.display = show ? '' : 'none';
            if (show) anyVisible = true;
        });
        if (uniqueNoResults) {
            uniqueNoResults.style.display = anyVisible ? 'none' : '';
        }
    }

    // Pre-fill input if value exists
    window.addEventListener('DOMContentLoaded', () => {
        const selectedVal = hiddenInput.value;
        if (selectedVal) {
            const selected = [...optionItems].find(item => item.dataset.value === selectedVal);
            if (selected) {
                input.value = selected.textContent;
            }
        }
    });
</script>
<script>
    const memberInput = document.getElementById('memberInput');
    const memberOptions = document.getElementById('memberOptions');
    const memberSearch = document.getElementById('memberSearch');
    const selectedMember = document.getElementById('selectedMember');
    const memberItems = memberOptions.querySelectorAll('.option-item-member');
    const memberNoResults = memberOptions.querySelector('.no-results');

    // Toggle dropdown
    memberInput.addEventListener('click', () => {
        memberOptions.classList.toggle('show');
        memberSearch.value = '';
        memberSearch.focus();
        filterMemberOptions('');
    });

    // Close dropdown outside
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.custom-select-wrapper')) {
            memberOptions.classList.remove('show');
        }
    });

    // Select option
    memberItems.forEach(item => {
        item.addEventListener('click', () => {
            memberInput.value = item.textContent;
            selectedMember.value = item.dataset.value;
            memberOptions.classList.remove('show');
        });
    });

    // Search
    memberSearch.addEventListener('keyup', () => {
        const filter = memberSearch.value.toLowerCase();
        filterMemberOptions(filter);
    });

    function filterMemberOptions(filter) {
        let anyVisible = false;
        memberItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            const show = text.includes(filter);
            item.style.display = show ? '' : 'none';
            if (show) anyVisible = true;
        });
        if (memberNoResults) {
            memberNoResults.style.display = anyVisible ? 'none' : '';
        }
    }

    // Pre-select value on load
    window.addEventListener('DOMContentLoaded', () => {
        const selectedVal = selectedMember.value;
        if (selectedVal) {
            const selected = [...memberItems].find(item => item.dataset.value === selectedVal);
            if (selected) {
                memberInput.value = selected.textContent;
            }
        }
    });
</script>
</main>
@endsection
