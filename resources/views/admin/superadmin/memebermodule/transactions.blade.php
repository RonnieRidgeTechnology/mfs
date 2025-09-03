@extends('layouts.admin')
<style>
    /* Enhanced Transaction Table Styles */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        font-size: 1em;
        margin-top: 20px;
    }

    .data-table th,
    .data-table td {
        padding: 18px 24px;
        text-align: left;
        border-bottom: 1px solid #f1f5f9;
    }

    .data-table th {
        background: linear-gradient(135deg, #f8fafc 0%, #e0e7ef 100%);
        color: #334155;
        font-weight: 700;
        font-size: 1.02em;
        letter-spacing: 0.02em;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .data-table tr:last-child td {
        border-bottom: none;
    }

    .data-table tr:hover {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .flagged-row {
        background: linear-gradient(135deg, #fff6f6 0%, #fef2f2 100%) !important;
        border-left: 4px solid #ef4444;
    }

    .flagged-icon {
        color: #ef4444;
        margin-right: 6px;
        font-size: 1.1em;
        vertical-align: middle;
    }

    .flagged-list-item {
        color: #ef4444;
    }

    .flagged-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #b91c1c;
        border-radius: 8px;
        padding: 0.25em 0.8em;
        font-size: 0.9em;
        font-weight: 600;
        margin-left: 8px;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.15);
    }

    .status-badge {
        display: inline-block;
        padding: 0.4em 1.2em;
        border-radius: 8px;
        font-size: 0.95em;
        font-weight: 600;
        background: #f1f5f9;
        color: #64748b;
        position: relative;
        z-index: 1;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .status-badge.active {
        background: linear-gradient(135deg, #e0f7fa 0%, #b2f5ea 100%);
        color: #059669;
        box-shadow: 0 2px 8px rgba(5, 150, 105, 0.15);
    }

    .status-badge.inactive {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #b45309;
        box-shadow: 0 2px 8px rgba(180, 83, 9, 0.15);
    }

    .flagged-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #b91c1c;
        border-radius: 8px;
        padding: 0.4em 1em;
        font-size: 0.95em;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.15);
    }

    .guest-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, #eef2ff 0%, #dbeafe 100%);
        color: #6366f1;
        border-radius: 8px;
        padding: 0.4em 1em;
        font-size: 0.95em;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.15);
    }

    .animated-stripes {
        position: absolute;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        background: repeating-linear-gradient(135deg,
                rgba(99, 102, 241, 0.08) 0px,
                rgba(99, 102, 241, 0.08) 8px,
                transparent 8px,
                transparent 16px);
        z-index: 0;
        pointer-events: none;
        animation: stripes-move 1.2s linear infinite;
    }

    @keyframes stripes-move {
        0% {
            background-position: 0 0;
        }

        100% {
            background-position: 32px 0;
        }
    }

    .employee-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .employee-info img {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        object-fit: cover;
        background: #f1f5f9;
        border: 2px solid #e5e7eb;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .employee-info h4 {
        margin: 0 0 4px 0;
        font-size: 1.02em;
        font-weight: 600;
        color: #334155;
    }

    .employee-info span {
        font-size: 0.95em;
        color: #64748b;
    }

    .progress-bar-container {
        width: 100%;
        max-width: 140px;
        margin: 0 auto;
    }

    .progress-bar-outer {
        width: 100%;
        height: 16px;
        background: #f1f5f9;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .progress-bar-inner {
        height: 100%;
        border-radius: 10px;
        transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .progress-bar-blue {
        background: linear-gradient(90deg, #6366f1 0%, #60a5fa 100%);
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
    }

    .progress-bar-green {
        background: linear-gradient(90deg, #22c55e 0%, #4ade80 100%);
        box-shadow: 0 2px 8px rgba(34, 197, 94, 0.3);
    }

    /* Alphabet Filter Container */
    .alphabet-container {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 20px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
    }

    .alphabet-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f1f5f9;
    }

    .alphabet-title {
        font-size: 1.4em;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alphabet-title i {
        color: #6366f1;
        font-size: 1.2em;
    }

    .alphabet-description {
        color: #64748b;
        font-size: 0.95em;
        max-width: 600px;
        line-height: 1.5;
    }

    .alphabet-reset-btn {
        background: linear-gradient(135deg, #6366f1 0%, #60a5fa 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.8em 1.5em;
        font-weight: 600;
        font-size: 0.95em;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .alphabet-reset-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
    }

    .alphabet-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(40px, 1fr));
        gap: 8px;
        max-width: 800px;
    }

    .alphabet-btn {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        color: #64748b;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 8px;
        font-weight: 700;
        font-size: 1.1em;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        min-width: 40px;
        position: relative;
        overflow: hidden;
    }

    .alphabet-btn:hover {
        background: linear-gradient(135deg, #eef2ff 0%, #dbeafe 100%);
        border-color: #6366f1;
        color: #6366f1;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.2);
    }

    .alphabet-btn.active {
        background: linear-gradient(135deg, #6366f1 0%, #60a5fa 100%);
        color: white;
        border-color: #6366f1;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
    }

    .alphabet-btn.active:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.5);
    }

    /* Skeleton Preloader Styles */
    .skeleton-container {
        display: none;
        margin: 20px 0;
    }

    .skeleton-table {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
    }

    .skeleton-header {
        background: linear-gradient(135deg, #f8fafc 0%, #e0e7ef 100%);
        padding: 18px 24px;
        border-bottom: 1px solid #f1f5f9;
    }

    .skeleton-header-line {
        height: 20px;
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 8px;
        margin-bottom: 8px;
    }

    .skeleton-row {
        display: flex;
        align-items: center;
        padding: 18px 24px;
        border-bottom: 1px solid #f1f5f9;
    }

    .skeleton-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        margin-right: 15px;
    }

    .skeleton-content {
        flex: 1;
    }

    .skeleton-line {
        height: 14px;
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 6px;
        margin-bottom: 6px;
    }

    .skeleton-line.short {
        width: 60%;
    }

    .skeleton-line.medium {
        width: 80%;
    }

    .skeleton-line.long {
        width: 90%;
    }

    .skeleton-badge {
        width: 80px;
        height: 32px;
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 8px;
        margin-left: 15px;
    }

    .skeleton-progress {
        width: 140px;
        height: 16px;
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 10px;
        margin: 0 auto;
    }

    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }

        100% {
            background-position: 200% 0;
        }
    }

    /* Enhanced Filter Styles */
    .sleek-filter-container {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
    }

    .sleek-filter-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        font-size: 1.3em;
        font-weight: 700;
        color: #1e293b;
    }

    .sleek-filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        align-items: end;
    }

    .sleek-filter-group {
        display: flex;
        flex-direction: column;
    }

    .sleek-filter-label {
        font-size: 0.95em;
        color: #64748b;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .sleek-filter-input {
        padding: 12px 16px;
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        background: #fff;
        font-size: 1em;
        color: #334155;
        transition: all 0.3s ease;
        outline: none;
    }

    .sleek-filter-input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .sleek-filter-btns {
        display: flex;
        gap: 10px;
        align-items: end;
    }

    .filter-btn {
        background: linear-gradient(135deg, #6366f1 0%, #60a5fa 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
    }

    .reset-btn {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        color: #64748b;
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .reset-btn:hover {
        background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
        transform: translateY(-2px);
    }

    /* Custom Select Dropdown Styles */
    .custom-select-input {
        width: 100%;
        padding: 12px 16px;
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        background: #fff;
        font-size: 1em;
        color: #334155;
        transition: all 0.3s ease;
        outline: none;
        cursor: pointer;
    }

    .custom-select-input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .custom-select-wrapper {
        position: relative;
        width: 100%;
    }

    .custom-options {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: #fff;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        max-height: 250px;
        overflow-y: auto;
        margin-top: 4px;
    }

    .custom-options input[type="text"] {
        width: calc(100% - 20px);
        margin: 10px;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        font-size: 0.9em;
        color: #334155;
        background: #f8fafc;
    }

    .option-item,
    .option-item-member {
        padding: 10px 15px;
        cursor: pointer;
        font-size: 0.95em;
        color: #334155;
        border-radius: 6px;
        margin: 2px 8px;
        transition: all 0.2s ease;
    }

    .option-item:hover,
    .option-item-member:hover {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        color: #6366f1;
        transform: translateX(2px);
    }

    .no-results {
        color: #ef4444;
        font-size: 0.9em;
        padding: 10px 15px;
        text-align: center;
        font-style: italic;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .alphabet-grid {
            grid-template-columns: repeat(auto-fit, minmax(35px, 1fr));
            gap: 6px;
        }

        .alphabet-btn {
            padding: 10px 6px;
            font-size: 1em;
            min-width: 35px;
        }

        .sleek-filter-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .data-table th,
        .data-table td {
            padding: 12px 16px;
        }

        .employee-info img {
            width: 35px;
            height: 35px;
        }
    }

    /* Loading States */
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }

    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #6366f1;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

@section('content')
    <main class="main-content">
        @include('layouts.header')
        <div class="content">
            <!-- Quick Action Container -->
            <div class="quick-action-container" style="background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 100%); border-radius: 16px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 20px rgba(34,197,94,0.08); border: 1px solid #bbf7d0;">
                <div style="margin: 18px 0 8px 0;">
                    <div class="quick-action-header" style="font-weight: 700; color: #22c55e; margin-bottom: 4px; display: flex; align-items: center;">
                        <i class="fa-solid fa-user" style="margin-right: 6px; color: #22c55e;"></i>
                        Welcome, <span style="margin-left: 6px;">{{ Auth::user()->name ?? 'N/A' }}</span>
                    </div>
                    <p style="color: #475569; font-size: 0.85em; margin-bottom: 7px;">
                        Here you can view your transactions.
                    </p>
                </div>
            </div>
            <div class="sleek-filter-container">
                <div style="margin: 18px 0 8px 0;">
                    <div class="sleek-filter-header">
                        <i class="fa-solid fa-sliders"></i>
                        Advanced Transactions Filter
                    </div>
                    <p style="color: #475569; font-size: 0.85em; margin-bottom: 7px;">
                        Use the filters below to find flagged transactions by date, type, year, or month.
                    </p>
                </div>
                <form method="GET" action="{{ route('member.my_transactions') }}" id="filterForm" autocomplete="off">
                    <div class="sleek-filter-grid">
                        <div class="sleek-filter-group" style="min-width: 120px;">
                            <label class="sleek-filter-label">Type</label>
                            <select id="type" name="type" class="sleek-filter-input">
                                <option value="">All</option>
                                <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Debit</option>
                                <option value="cash" {{ request('type') == 'cash' ? 'selected' : '' }}>Credit</option>
                            </select>
                        </div>
                        <div class="sleek-filter-group" style="min-width: 120px;">
                            <label class="sleek-filter-label">Start</label>
                            <input type="date" id="start_date" name="start_date" class="sleek-filter-input"
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="sleek-filter-group" style="min-width: 120px;">
                            <label class="sleek-filter-label">End</label>
                            <input type="date" id="end_date" name="end_date" class="sleek-filter-input"
                                value="{{ request('end_date') }}">
                        </div>
                        <div class="sleek-filter-group" style="min-width: 100px;">
                            <label class="sleek-filter-label">Year</label>
                            <select id="year" name="year" class="sleek-filter-input">
                                <option value="">All</option>
                                @php
                                    $currentYear = date('Y');
                                @endphp
                                @for($y = $currentYear; $y >= 2000; $y--)
                                    <option value="{{ $y }}" {{ (request('year') == $y) ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="sleek-filter-group" style="min-width: 100px;">
                            <label class="sleek-filter-label">Month</label>
                            <select id="month" name="month" class="sleek-filter-input">
                                <option value="">All</option>
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ (request('month') == $m) ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <input type="hidden" name="flag_status" value="0">
                        <div class="sleek-filter-group" style="flex: 0 0 auto; min-width: 120px;">
                            <div class="sleek-filter-btns">
                                <button type="submit" class="filter-btn">
                                    <i class="fa-solid fa-filter"></i>
                                    Apply
                                </button>
                                <a href="{{ route('member.my_transactions') }}" class="reset-btn">
                                    <i class="fa-solid fa-rotate"></i>
                                    Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Skeleton Preloader -->
            <div class="skeleton-container" id="skeletonContainer">
                <div class="skeleton-table">
                    <div class="skeleton-header">
                        <div class="skeleton-header-line"></div>
                    </div>
                    @for($i = 0; $i < 8; $i++)
                        <div class="skeleton-row">
                            <div class="skeleton-avatar"></div>
                            <div class="skeleton-content">
                                <div class="skeleton-line medium"></div>
                                <div class="skeleton-line short"></div>
                            </div>
                            <div class="skeleton-badge"></div>
                            <div class="skeleton-badge"></div>
                            <div class="skeleton-badge"></div>
                            <div class="skeleton-progress"></div>
                            <div class="skeleton-badge"></div>
                        </div>
                    @endfor
                </div>
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
                                <input type="checkbox" id="showMemberId" checked onchange="toggleColumn('member-id')"
                                    style="margin: 0;">
                                Member ID
                            </span>
                            <span class="badge"
                                style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                <input type="checkbox" id="showName" checked onchange="toggleColumn('name')"
                                    style="margin: 0;">
                                Name
                            </span>
                            <span class="badge"
                                style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                <input type="checkbox" id="showPhone" checked onchange="toggleColumn('phone')"
                                    style="margin: 0;">
                                Phone
                            </span>
                            <span class="badge"
                                style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                <input type="checkbox" id="showAddress" checked onchange="toggleColumn('address')"
                                    style="margin: 0;">
                                Address
                            </span>
                            <span class="badge"
                                style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                <input type="checkbox" id="showAccount" checked onchange="toggleColumn('account')"
                                    style="margin: 0;">
                                Account
                            </span>
                            <span class="badge"
                                style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                <input type="checkbox" id="showStatus" checked onchange="toggleColumn('status')"
                                    style="margin: 0;">
                                Status
                            </span>
                            <span class="badge"
                                style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                <input type="checkbox" id="showDate" checked onchange="toggleColumn('date')"
                                    style="margin: 0;">
                                Date
                            </span>
                            <span class="badge"
                                style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                <input type="checkbox" id="showProgress" checked onchange="toggleColumn('progress')"
                                    style="margin: 0;">
                                Progress
                            </span>
                            <span class="badge"
                                style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                <input type="checkbox" id="showAmount" checked onchange="toggleColumn('amount')"
                                    style="margin: 0;">
                                Amount
                            </span>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div style="display: flex; gap: 10px;">
                        <button onclick="showAllColumns()" class="quick-action-btn"
                            style="background: linear-gradient(135deg, #6366f1 0%, #60a5fa 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.9em; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                            <i class="fa-solid fa-eye" style="margin-right: 5px;"></i>
                            Show All
                        </button>
                        <button onclick="hideAllColumns()" class="quick-action-btn"
                            style="background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); color: #64748b; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.9em; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                            <i class="fa-solid fa-eye-slash" style="margin-right: 5px;"></i>
                            Hide All
                        </button>

                    </div>
                </div>
            </div>

            <!-- Enhanced Table Section -->
            <div id="tableSection">
                <div class="table-container">
                    <table class="data-table" id="transactionTable">
                        <thead>
                            <tr>
                                <th>Member ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Account</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Progress</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody id="transactionTableBody">
                            @if($transactions->isEmpty())
                                <tr>
                                    <td colspan="9" style="text-align: center; padding: 40px; color: #64748b;">
                                        <i class="fa-solid fa-inbox"
                                            style="font-size: 3em; color: #cbd5e1; margin-bottom: 16px; display: block;"></i>
                                        <div style="font-size: 1.1em; margin-bottom: 8px;">No transactions found</div>
                                        <div style="font-size: 0.9em;">Try adjusting your filters or import new transactions
                                        </div>
                                    </td>
                                </tr>
                            @else
                                @php
                                    $selectedYear = request('year');
                                    $annualFeeSettings = \App\Models\MembershipFeeSetting::where('member_type', 'annual_fee')->get()->keyBy('year');
                                @endphp
                                @foreach($transactions as $transaction)
                                    @php
                                        $feeYear = $selectedYear;
                                        if (!$feeYear && !empty($transaction->date)) {
                                            try {
                                                $feeYear = \Carbon\Carbon::parse($transaction->date)->format('Y');
                                            } catch (\Exception $e) {
                                                $feeYear = null;
                                            }
                                        }
                                        $annualFeeAmount = 80;
                                        if ($feeYear && isset($annualFeeSettings[$feeYear])) {
                                            $annualFeeAmount = (float) $annualFeeSettings[$feeYear]->amount;
                                        } elseif ($annualFeeSettings->count() > 0) {
                                            $annualFeeAmount = (float) $annualFeeSettings->sortByDesc('year')->first()->amount;
                                        }
                                        $amount = (float) $transaction->amount;
                                        $progress = min(max($amount, 0), $annualFeeAmount);
                                        $progressPercent = $annualFeeAmount > 0 ? ($progress / $annualFeeAmount) * 100 : 0;
                                        $isFull = $progress == $annualFeeAmount;
                                    @endphp
                                    <tr @if(isset($transaction->flag_status) && $transaction->flag_status === 0) class="flagged-row"
                                    @endif>
                                        <td>
                                            @if(isset($transaction->flag_status) && $transaction->flag_status === 0)
                                                <i class="fa fa-flag flagged-icon" title="Flagged"></i>
                                            @endif
                                            @if($transaction->user->is_guest == 1)
                                                <i class="fa fa-user-clock guest-icon" title="Guest User"
                                                    style="color: #6366f1; margin-right: 6px; font-size: 1.1em; vertical-align: middle;"></i>
                                            @endif
                                            {{ $transaction->user->unique_id }}
                                        </td>
                                        <td>
                                            <a href="{{ route('member.transactions.detail', ['name' => str_replace(' ', '-', $transaction->user->name), 'unique_id' => $transaction->user->unique_id]) }}"
                                                style="text-decoration: none; color: inherit;">
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
                                                        <div style="font-weight: 600; color: #333;">{{ $transaction->user->postal_code }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <span style="color: #999; font-style: italic; font-size: 0.9em;">No address</span>
                                            @endif
                                        </td>
                                        <td>{{ $transaction->account ?? '-' }}</td>
                                        <td>
                                            @if(isset($transaction->flag_status) && $transaction->flag_status === 0)
                                                <span class="flagged-status">
                                                    <i class="fa fa-flag"></i> Flagged
                                                </span>
                                            @elseif($transaction->user->is_guest == 1)
                                                <span class="guest-status">
                                                    <i class="fa fa-user-clock"></i> Guest
                                                </span>
                                            @else
                                                <span
                                                    class="status-badge {{ strtolower($transaction->status) == 'cash' ? 'active' : 'inactive' }}"
                                                    style="position: relative; overflow: hidden;">
                                                    {{ strtolower($transaction->status) == 'cash' ? 'credit' : $transaction->status }}
                                                    <span class="animated-stripes"></span>
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="activity-info">
                                                <span>{{ $transaction->date }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="progress-bar-container">
                                                <div class="progress-bar-outer" title="Amount: £{{ $transaction->amount }}">
                                                    <div class="progress-bar-inner {{ $isFull ? 'progress-bar-green' : 'progress-bar-blue' }}"
                                                        style="width: {{ $progressPercent }}%;">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span>£{{ $transaction->amount }}/£{{ number_format($annualFeeAmount, 2) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div id="pagination-container">
                        @include('layouts.custom_pagination', ['paginator' => $transactions])
                    </div>
                </div>
            </div>
        </div>
    </main>


@endsection
@section('script')
    <script>
        // Column visibility control functions
        function toggleColumn(columnType) {
            const table = document.getElementById('transactionTable');
            const headerRow = table.querySelector('thead tr');
            const dataRows = table.querySelectorAll('tbody tr');

            let columnIndex = -1;

            // Determine column index based on type
            switch (columnType) {
                case 'member-id':
                    columnIndex = 0;
                    break;
                case 'name':
                    columnIndex = 1;
                    break;
                case 'phone':
                    columnIndex = 2;
                    break;
                case 'address':
                    columnIndex = 3;
                    break;
                case 'account':
                    columnIndex = 4;
                    break;
                case 'status':
                    columnIndex = 5;
                    break;
                case 'date':
                    columnIndex = 6;
                    break;
                case 'progress':
                    columnIndex = 7;
                    break;
                case 'amount':
                    columnIndex = 8;
                    break;
            }

            if (columnIndex === -1) return;

            // Toggle header
            const headerCell = headerRow.cells[columnIndex];
            headerCell.style.display = headerCell.style.display === 'none' ? '' : 'none';

            // Toggle data cells
            dataRows.forEach(row => {
                const cell = row.cells[columnIndex];
                if (cell) {
                    cell.style.display = cell.style.display === 'none' ? '' : 'none';
                }
            });
        }

        function showAllColumns() {
            const checkboxes = document.querySelectorAll('.column-controls input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });

            const table = document.getElementById('transactionTable');
            const allCells = table.querySelectorAll('th, td');
            allCells.forEach(cell => {
                cell.style.display = '';
            });
        }

        function hideAllColumns() {
            const checkboxes = document.querySelectorAll('.column-controls input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });

            const table = document.getElementById('transactionTable');
            const allCells = table.querySelectorAll('th, td');
            allCells.forEach(cell => {
                cell.style.display = 'none';
            });
        }

        // Initialize column visibility based on checkboxes
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('.column-controls input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                const columnType = checkbox.id.replace('show', '').toLowerCase();
                if (!checkbox.checked) {
                    toggleColumn(columnType);
                }
            });
        });
    </script>
@endsection
