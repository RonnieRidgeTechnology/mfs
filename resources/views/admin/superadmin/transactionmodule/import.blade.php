@extends('layouts.admin')
@section('content')
<main class="main-content">
    @include('layouts.header')
    <div class="import-container">
        <!-- Header -->
        <div class="import-header">
            <h1>Transaction Import</h1>
            <p>Upload your Excel or CSV file to import transactions</p>
        </div>

        <!-- Dropzone -->
        <div class="dropzone-container" id="dropzone">
            <div class="dropzone-icon">
                <i class="fa-solid fa-cloud-arrow-up"></i>
            </div>
            <div class="dropzone-text">Drop your file here or click to browse</div>
            <div class="dropzone-subtext">Supports Excel (.xlsx, .xls) and CSV files</div>
            <button class="upload-btn">
                <i class="fa-solid fa-upload"></i> Choose File
            </button>
            <input type="file" id="fileInput" class="file-input" accept=".xlsx,.xls,.csv">
        </div>

        <!-- Progress Section -->
        <div class="progress-section" id="progressSection">
            <div class="progress-header">
                <div class="progress-title">Importing Transactions...</div>
                <div class="progress-percentage" id="progressPercentage">0%</div>
            </div>
            <div class="progress-bar-container">
                <div class="progress-bar-fill" id="progressBarFill" style="width: 0%"></div>
            </div>
            <div class="progress-status" id="progressStatus">Preparing import...</div>
        </div>

        <!-- Skeleton Loader -->
        <div class="skeleton-loader" id="skeletonLoader">
            <div class="skeleton-row">
                <div class="skeleton-avatar"></div>
                <div class="skeleton-content">
                    <div class="skeleton-line medium"></div>
                    <div class="skeleton-line short"></div>
                </div>
            </div>
            <div class="skeleton-row">
                <div class="skeleton-avatar"></div>
                <div class="skeleton-content">
                    <div class="skeleton-line medium"></div>
                    <div class="skeleton-line short"></div>
                </div>
            </div>
            <div class="skeleton-row">
                <div class="skeleton-avatar"></div>
                <div class="skeleton-content">
                    <div class="skeleton-line medium"></div>
                    <div class="skeleton-line short"></div>
                </div>
            </div>
        </div>

        <!-- Results Section -->
        <div class="results-section" id="resultsSection" >
            <div class="results-header">
                <div class="results-title">Import Results</div>
                <div class="results-stats">
                    <div class="stat-item stat-success" id="successCount">0 Success</div>
                    <div class="stat-item stat-warning" id="flaggedCount">{{ $transactions->where('flag_status', 0)->count() }} Flagged</div>
                   <div class="stat-item stat-info" id="guestCount" style="display: none;">{{ $guestCount }} Guests</div>
                    <div class="stat-item stat-no-id" id="noIdFlaggedCount">{{ $noIdFlaggedCount }} No ID Flag</div>
                    <div class="stat-item stat-error" id="totalCount">0 Total</div>
                </div>
            </div>

            <!-- Flagged Transactions -->
            <div class="transaction-table" id="flaggedTransactionsSection">
                <div class="table-header">
                    <h3 class="table-title">Duplicate Flagged Transactions</h3>
                </div>
                <div class="table-content" id="flaggedTransactionsContent">
                    @foreach($transactions->where('flag_status', 0) as $transaction)
                        <div class="transaction-item flagged" data-transaction-id="{{ $transaction->id }}">
                            <div class="transaction-avatar">
                                {{ substr($transaction->user->name ?? 'U', 0, 1) }}{{ substr($transaction->user->name ?? 'U', strpos($transaction->user->name ?? 'U', ' ') + 1, 1) ?? '' }}
                            </div>
                            <div class="transaction-info">
                                <div class="transaction-name">{{ $transaction->user->name ?? 'Unknown User' }}</div>
                                <div class="transaction-details">
                                    {{ $transaction->user->unique_id ?? 'N/A' }} • {{ $transaction->user->email ?? 'N/A' }} • {{ $transaction->date }}
                                </div>
                            </div>
                            <div class="transaction-amount">£{{ $transaction->amount }}</div>
                            <div class="transaction-status status-flagged">Flagged</div>
                            <button class="flagged-btn" onclick="console.log('Button clicked for transaction:', {{ $transaction->id }}); showFlaggedModal({{ $transaction->id }}, '{{ $transaction->user->name ?? 'Unknown User' }}', '{{ $transaction->user->unique_id ?? 'N/A' }}', '{{ $transaction->date }}', '{{ $transaction->amount }}', '{{ $transaction->user->email ?? 'N/A' }}')">
                                <i class="fa-solid fa-exclamation-triangle"></i> View Reason
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Successful Transactions (Hidden by default, shown only after import) -->
            <div class="transaction-table" id="successfulTransactionsSection" style="display: none;">
                <div class="table-header">
                    <h3 class="table-title">Successful Transactions</h3>
                </div>
                <div class="table-content" id="successfulTransactionsContent">
                    <!-- Successful transactions will be populated here after import -->
                </div>
            </div>

            <!-- Guest Users Section (Hidden from display but functionality preserved) -->
            <div class="transaction-table guest-section-hidden" id="guestUsersSection" style="display: none !important; visibility: hidden !important; height: 0 !important; overflow: hidden !important;">
                <div class="table-header">
                    <h3 class="table-title">New Guest Users Created</h3>
                </div>
                <div class="table-content" id="guestUsersContent">
                    <!-- Guest users will be populated here after import -->
                </div>
            </div>

            <!-- No ID Flag Transactions Section -->
            <div class="transaction-table" id="noIdFlagTransactionsSection">
                <div class="table-header">
                    <h3 class="table-title">No ID Flag Transactions</h3>
                </div>
                <div class="table-content" id="noIdFlagTransactionsContent">
                    @foreach(\App\Models\Transaction::where('no_id_flaged', 0)->orderBy('date', 'desc')->get() as $transaction)
                        <div class="transaction-item no-id-flag" data-transaction-id="{{ $transaction->id }}">
                            <div class="transaction-avatar">
                                <i class="fa-solid fa-user-slash"></i>
                            </div>
                            <div class="transaction-info">
                                <div class="transaction-name" title="{{ $transaction->name ?? 'No User ID Found' }}">{{ $transaction->name ?? 'No User ID Found' }}</div>
                                <div class="transaction-details">
                                    Transaction ID: {{ $transaction->id }} • {{ $transaction->date }} • {{ $transaction->account }}
                                </div>
                            </div>
                            <div class="transaction-amount">£{{ $transaction->amount }}</div>
                            <div class="transaction-status status-no-id">No ID</div>
                            <div class="no-id-action-buttons">
                                <button class="no-id-action-btn"
                                    data-transaction-id="{{ $transaction->id }}"
                                    data-transaction-date="{{ $transaction->date }}"
                                    data-transaction-amount="{{ $transaction->amount }}"
                                    data-transaction-account="{{ $transaction->account }}"
                                    data-transaction-name="{{ $transaction->name ?? 'No User ID Found' }}"
                                    onclick="showNoIdTransactionModal(this)">
                                    <i class="fa-solid fa-cog"></i> Manage
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- No Transactions Message (Hidden by default, shown only when no transactions exist) -->
            <div class="transaction-table" id="noTransactionsSection" style="display: none;">
                <div class="table-header">
                    <h3 class="table-title">No Transactions Found</h3>
                </div>
                <div class="table-content" style="text-align: center; padding: 3rem;">
                    <div style="color: #64748b; font-size: 1.1rem;">
                        <i class="fa-solid fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
                        <div>No transactions have been imported yet.</div>
                        <div style="font-size: 0.9rem; margin-top: 0.5rem;">Upload a file to see transactions here.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Flagged Transaction Modal -->
    <div class="modal-overlay" id="flaggedModal" style="display: none;">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3 class="modal-title">Flagged Transaction Details</h3>
                <button class="modal-close" onclick="closeFlaggedModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-info">
                    <div class="modal-info-item">
                        <span class="modal-info-label">User Name:</span>
                        <span class="modal-info-value" id="modalUserName"></span>
                    </div>
                    <div class="modal-info-item">
                        <span class="modal-info-label">Unique ID:</span>
                        <span class="modal-info-value" id="modalUniqueId"></span>
                    </div>
                    <div class="modal-info-item">
                        <span class="modal-info-label">Email:</span>
                        <span class="modal-info-value" id="modalEmail"></span>
                    </div>
                    <div class="modal-info-item">
                        <span class="modal-info-label">Date:</span>
                        <span class="modal-info-value" id="modalDate"></span>
                    </div>
                    <div class="modal-info-item">
                        <span class="modal-info-label">Amount:</span>
                        <span class="modal-info-value" id="modalAmount"></span>
                    </div>
                </div>
                <div class="modal-reason">
                    <div class="modal-reason-title">
                        <i class="fa-solid fa-exclamation-triangle"></i> Reason for Flagging
                    </div>
                    <div class="modal-reason-text">
                        This transaction has been flagged because a duplicate transaction already exists for this user on the same date.
                        You can either accept this transaction (which will mark it as successful) or ignore it (which will delete it).
                    </div>
                </div>
            </div>
            <div class="modal-actions">
                <button class="modal-btn modal-btn-cancel" onclick="closeFlaggedModal()">Cancel</button>
                <button class="modal-btn modal-btn-ignore" onclick="showConfirmModal('ignore')">
                    <i class="fa-solid fa-trash"></i> Ignore & Delete
                </button>
                <button class="modal-btn modal-btn-accept" onclick="showConfirmModal('accept')">
                    <i class="fa-solid fa-check"></i> Accept Transaction
                </button>
            </div>
        </div>
    </div>

    <!-- Custom Confirm Modal -->
    <div class="modal-overlay" id="customConfirmModal" style="display:none; z-index: 10000 !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="confirmModalTitle">Are you sure?</h3>
                <button class="modal-close" onclick="closeConfirmModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div id="confirmModalText" style="color:#374151; font-size:1rem;">
                    Are you sure you want to proceed?
                </div>
            </div>
            <div class="modal-actions">
                <button class="modal-btn modal-btn-cancel" onclick="closeConfirmModal()">Cancel</button>
                <button class="modal-btn modal-btn-accept" id="confirmModalOkBtn">OK</button>
            </div>
        </div>
    </div>

    <!-- Guest User Action Modal -->
    <div class="modal-overlay" id="guestUserModal" style="display:none;">
        <div class="modal-content" style="max-width: 700px;">
            <div class="modal-header">
                <h3 class="modal-title">Guest User Management</h3>
                <button class="modal-close" onclick="closeGuestUserModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="guest-user-info" id="guestUserInfo">
                    <!-- Guest user info will be populated here -->
                </div>
                <div class="guest-user-actions">
                    <h4 style="margin: 20px 0 15px 0; color: #374151;">Choose Action:</h4>
                    <div class="action-buttons">
                        <button class="guest-action-btn" onclick="handleGuestAction('remain')">
                            <i class="fa-solid fa-user-clock"></i>
                            Remain as Guest
                        </button>
                        <button class="guest-action-btn" onclick="handleGuestAction('promote')">
                            <i class="fa-solid fa-user-plus"></i>
                            Make as New Member
                        </button>
                        <button class="guest-action-btn" onclick="handleGuestAction('assign')">
                            <i class="fa-solid fa-user-check"></i>
                            Assign to Existing Member
                        </button>
                    </div>
                    <div id="assignToMemberSection" style="display: none; margin-top: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
                            Select Existing Member:
                        </label>
                        <select id="existingMemberSelect" class="guest-select-input">
                            <option value="">Choose a member...</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- No ID Transaction Management Modal -->
    <div class="modal-overlay" id="noIdTransactionModal" style="display:none;">
        <div class="modal" style="max-width: 700px; width: 90%;">
            <div class="modal-header">
                <div class="header-content">
                    <h3 class="modal-title">No ID Transaction Management</h3>
                    <button class="close-modal" onclick="closeNoIdTransactionModal()">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div class="no-id-transaction-info" id="noIdTransactionInfo">
                    <!-- Transaction info will be populated here -->
                </div>
                <div class="action-section">
                    <h4 class="section-title">Choose Action:</h4>
                    <div class="action-buttons">
                        <button class="action-button ignore-btn" onclick="handleNoIdAction('ignore')">
                            <i class="fa-solid fa-trash"></i>
                            <span>Ignore Transaction</span>
                        </button>
                        <button class="action-button assign-btn" onclick="handleNoIdAction('assign')">
                            <i class="fa-solid fa-user-check"></i>
                            <span>Assign to Member</span>
                        </button>
                    </div>
                    <div id="assignToMemberSectionNoId" class="form-section" style="display: none;">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                Search and Select Member
                            </label>
                            <div class="searchable-dropdown">
                                <input type="text" id="memberSearchInput" class="form-input" placeholder="Type to search members by name, email, or ID..." autocomplete="off">
                                <div class="dropdown-list" id="memberDropdownList" style="display: none;">
                                    <!-- Members will be populated here -->
                                </div>
                                <input type="hidden" id="selectedMemberId" value="">
                            </div>
                        </div>
                        <div class="form-actions">
                            <button onclick="assignToExistingMember()" class="btn-submit">
                                <i class="fa-solid fa-link"></i>
                                Assign to Selected Member
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- No ID Transaction Confirmation Modal -->
    <div class="modal-overlay" id="noIdConfirmModal" style="display: none;">
        <div class="modal" style="max-width: 500px; width: 90%;">
            <div class="modal-header">
                <div class="header-content">
                    <h3 class="modal-title">Confirm Action</h3>
                    <button class="close-modal" onclick="closeNoIdConfirmModal()">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div class="confirmation-content">
                    <div class="confirmation-icon">
                        <i class="fa-solid fa-exclamation-triangle"></i>
                    </div>
                    <div class="confirmation-message" id="noIdConfirmMessage">
                        <!-- Message will be populated here -->
                    </div>
                </div>
                <div class="confirmation-actions">
                    <button class="btn-cancel" onclick="closeNoIdConfirmModal()">
                        <i class="fa-solid fa-times"></i>
                        Cancel
                    </button>
                    <button class="btn-confirm" id="noIdConfirmButton" onclick="executeNoIdAction()">
                        <i class="fa-solid fa-check"></i>
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
/* No ID Flag Statistics */
.stat-no-id {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    border: none;
}

