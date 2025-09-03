@extends('layouts.admin')

@section('content')
    <main class="main-content">
        @include('layouts.header')
        <div class="content">
            <div class="table-section">
                <!-- Header Section -->
                <div class="table-header">
                    <div class="debit-header-container" style="width: 100%;">
                        <div style="display: flex;align-items: center;justify-content: space-between;margin-bottom: 20px;">
                            <div>
                                <h2 style="color: #1f2937; font-size: 1.875rem; font-weight: 700; margin: 0;">Problem
                                    Transactions</h2>
                                <p style="color: #6b7280; margin: 8px 0 0 0; font-size: 1rem;">Manage flagged, duplicate, and
                                    no-ID transactions that require attention</p>
                            </div>
                            <div style="display: flex; gap: 16px; align-items: center;">
                                <div class="stat-card warning">
                                    <div class="stat-number">{{ $totalFlagged ?? 0 }}</div>
                                    <div class="stat-label">Flagged</div>
                                </div>
                                <div class="stat-card danger">
                                    <div class="stat-number">{{ $totalNoId ?? 0 }}</div>
                                    <div class="stat-label">No ID</div>
                                </div>
                                <div class="stat-card info">
                                    <div class="stat-number">{{ $totalAll ?? 0 }}</div>
                                    <div class="stat-label">Total Issues</div>
                                </div>
                                <div class="stat-card success">
                                    <div class="stat-number">£{{ number_format($totalAllAmount ?? 0, 2) }}</div>
                                    <div class="stat-label">Total Amount</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter Section -->
                <div class="search-filter-section">
                    <form method="GET" action="{{ route('admin.transactions.flagged.view') }}" class="search-form">
                        <div class="search-controls">
                            <div class="search-input-group">
                                <input type="text" name="search" value="{{ $currentSearch ?? '' }}"
                                    placeholder="Search by name, email, unique ID, account, amount..." class="search-input">
                                <button type="submit" class="search-btn">
                                    <i class="fa-solid fa-search"></i>
                                </button>
                            </div>

                            <div class="filter-group">
                                <select name="filter" class="filter-select">
                                    <option value="all" {{ ($currentFilter ?? 'all') == 'all' ? 'selected' : '' }}>All
                                        Issues</option>
                                    <option value="flagged" {{ ($currentFilter ?? '') == 'flagged' ? 'selected' : '' }}>
                                        Flagged Only</option>
                                    <option value="no_id" {{ ($currentFilter ?? '') == 'no_id' ? 'selected' : '' }}>No ID
                                        Only</option>
                                </select>

                                <select name="per_page" class="filter-select">
                                    <option value="10" {{ ($currentPerPage ?? 10) == 10 ? 'selected' : '' }}>10 per page
                                    </option>
                                    <option value="25" {{ ($currentPerPage ?? 10) == 25 ? 'selected' : '' }}>25 per
                                        page</option>
                                    <option value="50" {{ ($currentPerPage ?? 10) == 50 ? 'selected' : '' }}>50 per
                                        page</option>
                                    <option value="100" {{ ($currentPerPage ?? 10) == 100 ? 'selected' : '' }}>100 per
                                        page</option>
                                </select>
                            </div>

                            @if (($currentSearch ?? '') || ($currentFilter ?? 'all') != 'all')
                                <a href="{{ route('admin.transactions.flagged.view') }}" class="clear-filters-btn">
                                    <i class="fa-solid fa-times"></i>
                                    Clear Filters
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Table Section -->
                <div class="table-container modern-table">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Member ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($transactions) && $transactions->count() > 0)
                                @foreach ($transactions as $transaction)
                                    @php
                                        $isNoId = $transaction->no_id_flaged == 0;
                                        $isFlagged = $transaction->flag_status == 0;
                                        $transactionType = $isNoId ? 'no-id' : 'flagged';
                                    @endphp
                                    <tr data-transaction-id="{{ $transaction->id }}"
                                        class="table-row {{ $transactionType }}-row">
                                        <td>
                                            @if ($isNoId)
                                                <span class="type-badge no-id-badge">
                                                    <i class="fa-solid fa-user-slash"></i>
                                                    No ID
                                                </span>
                                            @else
                                                <span class="type-badge flagged-badge">
                                                    <i class="fa-solid fa-flag"></i>
                                                    Flagged
                                                </span>
                                            @endif
                                        </td>
                                        <td class="member-id">
                                            @if ($isNoId)
                                                <span class="no-id-text">No ID</span>
                                            @else
                                                {{ $transaction->user->unique_id ?? 'N/A' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($isNoId)
                                                <div class="transaction-name-display">
                                                    {{ $transaction->name ?? 'Unknown' }}
                                                </div>
                                            @else
                                                <div class="employee-info">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($transaction->user->name ?? 'Unknown') }}&background=6366f1&color=ffffff"
                                                        alt="{{ $transaction->user->name ?? 'Unknown' }}"
                                                        class="user-avatar">
                                                    <div>
                                                        <h4>{{ $transaction->user->name ?? 'Unknown User' }}</h4>
                                                        <span class="user-role">Member</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="email-cell">
                                            @if ($isNoId)
                                                <span class="no-data">No Email</span>
                                            @else
                                                {{ $transaction->user->email ?? 'N/A' }}
                                            @endif
                                        </td>
                                        <td class="date-cell">
                                            {{ \Carbon\Carbon::parse($transaction->date)->format('M d, Y') }}</td>
                                        <td class="amount-cell">£{{ number_format($transaction->amount, 2) }}</td>
                                        <td>
                                            @if ($isNoId)
                                                <span class="status-badge no-id-status">
                                                    <i class="fa-solid fa-user-slash"></i>
                                                    No ID Found
                                                </span>
                                            @else
                                                <span class="status-badge flagged-status">
                                                    <i class="fa-solid fa-flag"></i>
                                                    Duplicate
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($isNoId)
                                                <button class="view-btn no-id-btn"
                                                    onclick="showNoIdModal({{ $transaction->id }}, '{{ addslashes($transaction->name ?? 'Unknown') }}', '{{ $transaction->date }}', '{{ $transaction->amount }}', '{{ addslashes($transaction->account ?? 'N/A') }}')">
                                                    <i class="fa-solid fa-cog"></i>
                                                    Manage
                                                </button>
                                            @else
                                                <button class="view-btn flagged-btn"
                                                    onclick="showFlaggedModal({{ $transaction->id }}, '{{ addslashes($transaction->user->name ?? 'Unknown User') }}', '{{ $transaction->user->unique_id ?? 'N/A' }}', '{{ $transaction->date }}', '{{ $transaction->amount }}', '{{ addslashes($transaction->user->email ?? 'N/A') }}')">
                                                    <i class="fa-solid fa-eye"></i>
                                                    View
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8" class="empty-state-cell">
                                        <div class="empty-state-content">
                                            <i class="fa-solid fa-search empty-icon"></i>
                                            <div class="empty-title">No transactions found</div>
                                            <div class="empty-subtitle">
                                                @if ($currentSearch ?? '')
                                                    No transactions match your search criteria.
                                                @else
                                                    No problem transactions found with the current filter.
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                  
                </div>
                   @if(isset($transactions) && $transactions->hasPages())
                        <div id="pagination-container" class="pagination-container">
                            @include('layouts.custom_pagination', ['paginator' => $transactions])
                        </div>
                    @endif

                <!-- Pagination -->

            </div>
        </div>

        <!-- Flagged Transaction Modal -->
        <div id="flaggedModal" class="modal-overlay">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        <i class="fa-solid fa-flag"></i>
                        Flagged Transaction Details
                    </h3>
                    <button onclick="closeFlaggedModal()" class="modal-close">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <label>User Name</label>
                            <span id="modalUserName"></span>
                        </div>
                        <div class="info-item">
                            <label>Unique ID</label>
                            <span id="modalUniqueId"></span>
                        </div>
                        <div class="info-item">
                            <label>Email</label>
                            <span id="modalEmail"></span>
                        </div>
                        <div class="info-item">
                            <label>Date</label>
                            <span id="modalDate"></span>
                        </div>
                        <div class="info-item">
                            <label>Amount</label>
                            <span id="modalAmount" class="amount-highlight"></span>
                        </div>
                    </div>
                    <div class="warning-box">
                        <div class="warning-header">
                            <i class="fa-solid fa-exclamation-triangle"></i>
                            <span>Reason for Flagging</span>
                        </div>
                        <p>This transaction has been flagged because a duplicate transaction already exists for this user on
                            the same date.</p>
                    </div>
                </div>
                <div class="modal-actions">
                    <button onclick="closeFlaggedModal()" class="btn btn-secondary">
                        <i class="fa-solid fa-times"></i>
                        Cancel
                    </button>
                    <button onclick="showConfirmModal('ignore')" class="btn btn-danger">
                        <i class="fa-solid fa-trash"></i>
                        Ignore & Delete
                    </button>
                    <button onclick="showConfirmModal('accept')" class="btn btn-success">
                        <i class="fa-solid fa-check"></i>
                        Accept Transaction
                    </button>
                </div>
            </div>
        </div>

        <!-- No ID Transaction Modal -->
        <div id="noIdModal" class="modal-overlay">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        <i class="fa-solid fa-user-slash"></i>
                        No ID Transaction Management
                    </h3>
                    <button onclick="closeNoIdModal()" class="modal-close">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Transaction Name</label>
                            <span id="noIdModalName" class="transaction-name-full"></span>
                        </div>
                        <div class="info-item">
                            <label>Date</label>
                            <span id="noIdModalDate"></span>
                        </div>
                        <div class="info-item">
                            <label>Amount</label>
                            <span id="noIdModalAmount" class="amount-highlight"></span>
                        </div>
                        <div class="info-item">
                            <label>Account</label>
                            <span id="noIdModalAccount"></span>
                        </div>
                    </div>
                    <div class="warning-box">
                        <div class="warning-header">
                            <i class="fa-solid fa-exclamation-triangle"></i>
                            <span>No User ID Found</span>
                        </div>
                        <p>This transaction could not be matched to any existing member. You can assign it to a member or
                            ignore it.</p>
                    </div>
                </div>
                <div class="modal-actions">
                    <button onclick="closeNoIdModal()" class="btn btn-secondary">
                        <i class="fa-solid fa-times"></i>
                        Cancel
                    </button>
                    <button onclick="showConfirmModal('ignore_no_id')" class="btn btn-danger">
                        <i class="fa-solid fa-trash"></i>
                        Ignore & Delete
                    </button>
                    <button onclick="showAssignModal()" class="btn btn-success">
                        <i class="fa-solid fa-user-plus"></i>
                        Assign to Member
                    </button>
                </div>
            </div>
        </div>

        <!-- Assign Member Modal -->
        <div id="assignMemberModal" class="modal-overlay">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        <i class="fa-solid fa-user-plus"></i>
                        Assign Transaction to Member
                    </h3>
                    <button onclick="closeAssignModal()" class="modal-close">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="assign-transaction-info">
                        <div class="info-item">
                            <label>Transaction Name</label>
                            <span id="assignModalName" class="transaction-name-full"></span>
                        </div>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Date</label>
                                <span id="assignModalDate"></span>
                            </div>
                            <div class="info-item">
                                <label>Amount</label>
                                <span id="assignModalAmount" class="amount-highlight"></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            Search and Select Member
                        </label>
                        <div class="searchable-dropdown">
                            <input type="text" id="memberSearchInput" class="form-input"
                                placeholder="Type to search members by name, email, or ID..." autocomplete="off">
                            <div class="dropdown-list" id="memberDropdownList" style="display: none;">
                                <!-- Members will be populated here -->
                            </div>
                            <input type="hidden" id="selectedMemberId" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-actions">
                    <button onclick="closeAssignModal()" class="btn btn-secondary">
                        <i class="fa-solid fa-times"></i>
                        Cancel
                    </button>
                    <button onclick="assignToSelectedMember()" class="btn btn-success">
                        <i class="fa-solid fa-link"></i>
                        Assign to Selected Member
                    </button>
                </div>
            </div>
        </div>

        <!-- Confirm Modal -->
        <div id="customConfirmModal" class="modal-overlay">
            <div class="modal-content confirm-modal">
                <div class="modal-header">
                    <h3 class="modal-title">
                        <i class="fa-solid fa-question-circle"></i>
                        Confirm Action
                    </h3>
                    <button onclick="closeConfirmModal()" class="modal-close">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="confirm-content">
                        <div class="confirm-icon">
                            <i class="fa-solid fa-exclamation-triangle"></i>
                        </div>
                        <p id="confirmModalText">Are you sure you want to proceed?</p>
                    </div>
                </div>
                <div class="modal-actions">
                    <button onclick="closeConfirmModal()" class="btn btn-secondary">Cancel</button>
                    <button id="confirmModalOkBtn" class="btn btn-danger">Confirm</button>
                </div>
            </div>
        </div>
    </main>

    <style>
        /* Enhanced Modern Styles for Problem Transactions */
        .stat-card {
            padding: 16px 20px;
            border-radius: 12px;
            text-align: center;
            min-width: 140px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .stat-card.warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fbbf24 100%);
            border-left: 4px solid #f59e0b;
        }

        .stat-card.danger {
            background: linear-gradient(135deg, #fee2e2 0%, #ef4444 100%);
            border-left: 4px solid #dc2626;
        }

        .stat-card.info {
            background: linear-gradient(135deg, #dbeafe 0%, #3b82f6 100%);
            border-left: 4px solid #2563eb;
        }

        .stat-card.success {
            background: linear-gradient(135deg, #ecfdf5 0%, #10b981 100%);
            border-left: 4px solid #059669;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 4px;
            color: #1f2937;
        }

        .stat-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #6b7280;
        }

        /* Search and Filter Styles */
        .search-filter-section {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            border: 1px solid #e2e8f0;
        }

        .search-controls {
            display: flex;
            gap: 16px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-input-group {
            display: flex;
            flex: 1;
            min-width: 300px;
        }

        .search-input {
            flex: 1;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px 0 0 8px;
            font-size: 0.875rem;
            transition: border-color 0.2s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #6366f1;
        }

        .search-btn {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            border: none;
            padding: 12px 16px;
            border-radius: 0 8px 8px 0;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .search-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }

        .filter-group {
            display: flex;
            gap: 12px;
        }

        .filter-select {
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.875rem;
            background: white;
            cursor: pointer;
            transition: border-color 0.2s ease;
        }

        .filter-select:focus {
            outline: none;
            border-color: #6366f1;
        }

        .clear-filters-btn {
            background: #ef4444;
            color: white;
            padding: 12px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .clear-filters-btn:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }

        /* Enhanced Table Styles */
        .modern-table {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .data-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .data-table thead th {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: #374151;
            font-weight: 600;
            padding: 16px 12px;
            text-align: left;
            border-bottom: 2px solid #e5e7eb;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table-row {
            transition: all 0.2s ease;
            border-bottom: 1px solid #f3f4f6;
        }

        .table-row:hover {
            background-color: #f9fafb;
            transform: scale(1.01);
        }

        .data-table td {
            padding: 16px 12px;
            vertical-align: middle;
            font-size: 0.875rem;
        }

        /* Type Badges */
        .type-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .no-id-badge {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .flagged-badge {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #d97706;
            border: 1px solid #fde68a;
        }

        /* Transaction Name Display */
        .transaction-name-display {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.9rem;
            font-family: 'Courier New', monospace;
            background: #f8fafc;
            padding: 8px 12px;
            border-radius: 6px;
            border-left: 3px solid #ef4444;
            white-space: normal;
            word-wrap: break-word;
            line-height: 1.3;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 12px;
            border: 2px solid #e5e7eb;
        }

        .employee-info {
            display: flex;
            align-items: center;
        }

        .employee-info h4 {
            margin: 0;
            font-size: 0.875rem;
            font-weight: 600;
            color: #1f2937;
        }

        .user-role {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 500;
        }

        .no-id-text {
            color: #ef4444;
            font-weight: 600;
            font-style: italic;
        }

        .no-data {
            color: #9ca3af;
            font-style: italic;
        }

        .amount-cell {
            font-weight: 600;
            color: #059669;
            font-size: 0.9rem;
        }

        .date-cell {
            color: #6b7280;
            font-weight: 500;
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .no-id-status {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .flagged-status {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #d97706;
            border: 1px solid #fde68a;
        }

        /* Action Buttons */
        .view-btn {
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .flagged-btn {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
        }

        .no-id-btn {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .view-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* Empty State */
        .empty-state-cell {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            font-size: 3rem;
            color: #6b7280;
            margin-bottom: 16px;
        }

        .empty-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .empty-subtitle {
            color: #6b7280;
            font-size: 0.875rem;
        }

        /* Pagination Styles */
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 24px;
            padding: 16px 0;
            border-top: 1px solid #e5e7eb;
        }

        .pagination-info {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .pagination-wrapper .pagination {
            margin: 0;
        }

        .pagination-wrapper .page-link {
            border: 1px solid #d1d5db;
            color: #374151;
            padding: 8px 12px;
            margin: 0 2px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .pagination-wrapper .page-link:hover {
            background-color: #f3f4f6;
            border-color: #9ca3af;
        }

        .pagination-wrapper .page-item.active .page-link {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border-color: #6366f1;
        }

        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .modal-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 20px 24px;
            border-bottom: 1px solid #e5e7eb;
            border-radius: 16px 16px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6b7280;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .modal-close:hover {
            background-color: #f3f4f6;
            color: #374151;
        }

        .modal-body {
            padding: 24px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 24px;
        }

        .info-item {
            background: #f9fafb;
            padding: 16px;
            border-radius: 8px;
            border-left: 4px solid #6366f1;
        }

        .info-item label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }

        .info-item span {
            font-size: 0.875rem;
            font-weight: 500;
            color: #1f2937;
        }

        .amount-highlight {
            font-size: 1rem !important;
            font-weight: 700 !important;
            color: #059669 !important;
        }

        .transaction-name-full {
            font-family: 'Courier New', monospace !important;
            background: #f1f5f9 !important;
            padding: 8px !important;
            border-radius: 4px !important;
            border-left: 3px solid #ef4444 !important;
            white-space: normal !important;
            word-wrap: break-word !important;
            line-height: 1.3 !important;
        }

        .warning-box {
            background: linear-gradient(135deg, #fef3c7 0%, #fbbf24 100%);
            border-radius: 8px;
            padding: 16px;
            border-left: 4px solid #f59e0b;
        }

        .warning-header {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #92400e;
            margin-bottom: 8px;
        }

        .warning-box p {
            color: #78350f;
            margin: 0;
            line-height: 1.5;
        }

        .modal-actions {
            padding: 20px 24px;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            border-radius: 0 0 16px 16px;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.875rem;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.4);
        }

        .confirm-modal {
            max-width: 450px;
        }

        .confirm-content {
            text-align: center;
            padding: 20px 0;
        }

        .confirm-icon {
            font-size: 3rem;
            color: #f59e0b;
            margin-bottom: 16px;
        }

        .confirm-content p {
            color: #374151;
            margin: 0;
            line-height: 1.5;
        }

        /* Assign Member Modal Styles - Matching Import Page */
        .assign-transaction-info {
            background: #f8fafc;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
            border-left: 4px solid #6366f1;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .searchable-dropdown {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            background: white;
            box-sizing: border-box;
        }

        .form-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .dropdown-list {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 0 0 8px 8px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item {
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            transition: background-color 0.2s ease;
        }

        .dropdown-item:hover,
        .dropdown-item.keyboard-focus {
            background-color: #f8fafc;
        }

        .dropdown-item:last-child {
            border-bottom: none;
        }

        .dropdown-item.selected {
            background-color: #eff6ff;
            border-left: 3px solid #6366f1;
        }

        .member-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 0.875rem;
            margin-bottom: 2px;
        }

        .member-details {
            font-size: 0.75rem;
            color: #6b7280;
        }

        .no-results {
            padding: 16px;
            text-align: center;
            color: #6b7280;
            font-style: italic;
        }

        .loading-members {
            padding: 16px;
            text-align: center;
            color: #6366f1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .loading-spinner {
            width: 16px;
            height: 16px;
            border: 2px solid #e2e8f0;
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

        /* Pagination Styles */
        .pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 24px;
            padding: 20px 0;
            border-top: 1px solid #e5e7eb;
            background: #f8fafc;
            border-radius: 0 0 12px 12px;
        }

        #pagination-container {
            margin-top: 24px;
        }

        /* Custom pagination styling */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .pagination .page-item {
            margin: 0;
        }

        .pagination .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 8px 12px;
            border: 2px solid #e2e8f0;
            background: white;
            color: #374151;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .pagination .page-link:hover {
            background: #f8fafc;
            border-color: #6366f1;
            color: #6366f1;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(99, 102, 241, 0.2);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border-color: #6366f1;
            color: white;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .pagination .page-item.disabled .page-link {
            background: #f3f4f6;
            border-color: #e5e7eb;
            color: #9ca3af;
            cursor: not-allowed;
        }

        .pagination .page-item.disabled .page-link:hover {
            transform: none;
            box-shadow: none;
            background: #f3f4f6;
            border-color: #e5e7eb;
        }

        /* Pagination info */
        .pagination-info {
            text-align: center;
            color: #6b7280;
            font-size: 0.875rem;
            margin-top: 12px;
            font-weight: 500;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .search-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .search-input-group {
                min-width: auto;
            }

            .filter-group {
                flex-direction: column;
            }

            .pagination-container {
                flex-direction: column;
                gap: 16px;
                text-align: center;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .modal-actions {
                flex-direction: column;
            }
        }
    </style>

    <script>
        let currentTransactionId = null;
        let confirmActionType = null;

        function showCustomSnackbar(message, type = 'info') {
            const snackbar = document.createElement('div');
            snackbar.style.cssText = `
        position: fixed; top: 20px; right: 20px; padding: 16px 20px; border-radius: 8px;
        color: white; font-weight: 600; z-index: 10000; transform: translateX(100%);
        transition: transform 0.3s ease; max-width: 350px; word-wrap: break-word;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    `;

            if (type === 'success') snackbar.style.background = 'linear-gradient(135deg, #059669 0%, #047857 100%)';
            else if (type === 'error') snackbar.style.background = 'linear-gradient(135deg, #dc2626 0%, #b91c1c 100%)';
            else snackbar.style.background = 'linear-gradient(135deg, #6366f1 0%, #4f46e5 100%)';

            snackbar.textContent = message;
            document.body.appendChild(snackbar);

            setTimeout(() => snackbar.style.transform = 'translateX(0)', 100);
            setTimeout(() => {
                snackbar.style.transform = 'translateX(100%)';
                setTimeout(() => document.body.removeChild(snackbar), 300);
            }, 4000);
        }

        // Flagged Transaction Modal Functions
        function showFlaggedModal(transactionId, userName, uniqueId, date, amount, email) {
            currentTransactionId = transactionId;
            document.getElementById('modalUserName').textContent = userName;
            document.getElementById('modalUniqueId').textContent = uniqueId;
            document.getElementById('modalEmail').textContent = email;
            document.getElementById('modalDate').textContent = date;
            document.getElementById('modalAmount').textContent = '£' + amount;
            document.getElementById('flaggedModal').style.display = 'flex';
        }

        function closeFlaggedModal() {
            document.getElementById('flaggedModal').style.display = 'none';
            currentTransactionId = null;
        }

        // No ID Transaction Modal Functions
        function showNoIdModal(transactionId, name, date, amount, account) {
            currentTransactionId = transactionId;
            document.getElementById('noIdModalName').textContent = name;
            document.getElementById('noIdModalDate').textContent = date;
            document.getElementById('noIdModalAmount').textContent = '£' + amount;
            document.getElementById('noIdModalAccount').textContent = account;
            document.getElementById('noIdModal').style.display = 'flex';
        }

        function closeNoIdModal() {
            document.getElementById('noIdModal').style.display = 'none';
            currentTransactionId = null;
        }

        // Confirm Modal Functions
        function showConfirmModal(type) {
            confirmActionType = type;
            let text = '';

            switch (type) {
                case 'accept':
                    text = 'Are you sure you want to accept this transaction? This will mark it as successful.';
                    break;
                case 'ignore':
                    text = 'Are you sure you want to delete this flagged transaction? This action cannot be undone.';
                    break;
                case 'ignore_no_id':
                    text = 'Are you sure you want to delete this no-ID transaction? This action cannot be undone.';
                    break;
                default:
                    text = 'Are you sure you want to proceed?';
            }

            document.getElementById('confirmModalText').textContent = text;
            document.getElementById('customConfirmModal').style.display = 'flex';
        }

        function closeConfirmModal() {
            document.getElementById('customConfirmModal').style.display = 'none';
            confirmActionType = null;
        }

        // Action Functions
        function doAcceptTransaction() {
            if (!currentTransactionId) return;

            fetch(`/transactions/flagged/accept/${currentTransactionId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showCustomSnackbar('Transaction accepted successfully!', 'success');
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        showCustomSnackbar(data.message || 'Failed to accept transaction', 'error');
                    }
                    closeConfirmModal();
                    closeFlaggedModal();
                })
                .catch(error => {
                    console.error('Error:', error);
                    showCustomSnackbar('Error accepting transaction', 'error');
                    closeConfirmModal();
                    closeFlaggedModal();
                });
        }

        function doIgnoreTransaction() {
            if (!currentTransactionId) return;

            fetch(`/transactions/flagged/ignore/${currentTransactionId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showCustomSnackbar('Transaction ignored successfully!', 'success');
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        showCustomSnackbar(data.message || 'Failed to ignore transaction', 'error');
                    }
                    closeConfirmModal();
                    closeFlaggedModal();
                })
                .catch(error => {
                    console.error('Error:', error);
                    showCustomSnackbar('Error ignoring transaction', 'error');
                    closeConfirmModal();
                    closeFlaggedModal();
                });
        }

        function doIgnoreNoIdTransaction() {
            if (!currentTransactionId) return;

            fetch(`/admin/transactions/ignore-no-id`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        transaction_id: currentTransactionId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showCustomSnackbar('No-ID transaction ignored successfully!', 'success');
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        showCustomSnackbar(data.message || 'Failed to ignore transaction', 'error');
                    }
                    closeConfirmModal();
                    closeNoIdModal();
                })
                .catch(error => {
                    console.error('Error:', error);
                    showCustomSnackbar('Error ignoring transaction', 'error');
                    closeConfirmModal();
                    closeNoIdModal();
                });
        }

        function showAssignModal() {
            // IMPORTANT: Don't close the no-ID modal yet to preserve currentTransactionId
            if (!currentTransactionId) {
                showCustomSnackbar('No transaction selected', 'error');
                return;
            }

            console.log('🎯 Opening assign modal for transaction ID:', currentTransactionId);

            // Get transaction details from the no-ID modal
            const name = document.getElementById('noIdModalName').textContent;
            const date = document.getElementById('noIdModalDate').textContent;
            const amount = document.getElementById('noIdModalAmount').textContent;

            // Populate assign modal
            document.getElementById('assignModalName').textContent = name;
            document.getElementById('assignModalDate').textContent = date;
            document.getElementById('assignModalAmount').textContent = amount;

            // Hide no-ID modal but DON'T reset currentTransactionId
            document.getElementById('noIdModal').style.display = 'none';

            // Show assign modal
            document.getElementById('assignMemberModal').style.display = 'flex';

            // Load members and setup search
            loadAllMembers();

            // Focus on search input
            setTimeout(() => {
                document.getElementById('memberSearchInput').focus();
            }, 100);
        }

        function closeAssignModal() {
            console.log('🚪 Closing assign modal');
            document.getElementById('assignMemberModal').style.display = 'none';

            // Reset form
            document.getElementById('memberSearchInput').value = '';
            document.getElementById('selectedMemberId').value = '';
            document.getElementById('memberDropdownList').style.display = 'none';
            document.getElementById('memberDropdownList').innerHTML = '';

            // Reset currentTransactionId only when completely closing
            currentTransactionId = null;
            console.log('🔄 Transaction ID reset');
        }

        // Member search and assign functionality - Exact copy from import.js
        let allMembers = [];

        function loadAllMembers() {
            console.log('🚀 Starting to load all members...');

            if (allMembers.length > 0) {
                console.log('✅ Using cached members:', allMembers.length);
                setupSearchableDropdown();
                return;
            }

            fetch('/admin/get-all-members', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    console.log('📡 Response received:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('📊 Data received:', data);
                    if (data.success && data.members) {
                        allMembers = data.members;
                        console.log('✅ Members loaded successfully:', allMembers.length);
                        setupSearchableDropdown();
                    } else {
                        console.error('❌ Invalid response format:', data);
                        throw new Error('Invalid response format');
                    }
                })
                .catch(error => {
                    console.error('❌ Error loading members:', error);
                    const dropdownList = document.getElementById('memberDropdownList');
                    if (dropdownList) {
                        dropdownList.innerHTML =
                            '<div class="no-results">Error loading members. Please try again.</div>';
                        dropdownList.style.display = 'block';
                    }
                });
        }

        function setupSearchableDropdown() {
            console.log('🔧 Setting up searchable dropdown...');

            const searchInput = document.getElementById('memberSearchInput');
            const dropdownList = document.getElementById('memberDropdownList');

            if (!searchInput) {
                console.error('❌ Search input not found');
                return;
            }

            if (!dropdownList) {
                console.error('❌ Dropdown list not found');
                return;
            }

            console.log('✅ Found search input and dropdown list');

            // Add event listeners
            searchInput.addEventListener('input', handleSearchInput);
            searchInput.addEventListener('focus', handleSearchFocus);
            searchInput.addEventListener('keydown', handleSearchKeydown);

            console.log('✅ Event listeners added successfully');

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.searchable-dropdown')) {
                    dropdownList.style.display = 'none';
                }
            });

            // Test the search functionality by showing all members initially
            console.log('🧪 Testing initial display with all members...');
            filterAndDisplayMembers('');
        }

        // Handle search input
        function handleSearchInput(event) {
            const searchTerm = event.target.value.toLowerCase().trim();
            console.log('🔍 Search input changed:', searchTerm);
            filterAndDisplayMembers(searchTerm);
        }

        // Handle search focus
        function handleSearchFocus(event) {
            const searchTerm = event.target.value.toLowerCase().trim();
            console.log('👁️ Search input focused:', searchTerm);
            filterAndDisplayMembers(searchTerm);
        }

        // Handle keyboard navigation
        function handleSearchKeydown(event) {
            const dropdownList = document.getElementById('memberDropdownList');
            const items = dropdownList.querySelectorAll('.dropdown-item');

            if (event.key === 'ArrowDown') {
                event.preventDefault();
                // Focus first item or next item
                const currentIndex = Array.from(items).findIndex(item => item.classList.contains('keyboard-focus'));
                const nextIndex = currentIndex < items.length - 1 ? currentIndex + 1 : 0;
                updateKeyboardFocus(items, nextIndex);
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                // Focus last item or previous item
                const currentIndex = Array.from(items).findIndex(item => item.classList.contains('keyboard-focus'));
                const prevIndex = currentIndex > 0 ? currentIndex - 1 : items.length - 1;
                updateKeyboardFocus(items, prevIndex);
            } else if (event.key === 'Enter') {
                event.preventDefault();
                // Select the focused item
                const focusedItem = dropdownList.querySelector('.dropdown-item.keyboard-focus');
                if (focusedItem) {
                    focusedItem.click();
                }
            } else if (event.key === 'Escape') {
                dropdownList.style.display = 'none';
            }
        }

        // Update keyboard focus
        function updateKeyboardFocus(items, index) {
            // Remove focus from all items
            items.forEach(item => item.classList.remove('keyboard-focus'));

            // Add focus to the specified item
            if (items[index]) {
                items[index].classList.add('keyboard-focus');
                items[index].scrollIntoView({
                    block: 'nearest'
                });
            }
        }

        // Filter and display members in dropdown - Exact copy from import.js
        function filterAndDisplayMembers(searchTerm) {
            const dropdownList = document.getElementById('memberDropdownList');

            if (!dropdownList) {
                console.error('❌ Dropdown list not found');
                return;
            }

            console.log('🔍 Filtering members with search term:', searchTerm);
            console.log('📊 Total members available:', allMembers.length);

            let filteredMembers = [];

            if (!searchTerm || searchTerm === '') {
                // Show all members if no search term
                filteredMembers = allMembers.slice(); // Create a copy
            } else {
                // Filter members based on search term
                filteredMembers = allMembers.filter(member => {
                    if (!member) return false;

                    const name = (member.name || '').toLowerCase();
                    const email = (member.email || '').toLowerCase();
                    const uniqueId = (member.unique_id || '').toLowerCase();

                    const nameMatch = name.includes(searchTerm);
                    const emailMatch = email.includes(searchTerm);
                    const idMatch = uniqueId.includes(searchTerm);

                    console.log(
                        `🔍 Checking member: ${member.name}, nameMatch: ${nameMatch}, emailMatch: ${emailMatch}, idMatch: ${idMatch}`
                    );

                    return nameMatch || emailMatch || idMatch;
                });
            }

            console.log('✅ Filtered members count:', filteredMembers.length);

            dropdownList.innerHTML = '';

            if (filteredMembers.length === 0) {
                dropdownList.innerHTML = '<div class="no-results">No members found matching your search</div>';
            } else {
                filteredMembers.forEach((member) => {
                    const memberItem = document.createElement('div');
                    memberItem.className = 'dropdown-item';
                    memberItem.innerHTML = `
                <div class="member-name">${escapeHtml(member.name)}</div>
                <div class="member-details">${escapeHtml(member.unique_id)} • ${escapeHtml(member.email)}</div>
            `;

                    // Add click event listener
                    memberItem.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        selectMember(member);
                    });

                    // Add hover event listeners
                    memberItem.addEventListener('mouseenter', function() {
                        // Remove keyboard focus from all items when hovering
                        dropdownList.querySelectorAll('.dropdown-item').forEach(item => {
                            item.classList.remove('keyboard-focus');
                        });
                    });

                    dropdownList.appendChild(memberItem);
                });
            }

            dropdownList.style.display = 'block';
        }

        // Escape HTML to prevent XSS
        function escapeHtml(text) {
            if (!text) return '';
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) {
                return map[m];
            });
        }

        // Select member function - Exact copy from import.js
        function selectMember(member) {
            console.log('✅ Selecting member:', member);

            const searchInput = document.getElementById('memberSearchInput');
            const dropdownList = document.getElementById('memberDropdownList');
            const selectedMemberIdInput = document.getElementById('selectedMemberId');

            if (!searchInput || !dropdownList || !selectedMemberIdInput) {
                console.error('❌ Required elements not found for member selection');
                return;
            }

            // Update the input field with member info
            searchInput.value = `${member.name} (${member.unique_id || member.email})`;
            selectedMemberIdInput.value = member.id;

            // Hide the dropdown
            dropdownList.style.display = 'none';

            // Add visual feedback
            searchInput.style.borderColor = '#10b981';
            setTimeout(() => {
                searchInput.style.borderColor = '#e2e8f0';
            }, 2000);

            console.log('✅ Member selected successfully. ID:', member.id);
        }

        function assignToSelectedMember() {
            console.log('🎯 Starting assignment process...');
            console.log('📋 Current transaction ID:', currentTransactionId);

            const selectedMemberId = document.getElementById('selectedMemberId').value;
            const searchInput = document.getElementById('memberSearchInput');

            console.log('👤 Selected member ID:', selectedMemberId);

            if (!selectedMemberId) {
                console.error('❌ No member selected');
                showCustomSnackbar('Please select a member first', 'error');
                searchInput.style.borderColor = '#ef4444';
                setTimeout(() => {
                    searchInput.style.borderColor = '#e2e8f0';
                }, 2000);
                return;
            }

            if (!currentTransactionId) {
                console.error('❌ No transaction ID available');
                showCustomSnackbar('No transaction selected', 'error');
                return;
            }

            // Get member name from search input for confirmation
            const memberName = searchInput.value;
            console.log('✅ Ready to assign transaction', currentTransactionId, 'to member', selectedMemberId, '(' +
                memberName + ')');

            confirmActionType = 'assign_member';
            document.getElementById('confirmModalText').textContent =
                `Are you sure you want to assign this transaction to ${memberName}?`;

            // Hide assign modal but DON'T reset currentTransactionId yet
            document.getElementById('assignMemberModal').style.display = 'none';
            document.getElementById('customConfirmModal').style.display = 'flex';
        }

        function doAssignToMember() {
            console.log('🚀 Executing assignment...');

            const selectedMemberId = document.getElementById('selectedMemberId').value;

            console.log('📋 Transaction ID:', currentTransactionId);
            console.log('👤 Member ID:', selectedMemberId);

            if (!selectedMemberId || !currentTransactionId) {
                console.error('❌ Missing required information - Transaction ID:', currentTransactionId, 'Member ID:',
                    selectedMemberId);
                showCustomSnackbar('Missing required information', 'error');
                return;
            }

            console.log('📡 Sending assignment request...');

            fetch('/admin/transactions/assign-no-id', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        transaction_id: currentTransactionId,
                        member_id: selectedMemberId
                    })
                })
                .then(response => {
                    console.log('📡 Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('📊 Response data:', data);
                    if (data.success) {
                        showCustomSnackbar('Transaction assigned successfully!', 'success');
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        showCustomSnackbar(data.message || 'Failed to assign transaction', 'error');
                    }
                    closeConfirmModal();
                    // Don't call closeAssignModal here as it resets currentTransactionId
                })
                .catch(error => {
                    console.error('❌ Assignment error:', error);
                    showCustomSnackbar('Error assigning transaction', 'error');
                    closeConfirmModal();
                });
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Modal click outside to close
            document.getElementById('flaggedModal').addEventListener('click', function(e) {
                if (e.target === this) closeFlaggedModal();
            });

            document.getElementById('noIdModal').addEventListener('click', function(e) {
                if (e.target === this) closeNoIdModal();
            });

            document.getElementById('assignMemberModal').addEventListener('click', function(e) {
                if (e.target === this) closeAssignModal();
            });

            document.getElementById('customConfirmModal').addEventListener('click', function(e) {
                if (e.target === this) closeConfirmModal();
            });

            // Confirm button
            document.getElementById('confirmModalOkBtn').onclick = function() {
                switch (confirmActionType) {
                    case 'accept':
                        doAcceptTransaction();
                        break;
                    case 'ignore':
                        doIgnoreTransaction();
                        break;
                    case 'ignore_no_id':
                        doIgnoreNoIdTransaction();
                        break;
                    case 'assign_member':
                        doAssignToMember();
                        break;
                }
            };

            // Keyboard support
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (document.getElementById('customConfirmModal').style.display === 'flex') {
                        closeConfirmModal();
                    } else if (document.getElementById('assignMemberModal').style.display === 'flex') {
                        // When closing assign modal with ESC, go back to no-ID modal
                        document.getElementById('assignMemberModal').style.display = 'none';
                        document.getElementById('noIdModal').style.display = 'flex';
                        // Don't reset currentTransactionId
                    } else if (document.getElementById('flaggedModal').style.display === 'flex') {
                        closeFlaggedModal();
                    } else if (document.getElementById('noIdModal').style.display === 'flex') {
                        closeNoIdModal();
                    }
                }
            });

            // Auto-submit form on filter change
            document.querySelectorAll('.filter-select').forEach(select => {
                select.addEventListener('change', function() {
                    this.closest('form').submit();
                });
            });
        });
    </script>

@endsection
