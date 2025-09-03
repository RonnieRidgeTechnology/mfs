@extends('layouts.admin')
<style>
    /* ... (keep all the same styles as before) ... */
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

    .sleek-import-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(90deg, #6366f1 0%, #38bdf8 100%);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1.08rem;
        padding: 0.55rem 1.5rem;
        box-shadow: 0 2px 12px rgba(99, 102, 241, 0.13);
        transition: background 0.18s, box-shadow 0.18s, transform 0.13s;
        cursor: pointer;
    }

    .sleek-import-btn:hover {
        background: linear-gradient(90deg, #4f46e5 0%, #0ea5e9 100%);
        box-shadow: 0 4px 18px rgba(99, 102, 241, 0.18);
        transform: translateY(-2px) scale(1.03);
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

    .sleek-modal-overlay {
        display: none;
        position: fixed;
        z-index: 1200;
        left: 0;
        top: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(20, 24, 31, 0.55);
        backdrop-filter: blur(5px) saturate(120%);
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    .sleek-modal-overlay.active {
        display: flex;
        animation: sleekFadeIn 0.22s cubic-bezier(.4, 2, .6, 1);
    }

    @keyframes sleekFadeIn {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    .sleek-modal {
        background: linear-gradient(135deg, #f8fafc 80%, #e0e7ef 100%);
        border-radius: 22px;
        box-shadow: 0 8px 40px 0 rgba(31, 38, 135, 0.22), 0 1.5px 8px 0 rgba(0, 0, 0, 0.08);
        border: none;
        width: 100%;
        max-width: 410px;
        min-width: 320px;
        animation: sleekPopIn 0.22s cubic-bezier(.4, 2, .6, 1) both;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    @keyframes sleekPopIn {
        0% {
            transform: scale(0.92) translateY(40px);
            opacity: 0;
        }

        100% {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
    }

    .sleek-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.3rem 2rem 1.1rem 2rem;
        border-bottom: 1px solid #e2e8f0;
        background: transparent;
    }

    .sleek-modal-header h3 {
        font-size: 1.32rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .sleek-modal-close {
        background: none;
        border: none;
        font-size: 1.7rem;
        color: #64748b;
        cursor: pointer;
        transition: color 0.15s, transform 0.15s;
        padding: 0 0.2rem;
        line-height: 1;
    }

    .sleek-modal-close:hover {
        color: #ef4444;
        transform: scale(1.15) rotate(8deg);
    }

    .sleek-modal-body {
        padding: 2rem 2rem 1.5rem 2rem;
        background: transparent;
        display: flex;
        flex-direction: column;
        gap: 1.1rem;
    }

    .sleek-label {
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.5rem;
        display: block;
        font-size: 1.07rem;
    }

    .sleek-input {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 0.6rem 1rem;
        font-size: 1.07rem;
        background: #f9fafb;
        transition: border 0.2s, box-shadow 0.2s;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }

    .sleek-input:focus {
        border-color: #6366f1;
        outline: none;
        background: #fff;
        box-shadow: 0 0 0 2px #6366f133;
    }

    .sleek-progress {
        margin-top: 10px;
        height: 16px;
        background: #e0e7ef;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    }

    .sleek-progress-bar {
        background: linear-gradient(90deg, #6366f1 0%, #38bdf8 100%);
        color: #fff;
        font-size: 0.97rem;
        font-weight: 600;
        border-radius: 10px;
        transition: width 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    .sleek-status {
        margin-top: 10px;
        font-size: 0.98rem;
        min-height: 22px;
    }

    .sleek-modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 0.7rem;
        padding: 1.1rem 2rem 1.3rem 2rem;
        background: transparent;
        border-top: 1px solid #e2e8f0;
    }

    .sleek-btn {
        border-radius: 9px;
        font-weight: 600;
        font-size: 1.05rem;
        padding: 0.5rem 1.4rem;
        transition: background 0.18s, color 0.18s, box-shadow 0.18s;
        box-shadow: 0 1.5px 6px rgba(99, 102, 241, 0.08);
        border: none;
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .sleek-btn-primary {
        background: linear-gradient(90deg, #6366f1 0%, #38bdf8 100%);
        color: #fff;
        border: none;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.10);
    }

    .sleek-btn-primary:hover {
        background: linear-gradient(90deg, #4f46e5 0%, #0ea5e9 100%);
        box-shadow: 0 4px 16px rgba(99, 102, 241, 0.13);
    }

    .sleek-btn-secondary {
        background: #f1f5f9;
        color: #334155;
        border: none;
    }

    .sleek-btn-secondary:hover {
        background: #e2e8f0;
    }

    @media (max-width: 600px) {
        .sleek-modal {
            max-width: 98vw;
            min-width: unset;
            border-radius: 12px;
        }

        .sleek-modal-header,
        .sleek-modal-footer,
        .sleek-modal-body {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .progress-bar-container {
            width: 80px;
            min-width: 60px;
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
                                <h2>Debit Transactions</h2>
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
                                    Use the advanced filters below to quickly locate specific debit transactions by member,
                                    unique ID, date, or year.
                                </p>
                            </div>
                        </div>
                        <form method="GET" action="{{ route('transactions.debit') }}" style="width: 100%;">
                            <div style="display: flex;gap:10px;align-items: end;gap:20px;">
                                <div style="position: relative;">
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
                                <div>
                                    <!-- Unique ID Search Selector -->
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
                                <!-- Start Date -->
                                <div style="display: flex; flex-direction: column;">
                                    <label for="start_date"
                                        style="font-size: 0.95em; color: #64748b; margin-bottom: 0.2em;">Start Date</label>
                                    <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}"
                                        style="padding: 0.4em 0.7em; border-radius: 6px; border: 1px solid #cbd5e1;">
                                </div>
                                <!-- End Date -->
                                <div style="display: flex; flex-direction: column;">
                                    <label for="end_date"
                                        style="font-size: 0.95em; color: #64748b; margin-bottom: 0.2em;">End
                                        Date</label>
                                    <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}"
                                        style="padding: 0.4em 0.7em; border-radius: 6px; border: 1px solid #cbd5e1;">
                                </div>
                                <!-- Yearly Filter -->
                                <div style="display: flex; flex-direction: column;">
                                    <label for="year"
                                        style="font-size: 0.95em; color: #64748b; margin-bottom: 0.2em;">Year</label>
                                    <select id="year" name="year"
                                        style="min-width: 90px; padding: 0.4em 0.7em; border-radius: 6px; border: 1px solid #cbd5e1;">
                                        <option value="">All</option>
                                        @php
                                            $currentYear = date('Y');
                                        @endphp
                                        @for($y = $currentYear; $y >= 2000; $y--)
                                            <option value="{{ $y }}" {{ (request('year') == $y) ? 'selected' : '' }}>{{ $y }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <button type="submit"
                                        style="background: #6366f1; color: #fff; border: none; border-radius: 6px; padding: 0.5em 1.2em; font-weight: 600; margin-top: 1.5em; height: 2.4em;">
                                        <i class="fa-light fa-filter"></i>
                                    </button>
                                    <a href="{{ route('transactions.debit') }}"
                                        style="background: #f1f5f9; color: #334155; border: none; border-radius: 6px; padding: 0.5em 1.2em; font-weight: 600; margin-top: 1.5em; height: 2.4em; text-decoration: none;">
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
                                        Use the table controls below to show or hide columns, making it easy to customize your view and focus on the transaction details that matter most to you.
                                    </p>
                                </div>
                                <!-- Column Visibility Controls -->
                                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                    <span class="badge"
                                        style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                        <input type="checkbox" id="showMemberId" checked data-col="member-id" style="margin: 0;">
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
                                        <input type="checkbox" id="showProgress" checked data-col="progress" style="margin: 0;">
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

                    <!-- Skeleton Loader (visible on page load for 3 seconds) -->
                    <div id="skeleton-loader" style="display: block;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th class="col-member-id">Member ID</th>
                                    <th class="col-name">Name</th>
                                    <th class="col-phone">Phone</th>
                                    <th class="col-address">Address</th>
                                    <th class="col-status">Status</th>
                                    <th class="col-date">Date</th>
                                    <th class="col-progress">Progress</th>
                                    <th class="col-amount">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < 5; $i++)
                                    <tr>
                                        <td class="col-member-id">
                                            <div class="skeleton-cell"
                                                style="width: 60px; height: 18px; background: #e5e7eb; border-radius: 4px;">
                                            </div>
                                        </td>
                                        <td class="col-name">
                                            <div class="skeleton-cell"
                                                style="width: 140px; height: 32px; background: #e5e7eb; border-radius: 4px; margin-bottom: 4px;">
                                            </div>
                                            <div class="skeleton-cell"
                                                style="width: 80px; height: 14px; background: #e5e7eb; border-radius: 4px;">
                                            </div>
                                        </td>
                                        <td class="col-phone">
                                            <div class="skeleton-cell"
                                                style="width: 90px; height: 18px; background: #e5e7eb; border-radius: 4px; margin-bottom: 4px;">
                                            </div>
                                            <div class="skeleton-cell"
                                                style="width: 120px; height: 14px; background: #e5e7eb; border-radius: 4px;">
                                            </div>
                                        </td>
                                        <td class="col-address">
                                            <div class="skeleton-cell"
                                                style="width: 120px; height: 18px; background: #e5e7eb; border-radius: 4px;">
                                            </div>
                                        </td>
                                        <td class="col-status">
                                            <div class="skeleton-cell"
                                                style="width: 60px; height: 18px; background: #e5e7eb; border-radius: 4px;">
                                            </div>
                                        </td>
                                        <td class="col-date">
                                            <div class="skeleton-cell"
                                                style="width: 80px; height: 18px; background: #e5e7eb; border-radius: 4px;">
                                            </div>
                                        </td>
                                        <td class="col-progress">
                                            <div class="skeleton-cell"
                                                style="width: 100px; height: 14px; background: #e5e7eb; border-radius: 4px;">
                                            </div>
                                        </td>
                                        <td class="col-amount">
                                            <div class="skeleton-cell"
                                                style="width: 70px; height: 18px; background: #e5e7eb; border-radius: 4px;">
                                            </div>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                    <div id="real-data-table" style="display: none;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th class="col-member-id">Member ID</th>
                                    <th class="col-name">Name</th>
                                    <th class="col-phone">Phone</th>
                                    <th class="col-address">Address</th>
                                    <th class="col-status">Status</th>
                                    <th class="col-date">Date</th>
                                    <th class="col-progress">Progress</th>
                                    <th class="col-amount">Amount</th>
                                </tr>
                            </thead>
                            <tbody id="transactionTableBody">
                                @if($transactions->isEmpty())
                                    <tr>
                                        <td colspan="9" style="text-align: center; padding: 30px; color: #888;">
                                            <i class="fa-light fa-circle-exclamation"
                                                style="font-size: 2em; color: #ff9800; margin-bottom: 8px;"></i>
                                            <div style="margin-top: 8px;">No debit transactions found.</div>
                                        </td>
                                    </tr>
                                @else
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td class="col-member-id">{{ $transaction->user->unique_id }}</td>
                                            <td class="col-name">
                                                <a href="{{ route('member.transactions.detail', ['name' => str_replace(' ', '-', $transaction->user->name), 'unique_id' => $transaction->user->unique_id]) }}"
                                                    style="text-decoration: none; color: inherit;">
                                                    <div class="employee-info">
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($transaction->user->name) }}"
                                                            alt="{{ $transaction->user->name }}">
                                                        <div>
                                                            <h4>{{ $transaction->user->name }}</h4>
                                                            <span>ID: {{ $transaction->user->unique_id }}</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </td>
                                            <td class="col-phone">
                                                <div class="employee-info">
                                                    <div>
                                                        <h4>{{ $transaction->user->phone }}</h4>
                                                        <span>{{ $transaction->user->email }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="col-address">
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
                                                        <div style="font-weight: 600; color: #333;">{{ $transaction->user->postal_code }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <span style="color: #999; font-style: italic; font-size: 0.9em;">No address</span>
                                            @endif
                                            </td>
                                            <td class="col-status">
                                                <span
                                                    class="status-badge {{ strtolower($transaction->status) == 'debit' ? 'inactive' : 'active' }}"
                                                    style="position: relative; overflow: hidden;">
                                                    {{ strtolower($transaction->status) == 'debit' ? 'debit' : $transaction->status }}
                                                    <span class="animated-stripes"></span>
                                                </span>
                                            </td>
                                            <td class="col-date">
                                                <div class="activity-info">
                                                    <span>{{ $transaction->date }}</span>
                                                </div>
                                            </td>
                                             @php
                                                // Determine annual fee amount dynamically (same as in member_detail.blade.php)
                                                $currentYear = now()->year;
                                                // Use the selected year from filter, fallback to current year if not set
                                                $feeYear = $selectedYear ?? $currentYear;

                                                // Try to get year from transaction date if not set
                                                if (!$feeYear && !empty($transaction->date)) {
                                                    try {
                                                        $feeYear = \Carbon\Carbon::parse($transaction->date)->format('Y');
                                                    } catch (\Exception $e) {
                                                        $feeYear = $currentYear;
                                                    }
                                                }

                                                // Get annual fee amount for the user's member_type and year
                                                $annualFeeSetting = \App\Models\MembershipFeeSetting::where(function($query) use ($transaction) {
                                                        $query->where('member_type', $transaction->user->member_type)
                                                              ->orWhere('member_type', 'annual_fee');
                                                    })
                                                    ->where('year', $feeYear)
                                                    ->first();
                                                $annualFeeAmount = $annualFeeSetting ? $annualFeeSetting->amount : 0;

                                                $amount = (float) $transaction->amount;
                                                $progress = min(max($amount, 0), $annualFeeAmount);
                                                $progressPercent = $annualFeeAmount > 0 ? min(100, ($progress / $annualFeeAmount) * 100) : 0;
                                                $isFull = $progress >= $annualFeeAmount && $annualFeeAmount > 0;
                                            @endphp
                                            <td class="col-progress">
                                                <div class="progress-bar-container">
                                                    <div class="progress-bar-outer" title="Amount: £{{ $transaction->amount }}">
                                                        <div class="progress-bar-inner {{ $isFull ? 'progress-bar-green' : 'progress-bar-blue' }}"
                                                            style="width: {{ $progressPercent }}%;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="col-amount">
                                                <span>£{{ $transaction->amount }}/£{{ number_format($annualFeeAmount, 2) }}</span>
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
        // Table column show/hide logic
        function toggleColumn(col) {
            var checked = document.querySelector('input[data-col="' + col + '"]').checked;
            var ths = document.querySelectorAll('th.col-' + col);
            var tds = document.querySelectorAll('td.col-' + col);
            if (checked) {
                ths.forEach(function (el) { el.style.display = ''; });
                tds.forEach(function (el) { el.style.display = ''; });
            } else {
                ths.forEach(function (el) { el.style.display = 'none'; });
                tds.forEach(function (el) { el.style.display = 'none'; });
            }
        }
        function showAllColumns() {
            document.querySelectorAll('.table-controls-container input[type="checkbox"][data-col]').forEach(function (cb) {
                cb.checked = true;
                toggleColumn(cb.getAttribute('data-col'));
            });
        }
        function hideAllColumns() {
            document.querySelectorAll('.table-controls-container input[type="checkbox"][data-col]').forEach(function (cb) {
                cb.checked = false;
                toggleColumn(cb.getAttribute('data-col'));
            });
        }
        // Attach event listeners
        document.querySelectorAll('.table-controls-container input[type="checkbox"][data-col]').forEach(function (cb) {
            cb.addEventListener('change', function () {
                toggleColumn(cb.getAttribute('data-col'));
            });
        });
        // On page load, ensure columns are shown/hidden according to checkboxes
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.table-controls-container input[type="checkbox"][data-col]').forEach(function (cb) {
                toggleColumn(cb.getAttribute('data-col'));
            });
        });
    </script>
    <script>
        // Skeleton loader logic
        document.addEventListener('DOMContentLoaded', function () {
            var skeletonLoader = document.getElementById('skeleton-loader');
            var realDataTable = document.getElementById('real-data-table');
            var paginationContainer = document.getElementById('pagination-container');
            // Always show skeleton for 3 seconds, then show table and pagination
            if (skeletonLoader && realDataTable && paginationContainer) {
                skeletonLoader.style.display = 'block';
                realDataTable.style.display = 'none';
                paginationContainer.style.display = 'none';
                setTimeout(function () {
                    skeletonLoader.style.display = 'none';
                    realDataTable.style.display = '';
                    paginationContainer.style.display = '';
                }, 3000);
            }
        });
    </script>
    <script>
        // Unique ID dropdown logic
        const input = document.getElementById('uniqueInput');
        const optionsList = document.getElementById('optionsList');
        const searchBox = document.getElementById('searchBox');
        const hiddenInput = document.getElementById('selectedUniqueId');
        const optionItems = optionsList.querySelectorAll('.option-item');
        const uniqueNoResults = optionsList.querySelector('.no-results');
        input.addEventListener('click', () => {
            optionsList.classList.toggle('show');
            searchBox.value = '';
            searchBox.focus();
            filterOptions('');
        });
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.custom-select-wrapper')) {
                optionsList.classList.remove('show');
            }
        });
        optionItems.forEach(item => {
            item.addEventListener('click', () => {
                input.value = item.textContent;
                hiddenInput.value = item.dataset.value;
                optionsList.classList.remove('show');
            });
        });
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
        window.addEventListener('DOMContentLoaded', () => {
            const selectedVal = hiddenInput.value;
            if (selectedVal) {
                const selected = [...optionItems].find(item => item.dataset.value === selectedVal);
                if (selected) {
                    input.value = selected.textContent;
                }
            }
        });

        // Member Name dropdown logic (FIXED)
        (function () {
            const memberInput = document.getElementById('memberInput');
            const memberOptions = document.getElementById('memberOptions');
            const memberSearch = document.getElementById('memberSearch');
            const selectedMember = document.getElementById('selectedMember');
            const memberNoResults = memberOptions.querySelector('.no-results');

            // Use event delegation for dynamic filtering
            function getMemberItems() {
                return memberOptions.querySelectorAll('.option-item-member');
            }

            // Show/hide dropdown
            memberInput.addEventListener('click', function (e) {
                e.stopPropagation();
                memberOptions.classList.toggle('show');
                memberSearch.value = '';
                filterMemberOptions('');
                setTimeout(() => memberSearch.focus(), 10);
            });

            // Hide dropdown when clicking outside
            document.addEventListener('click', function (e) {
                if (!e.target.closest('.custom-select-wrapper')) {
                    memberOptions.classList.remove('show');
                }
            });

            // Select member
            memberOptions.addEventListener('click', function (e) {
                const item = e.target.closest('.option-item-member');
                if (item) {
                    memberInput.value = item.textContent;
                    selectedMember.value = item.dataset.value;
                    memberOptions.classList.remove('show');
                }
            });

            // Filter on search
            memberSearch.addEventListener('input', function () {
                const filter = memberSearch.value.toLowerCase();
                filterMemberOptions(filter);
            });

            function filterMemberOptions(filter) {
                let anyVisible = false;
                const items = getMemberItems();
                items.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    const show = text.includes(filter);
                    item.style.display = show ? '' : 'none';
                    if (show) anyVisible = true;
                });
                if (memberNoResults) {
                    memberNoResults.style.display = anyVisible ? 'none' : '';
                }
            }

            // Set input value if already selected
            window.addEventListener('DOMContentLoaded', () => {
                const selectedVal = selectedMember.value;
                if (selectedVal) {
                    const items = getMemberItems();
                    const selected = [...items].find(item => item.dataset.value === selectedVal);
                    if (selected) {
                        memberInput.value = selected.textContent;
                    }
                }
            });
        })();
    </script>
@endsection