/* Modal Overlay - Match Application Theme */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    padding: 20px;
    box-sizing: border-box;
}

/* Modal Content - Scrollable with Hidden Scrollbar */
.modal {
    max-height: 90vh;
    overflow-y: auto;
    background: linear-gradient(to bottom, #ffffff 0%, #fafbfc 100%);
    border-radius: 16px;
    width: 100%;
    max-width: 700px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    /* Hide scrollbar for webkit browsers */
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* Internet Explorer 10+ */
}

.modal::-webkit-scrollbar {
    display: none; /* Safari and Chrome */
}

/* Modal Header */
.modal-header {
    background: white;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    position: sticky;
    top: 0;
    z-index: 10;
    border-radius: 16px 16px 0 0;
}

.header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.modal-title {
    font-size: 1.15rem;
    color: var(--text);
    font-weight: 600;
    margin: 0;
}

.close-modal {
    background: none;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    color: #6c757d;
}

.close-modal:hover {
    background: #f1f3f5;
    color: #dc3545;
}

/* Modal Body */
.modal-body {
    padding: 1.5rem;
}

/* Transaction Info */
.no-id-transaction-info {
    background: #f8fafc;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    border: 1px solid #e2e8f0;
}

/* Action Section */
.action-section {
    margin-bottom: 1.5rem;
}

.section-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 1rem;
    text-align: center;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-bottom: 1.5rem;
}

.action-button {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.ignore-btn {
    background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
    color: white;
}

.ignore-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

.assign-btn {
    background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    color: white;
}

.assign-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
}

/* Form Section */
.form-section {
    background: #f8fafc;
    border-radius: 8px;
    padding: 1.5rem;
    margin-top: 1rem;
    border: 1px solid #e2e8f0;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--text);
    font-size: 0.9rem;
}

.form-label i {
    color: var(--primary);
}

.form-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.9rem;
    background: white;
    transition: all 0.2s ease;
    box-sizing: border-box;
}

.form-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(255, 152, 0, 0.1);
}

.form-actions {
    text-align: center;
    margin-top: 1rem;
}

.stat-no-id {
    color: #336ce7;
    background: #eef3ff;
}

.btn-submit {
    background: linear-gradient(135deg, var(--primary) 0%, #e68900 100%);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.btn-submit:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
}

/* Searchable Dropdown */
.searchable-dropdown {
    position: relative;
    width: 100%;
}

.dropdown-list {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #d1d5db;
    border-top: none;
    border-radius: 0 0 8px 8px;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.dropdown-item {
    padding: 0.75rem 1rem;
    cursor: pointer;
    border-bottom: 1px solid #f1f5f9;
    transition: all 0.2s ease;
}

.dropdown-item:hover,
.dropdown-item.keyboard-focus {
    background: var(--primary);
    color: white;
}

.dropdown-item:hover .member-name,
.dropdown-item:hover .member-details,
.dropdown-item.keyboard-focus .member-name,
.dropdown-item.keyboard-focus .member-details {
    color: white;
}

.dropdown-item:last-child {
    border-bottom: none;
    border-radius: 0 0 8px 8px;
}

.member-name {
    font-weight: 600;
    color: var(--text);
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.member-details {
    font-size: 0.8rem;
    color: #64748b;
}

.no-results {
    padding: 1rem;
    color: #64748b;
    font-style: italic;
    text-align: center;
    background: #f8fafc;
}

/* No ID Action Button Styles for Transaction Items */
.no-id-action-buttons {
    display: flex;
    gap: 8px;
    align-items: center;
}

.no-id-action-btn {
    background: linear-gradient(135deg, var(--primary) 0%, #e68900 100%);
    color: white;
    border: none;
    padding: 6px 12px;
    margin-left: 6px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 4px;
}

.no-id-action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
}

.status-no-id {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}

/* Custom Scrollbar for Dropdown */
.dropdown-list::-webkit-scrollbar {
    width: 6px;
}

.dropdown-list::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.dropdown-list::-webkit-scrollbar-thumb {
    background: var(--primary);
    border-radius: 3px;
}

.dropdown-list::-webkit-scrollbar-thumb:hover {
    background: #e68900;
}

/* Confirmation Modal Styles */
.confirmation-content {
    text-align: center;
    padding: 1rem 0;
}

.confirmation-icon {
    font-size: 3rem;
    color: #f59e0b;
    margin-bottom: 1rem;
}

.confirmation-message {
    font-size: 1rem;
    color: var(--text);
    margin-bottom: 1.5rem;
    line-height: 1.5;
}

.confirmation-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.btn-cancel {
    background: #6c757d;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-cancel:hover {
    background: #5a6268;
    transform: translateY(-1px);
}

.btn-confirm {
    background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-confirm:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

.btn-confirm.assign-action {
    background: linear-gradient(135deg, #059669 0%, #10b981 100%);
}

.btn-confirm.assign-action:hover {
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
}

/* Transaction Name Display - Ensure Full Name is Shown */
.transaction-name {
    font-weight: 600;
    color: var(--text);
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
    white-space: normal !important; /* Allow text wrapping */
    word-wrap: break-word !important; /* Break long words if needed */
    overflow: visible !important; /* Don't hide overflow */
    text-overflow: unset !important; /* Don't use ellipsis */
    max-width: none !important; /* Remove any width restrictions */
    line-height: 1.3; /* Better line spacing for wrapped text */
}

/* Transaction Info Container */
.transaction-info {
    flex: 1;
    min-width: 0; /* Allow shrinking */
    padding-right: 8px;
}

/* Transaction Details */
.transaction-details {
    font-size: 0.8rem;
    color: #64748b;
    white-space: normal;
    word-wrap: break-word;
    line-height: 1.2;
}

/* Hide Guest Users Section Completely */
.guest-section-hidden,
#guestUsersSection {
    display: none !important;
    visibility: hidden !important;
    height: 0 !important;
    overflow: hidden !important;
    margin: 0 !important;
    padding: 0 !important;
    border: none !important;
    opacity: 0 !important;
    position: absolute !important;
    left: -9999px !important;
}

/* Ensure guest section never shows even with JavaScript */
.guest-section-hidden * {
    display: none !important;
    visibility: hidden !important;
}
</style>

@endsection
