let currentTransactionId = null;
let confirmActionType = null;

// Custom snackbar function
function showCustomSnackbar(message, type = 'info') {
    // Create snackbar element
    const snackbar = document.createElement('div');
    snackbar.className = `custom-snackbar ${type}`;
    snackbar.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        z-index: 10000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        max-width: 300px;
        word-wrap: break-word;
    `;

    // Set background color based on type
    switch (type) {
        case 'success':
            snackbar.style.background = 'linear-gradient(135deg, #059669 0%, #10b981 100%)';
            break;
        case 'error':
            snackbar.style.background = 'linear-gradient(135deg, #dc2626 0%, #ef4444 100%)';
            break;
        case 'warning':
            snackbar.style.background = 'linear-gradient(135deg, #d97706 0%, #f59e0b 100%)';
            break;
        default:
            snackbar.style.background = 'linear-gradient(135deg, #6366f1 0%, #38bdf8 100%)';
    }

    snackbar.textContent = message;
    document.body.appendChild(snackbar);

    // Show snackbar
    setTimeout(() => {
        snackbar.style.transform = 'translateX(0)';
    }, 100);

    // Hide and remove snackbar after 3 seconds
    setTimeout(() => {
        snackbar.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(snackbar);
        }, 300);
    }, 3000);
}

document.addEventListener('DOMContentLoaded', function () {
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('fileInput');
    const progressSection = document.getElementById('progressSection');
    const skeletonLoader = document.getElementById('skeletonLoader');
    const resultsSection = document.getElementById('resultsSection');
    const progressBarFill = document.getElementById('progressBarFill');
    const progressPercentage = document.getElementById('progressPercentage');
    const progressStatus = document.getElementById('progressStatus');

    // Initialize flagged section visibility
    const flaggedSection = document.getElementById('flaggedTransactionsSection');
    const flaggedContent = document.getElementById('flaggedTransactionsContent');

    if (flaggedSection && flaggedContent) {
        if (flaggedContent.children.length > 0) {
            flaggedSection.style.display = 'block';
        } else {
            flaggedSection.style.display = 'none';
        }
    }

    // Dropzone functionality
    dropzone.addEventListener('click', (e) => {

        // Don't trigger if clicking on the button specifically
        if (e.target.closest('.upload-btn')) {
            console.log('🚫 Button detected, preventing dropzone click');
            return;
        }
        console.log('📁 Dropzone area clicked, triggering file input');
        fileInput.click();
    });

    // Button click handler
    const uploadBtn = dropzone.querySelector('.upload-btn');
    if (uploadBtn) {
        uploadBtn.addEventListener('click', (e) => {
            console.log('🔘 Upload button clicked');
            e.preventDefault();
            e.stopPropagation();
            console.log('📁 Button click triggering file input');
            fileInput.click();
        });
    }

    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('dragover');
    });

    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('dragover');
    });

    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFile(files[0]);
        }
    });

    fileInput.addEventListener('change', (e) => {
        console.log('📂 File input change event triggered');
        console.log('📂 Files selected:', e.target.files.length);
        if (e.target.files.length > 0) {
            console.log('📂 Selected file:', e.target.files[0].name);
            console.log('📂 File type:', e.target.files[0].type);
            console.log('📂 File size:', e.target.files[0].size, 'bytes');
            handleFile(e.target.files[0]);
        } else {
            console.log('⚠️ No files selected');
        }
    });

    function handleFile(file) {
        console.log('🔧 handleFile called with:', file.name);

        // Validate file type
        const allowedTypes = [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel',
            'text/csv'
        ];

        console.log('🔧 File type:', file.type);
        console.log('🔧 File extension check:', file.name.match(/\.(xlsx|xls|csv)$/i));
        console.log('🔧 Is type in allowed list?', allowedTypes.includes(file.type));

        if (!allowedTypes.includes(file.type) && !file.name.match(/\.(xlsx|xls|csv)$/i)) {
            console.log('❌ File validation failed');
            showCustomSnackbar('Please select a valid Excel or CSV file.', 'error');
            return;
        }

        console.log('✅ File validation passed, starting import');
        // Start import process
        startImport(file);
    }

    function startImport(file) {
        console.log('🚀 startImport called with file:', file.name);

        const formData = new FormData();
        formData.append('file', file);
        console.log('📦 FormData created with file');

        // Show progress section and hide results
        console.log('📊 Showing progress section');
        progressSection.style.display = 'block';
        skeletonLoader.style.display = 'block';
        resultsSection.style.display = 'none';

        // Simulate progress
        let progress = 0;
        const progressInterval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress > 90) progress = 90;

            progressBarFill.style.width = progress + '%';
            progressPercentage.textContent = Math.round(progress) + '%';

            if (progress < 30) {
                progressStatus.textContent = 'Reading file...';
            } else if (progress < 60) {
                progressStatus.textContent = 'Processing transactions...';
            } else if (progress < 90) {
                progressStatus.textContent = 'Validating data...';
            }
        }, 200);

        // Make API call
        console.log('🌐 Making API call to /admin/transactions/import');
        console.log('🌐 CSRF Token:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ? 'Found' : 'Missing');

        fetch('/admin/transactions/import', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
            .then(response => {
                console.log('📡 Fetch response status:', response.status);
                console.log('📡 Fetch response ok:', response.ok);
                return response.json();
            })
            .then(data => {
                console.log('📡 Response data received:', data);
                clearInterval(progressInterval);

                // Complete progress
                progressBarFill.style.width = '100%';
                progressPercentage.textContent = '100%';
                progressStatus.textContent = 'Import completed!';

                setTimeout(() => {
                    progressSection.style.display = 'none';
                    skeletonLoader.style.display = 'none';

                    // Show results section and populate with data
                    showImportResults(data);
                }, 1000);
            })
            .catch(error => {
                console.error('❌ Import error:', error);
                console.error('❌ Error message:', error.message);
                clearInterval(progressInterval);
                progressSection.style.display = 'none';
                skeletonLoader.style.display = 'none';
                showCustomSnackbar('Import failed: ' + error.message, 'error');
            });
    }
});

// Show import results function
function showImportResults(data) {
    const resultsSection = document.getElementById('resultsSection');
    const flaggedSection = document.getElementById('flaggedTransactionsSection');
    const successfulSection = document.getElementById('successfulTransactionsSection');
    const noTransactionsSection = document.getElementById('noTransactionsSection');

    // Update stats
    document.getElementById('successCount').textContent = (data.successful_count || 0) + ' Success';
    document.getElementById('flaggedCount').textContent = (data.flagged_count || 0) + ' Flagged';

    // Update guest count only if element exists
    const guestCountElement = document.getElementById('guestCount');
    if (guestCountElement) {
        guestCountElement.textContent = (data.guest_count || 0) + ' Guests';
    }

    document.getElementById('noIdFlaggedCount').textContent = (data.no_id_flagged_count || 0) + ' No ID Flag';
    document.getElementById('totalCount').textContent = (data.total_count || 0) + ' Total';

    // Show flagged section only if there are flagged transactions
    if (data.flagged_transactions && data.flagged_transactions.length > 0) {
        populateFlaggedTransactions(data.flagged_transactions);
        flaggedSection.style.display = 'block';
    } else {
        // Clear any existing content and hide section
        const flaggedContent = document.getElementById('flaggedTransactionsContent');
        if (flaggedContent) {
            flaggedContent.innerHTML = '';
        }
        flaggedSection.style.display = 'none';
    }

    // Only show successful section if there are successful transactions
    if (data.successful_transactions && data.successful_transactions.length > 0) {
        populateSuccessfulTransactions(data.successful_transactions);
        successfulSection.style.display = 'block';
    } else {
        successfulSection.style.display = 'none';
    }

    // Process guest users but keep section hidden
    if (data.guest_users && data.guest_users.length > 0) {
        populateGuestUsers(data.guest_users);
        // Keep guest section hidden - functionality preserved but not displayed
        guestUsersSection.style.display = 'none';
    } else {
        guestUsersSection.style.display = 'none';
    }

    // Show no ID flagged transactions section if there are any
    const noIdFlagTransactionsSection = document.getElementById('noIdFlagTransactionsSection');
    if (data.no_id_flagged_transactions && data.no_id_flagged_transactions.length > 0) {
        populateNoIdFlaggedTransactions(data.no_id_flagged_transactions);
        if (noIdFlagTransactionsSection) {
            noIdFlagTransactionsSection.style.display = 'block';
        }
    } else {
        if (noIdFlagTransactionsSection) {
            noIdFlagTransactionsSection.style.display = 'none';
        }
    }

    // Show no transactions message if no transactions at all
    if ((!data.flagged_transactions || data.flagged_transactions.length === 0) &&
        (!data.successful_transactions || data.successful_transactions.length === 0) &&
        (!data.guest_users || data.guest_users.length === 0) &&
        (!data.no_id_flagged_transactions || data.no_id_flagged_transactions.length === 0)) {
        noTransactionsSection.style.display = 'block';
    } else {
        noTransactionsSection.style.display = 'none';
    }

    // Show results section
    resultsSection.style.display = 'block';
}

// Populate flagged transactions
function populateFlaggedTransactions(transactions) {
    const container = document.getElementById('flaggedTransactionsContent');
    container.innerHTML = '';

    transactions.forEach(transaction => {
        // Handle both import data structure and database data structure
        const userName = transaction.user_name || transaction.user?.name || 'Unknown User';
        const userUniqueId = transaction.user_id || transaction.user?.unique_id || 'N/A';
        const userEmail = transaction.user_email || transaction.user?.email || 'N/A';
        const transactionId = transaction.id || Math.random(); // Use random ID for import data

        const transactionHtml = `
            <div class="transaction-item flagged" data-transaction-id="${transactionId}">
                <div class="transaction-avatar">
                    ${getInitials(userName)}
                </div>
                <div class="transaction-info">
                    <div class="transaction-name">${userName}</div>
                    <div class="transaction-details">
                        ${userUniqueId} • ${userEmail} • ${transaction.date}
                    </div>
                </div>
                <div class="transaction-amount">£${transaction.amount}</div>
                <div class="transaction-status status-flagged">Flagged</div>
                <button class="flagged-btn" onclick="showFlaggedModal(${transactionId}, '${userName}', '${userUniqueId}', '${transaction.date}', '${transaction.amount}', '${userEmail}')">
                    <i class="fa-solid fa-exclamation-triangle"></i> View Reason
                </button>
            </div>
        `;
        container.innerHTML += transactionHtml;
    });
}

// Populate successful transactions
function populateSuccessfulTransactions(transactions) {
    const container = document.getElementById('successfulTransactionsContent');
    container.innerHTML = '';

    transactions.forEach(transaction => {
        const transactionHtml = `
            <div class="transaction-item">
                <div class="transaction-avatar">
                    ${getInitials(transaction.user?.name || 'U')}
                </div>
                <div class="transaction-info">
                    <div class="transaction-name">${transaction.user?.name || 'Unknown User'}</div>
                    <div class="transaction-details">
                        ${transaction.user?.unique_id || 'N/A'} • ${transaction.user?.email || 'N/A'} • ${transaction.date}
                    </div>
                </div>
                <div class="transaction-amount">£${transaction.amount}</div>
                <div class="transaction-status status-success">Success</div>
            </div>
        `;
        container.innerHTML += transactionHtml;
    });
}

// Helper function to get initials
function getInitials(name) {
    const parts = name.split(' ');
    const first = parts[0] ? parts[0][0] : '';
    const last = parts.length > 1 ? parts[parts.length - 1][0] : '';
    return first + last;
}

// Populate guest users
function populateGuestUsers(guestUsers) {
    const container = document.getElementById('guestUsersContent');
    container.innerHTML = '';

    guestUsers.forEach(guest => {
        const guestHtml = `
            <div class="transaction-item guest-user" data-guest-id="${guest.id}">
                <div class="transaction-avatar">
                    ${getInitials(guest.name)}
                </div>
                <div class="transaction-info">
                    <div class="transaction-name">${guest.name}</div>
                    <div class="transaction-details">
                        ${guest.unique_id} • ${guest.email} • Original ID: ${guest.original_unique_id}
                    </div>
                </div>
                <div class="guest-status">Guest User</div>
                <div class="guest-action-buttons">
                    <button class="guest-action-btn" onclick="showGuestUserModal(${guest.id}, '${guest.name}', '${guest.unique_id}', '${guest.original_unique_id}')">
                        <i class="fa-solid fa-cog"></i> Manage
                    </button>
                </div>
            </div>
        `;
        container.innerHTML += guestHtml;
    });

    // Update guest count
    updateGuestCount();
}

// Populate no ID flagged transactions
function populateNoIdFlaggedTransactions(transactions) {
    const container = document.getElementById('noIdFlagTransactionsContent');
    if (!container) return;

    container.innerHTML = '';

    transactions.forEach(transaction => {
        const transactionHtml = `
            <div class="transaction-item no-id-flag" data-transaction-id="${transaction.transaction_id}">
                <div class="transaction-avatar">
                    <i class="fa-solid fa-user-slash"></i>
                </div>
                <div class="transaction-info">
                    <div class="transaction-name">${transaction.name || 'No User ID Found'}</div>
                    <div class="transaction-details">
                        Transaction ID: ${transaction.transaction_id} • ${transaction.date} • ${transaction.account}
                    </div>
                </div>
                <div class="transaction-amount">£${transaction.amount}</div>
                <div class="transaction-status status-no-id">No ID</div>
                <div class="no-id-action-buttons">
                    <button class="no-id-action-btn" onclick="showNoIdTransactionModal(this)"
                        data-transaction-id="${transaction.transaction_id}"
                        data-transaction-date="${transaction.date}"
                        data-transaction-amount="${transaction.amount}"
                        data-transaction-account="${transaction.account}"
                        data-transaction-name="${transaction.name || 'No User ID Found'}">
                        <i class="fa-solid fa-cog"></i> Manage
                    </button>
                </div>
            </div>
        `;
        container.innerHTML += transactionHtml;
    });
}

// Modal functions
function showFlaggedModal(transactionId, userName, uniqueId, date, amount, email) {
    currentTransactionId = transactionId;

    document.getElementById('modalUserName').textContent = userName;
    document.getElementById('modalUniqueId').textContent = uniqueId;
    document.getElementById('modalEmail').textContent = email;
    document.getElementById('modalDate').textContent = date;
    document.getElementById('modalAmount').textContent = '£' + amount;

    document.getElementById('flaggedModal').style.display = 'block';
}

function closeFlaggedModal() {
    document.getElementById('flaggedModal').style.display = 'none';
    currentTransactionId = null;
}

// Show custom confirm modal for accept/ignore
function showConfirmModal(type) {
    confirmActionType = type;
    let text = '';
    if (type === 'accept') {
        text = 'Are you sure you want to accept this transaction?';
    } else if (type === 'ignore') {
        text = 'Are you sure you want to delete this transaction? This action cannot be undone.';
    } else if (type === 'remain_guest') {
        text = 'Are you sure you want this user to remain as a guest?';
    } else if (type === 'promote_guest') {
        text = 'Are you sure you want to promote this guest user to a full member?';
    } else if (type === 'assign_guest') {
        text = 'Are you sure you want to assign this guest user to the selected member?';
    } else {
        text = 'Are you sure you want to proceed?';
    }
    document.getElementById('confirmModalText').textContent = text;
    document.getElementById('customConfirmModal').style.display = 'block';
}

// Close custom confirm modal
function closeConfirmModal() {
    document.getElementById('customConfirmModal').style.display = 'none';
    confirmActionType = null;
}

// Accept transaction after confirmation
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
            showCustomSnackbar('Transaction accepted successfully!', 'success');
            closeConfirmModal();
            closeFlaggedModal();
            // Reload page after a short delay to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        })
        .catch(error => {
            console.error('Error accepting transaction:', error);
            showCustomSnackbar('Error accepting transaction', 'error');
            closeConfirmModal();
            closeFlaggedModal();
        });
}

// Ignore transaction after confirmation
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
            showCustomSnackbar('Transaction ignored successfully!', 'success');
            closeConfirmModal();
            closeFlaggedModal();
            // Reload page after a short delay to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        })
        .catch(error => {
            console.error('Error ignoring transaction:', error);
            showCustomSnackbar('Error ignoring transaction', 'error');
            closeConfirmModal();
            closeFlaggedModal();
        });
}

// Guest user modal functions
let currentGuestUser = null;

function showGuestUserModal(guestId, guestName, guestUniqueId, originalUniqueId) {
    currentGuestUser = {
        id: guestId,
        name: guestName,
        unique_id: guestUniqueId,
        original_unique_id: originalUniqueId
    };

    const guestUserInfo = document.getElementById('guestUserInfo');
    guestUserInfo.innerHTML = `
        <div class="guest-user-details">
            <h4 style="margin-bottom: 15px; color: #374151;">Guest User Details:</h4>
            <div class="detail-item">
                <span class="detail-label">Name:</span>
                <span class="detail-value">${guestName}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Guest ID:</span>
                <span class="detail-value">${guestUniqueId}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Original ID:</span>
                <span class="detail-value">${originalUniqueId}</span>
            </div>
        </div>
    `;

    document.getElementById('guestUserModal').style.display = 'block';
}

// Function to show guest user modal from button with data attributes
function showGuestUserModalFromButton(button) {
    const guestId = button.getAttribute('data-guest-id');
    const guestName = button.getAttribute('data-guest-name');
    const guestUniqueId = button.getAttribute('data-guest-unique-id');
    const guestOriginalUniqueId = button.getAttribute('data-guest-original-unique-id');

    showGuestUserModal(guestId, guestName, guestUniqueId, guestOriginalUniqueId);
}

function closeGuestUserModal() {
    document.getElementById('guestUserModal').style.display = 'none';
    // Don't set currentGuestUser to null here, as we need it for the actions
    document.getElementById('assignToMemberSection').style.display = 'none';

    // Reset the assignment section
    const assignSection = document.getElementById('assignToMemberSection');
    if (assignSection) {
        assignSection.innerHTML = `
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
                Select Existing Member:
            </label>
            <select id="existingMemberSelect" class="guest-select-input">
                <option value="">Choose a member...</option>
            </select>
            <button id="assignSubmitBtn" class="guest-action-btn" style="margin-top: 15px; display: none;" onclick="confirmAssignGuest()">
                <i class="fa-solid fa-check"></i> Assign to Selected Member
            </button>
        `;
    }
}

function handleGuestAction(action) {
    if (!currentGuestUser) return;

    switch (action) {
        case 'remain':
            showConfirmModal('remain_guest');
            break;
        case 'promote':
            showConfirmModal('promote_guest');
            break;
        case 'assign':
            showAssignToMemberSection();
            break;
    }
}

// Function to handle remain as guest action
function remainAsGuest() {
    if (!currentGuestUser) {
        showCustomSnackbar('No guest user selected', 'error');
        return;
    }

    showCustomSnackbar('Guest user will remain as guest', 'info');
    closeGuestUserModal();
    closeConfirmModal();

    // Hide the manage buttons for this guest user (handle both import and persistent sections)
    const guestUserElements = document.querySelectorAll(`[data-guest-id="${currentGuestUser.id}"]`);
    guestUserElements.forEach(guestUserElement => {
        if (guestUserElement) {
            const actionButtons = guestUserElement.querySelector('.guest-action-buttons');
            if (actionButtons) {
                actionButtons.style.display = 'none';
            }
            // Add a "Remains as Guest" badge
            const statusBadge = guestUserElement.querySelector('.guest-status');
            if (statusBadge) {
                statusBadge.textContent = 'Remains as Guest';
                statusBadge.className = 'guest-status remains-guest';
            }
        }
    });

    // Clear currentGuestUser after successful action
    currentGuestUser = null;
}

function showAssignToMemberSection() {
    const section = document.getElementById('assignToMemberSection');
    const select = document.getElementById('existingMemberSelect');

    // Clear previous content and add submit button
    section.innerHTML = `
        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
            Select Existing Member:
        </label>
        <select id="existingMemberSelect" class="guest-select-input">
            <option value="">Choose a member...</option>
        </select>
        <button id="assignSubmitBtn" class="guest-action-btn" style="margin-top: 15px; display: none;" onclick="confirmAssignGuest()">
            <i class="fa-solid fa-check"></i> Assign to Selected Member
        </button>
    `;

    // Populate with existing members
    fetch('/members/list')
        .then(response => response.json())
        .then(data => {
            const selectElement = document.getElementById('existingMemberSelect');
            selectElement.innerHTML = '<option value="">Choose a member...</option>';
            data.members.forEach(member => {
                selectElement.innerHTML += `<option value="${member.id}">${member.name} (${member.unique_id})</option>`;
            });
            section.style.display = 'block';

            // Add change event listener to show/hide submit button
            selectElement.addEventListener('change', function () {
                const submitBtn = document.getElementById('assignSubmitBtn');
                if (this.value) {
                    submitBtn.style.display = 'inline-block';
                } else {
                    submitBtn.style.display = 'none';
                }
            });
        })
        .catch(error => {
            console.error('Error loading members:', error);
            section.style.display = 'block';
        });
}

// Attach confirm modal OK button event
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('flaggedModal');
    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === this) {
                closeFlaggedModal();
            }
        });
    }

    // Guest user modal
    const guestModal = document.getElementById('guestUserModal');
    if (guestModal) {
        guestModal.addEventListener('click', function (e) {
            if (e.target === this) {
                closeGuestUserModal();
            }
        });
    }

    // Confirm modal OK button
    const okBtn = document.getElementById('confirmModalOkBtn');
    if (okBtn) {
        okBtn.onclick = function () {
            if (confirmActionType === 'accept') {
                doAcceptTransaction();
            } else if (confirmActionType === 'ignore') {
                doIgnoreTransaction();
            } else if (confirmActionType === 'remain_guest') {
                remainAsGuest();
            } else if (confirmActionType === 'promote_guest') {
                promoteGuestUser();
            } else if (confirmActionType === 'assign_guest') {
                assignGuestToMember();
            } else {
                closeConfirmModal();
            }
        };
    }

    // Initialize the persistent guest members section
    updatePersistentGuestCount();
});

function promoteGuestUser() {
    if (!currentGuestUser) {
        showCustomSnackbar('No guest user selected', 'error');
        return;
    }

    fetch(`/guest/promote/${currentGuestUser.id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showCustomSnackbar('Guest user promoted successfully!', 'success');
                closeGuestUserModal();
                closeConfirmModal();
                // Reload page after a short delay to show updated data
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showCustomSnackbar(data.message || 'Failed to promote guest user', 'error');
            }
        })
        .catch(error => {
            console.error('Error promoting guest user:', error);
            showCustomSnackbar('Error promoting guest user', 'error');
        });
}

function assignGuestToMember() {
    if (!currentGuestUser) {
        showCustomSnackbar('No guest user selected', 'error');
        return;
    }

    const memberId = document.getElementById('existingMemberSelect').value;
    if (!memberId) {
        showCustomSnackbar('Please select a member', 'warning');
        return;
    }

    fetch(`/guest/assign/${currentGuestUser.id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ member_id: memberId })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showCustomSnackbar('Guest user assigned successfully!', 'success');
                closeGuestUserModal();
                closeConfirmModal();
                // Reload page after a short delay to show updated data
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showCustomSnackbar(data.message || 'Failed to assign guest user', 'error');
            }
        })
        .catch(error => {
            console.error('Error assigning guest user:', error);
            showCustomSnackbar('Error assigning guest user', 'error');
        });
}

// Function to confirm guest assignment
function confirmAssignGuest() {
    if (!currentGuestUser) {
        showCustomSnackbar('No guest user selected', 'error');
        return;
    }

    const memberId = document.getElementById('existingMemberSelect').value;
    if (!memberId) {
        showCustomSnackbar('Please select a member', 'warning');
        return;
    }

    // Show confirmation modal
    confirmActionType = 'assign_guest';
    document.getElementById('confirmModalText').textContent = 'Are you sure you want to assign this guest user to the selected member?';
    document.getElementById('customConfirmModal').style.display = 'block';
}

// Helper function to remove guest user from UI
function removeGuestUserFromUI(guestId) {
    const guestUserElements = document.querySelectorAll(`[data-guest-id="${guestId}"]`);
    guestUserElements.forEach(guestUserElement => {
        if (guestUserElement) {
            guestUserElement.style.transition = 'opacity 0.3s ease';
            guestUserElement.style.opacity = '0';
            setTimeout(() => {
                guestUserElement.remove();
            }, 300);
        }
    });

    // Update guest count
    updateGuestCount();

    // Keep guest users section hidden (functionality preserved but not displayed)
    const guestUsersSection = document.getElementById('guestUsersSection');
    if (guestUsersSection) {
        // Always keep guest section hidden regardless of guest user count
        guestUsersSection.style.display = 'none';
    }

    // Update persistent guest members count
    updatePersistentGuestCount();
}

// Helper function to remove flagged transaction from UI
function removeFlaggedTransactionFromUI(transactionId) {
    const transactionElement = document.querySelector(`[data-transaction-id="${transactionId}"]`);
    if (transactionElement) {
        transactionElement.style.transition = 'opacity 0.3s ease';
        transactionElement.style.opacity = '0';
        setTimeout(() => {
            transactionElement.remove();
        }, 300);
    }
}

// Helper function to update guest count
function updateGuestCount() {
    const guestUsers = document.querySelectorAll('.transaction-item.guest-user');
    const guestCountElement = document.getElementById('guestCount');
    if (guestCountElement) {
        guestCountElement.textContent = `${guestUsers.length} Guest Users`;
    }
}

// Helper function to update flagged count
function updateFlaggedCount() {
    const flaggedTransactions = document.querySelectorAll('.transaction-item.flagged');
    const flaggedCountElement = document.getElementById('flaggedCount');
    if (flaggedCountElement) {
        flaggedCountElement.textContent = `${flaggedTransactions.length} Flagged`;
    }
}

// Helper function to update flagged section visibility
function updateFlaggedSectionVisibility() {
    const flaggedSection = document.getElementById('flaggedTransactionsSection');
    const flaggedContent = document.getElementById('flaggedTransactionsContent');

    if (flaggedSection && flaggedContent) {
        if (flaggedContent.children.length > 0) {
            flaggedSection.style.display = 'block';
        } else {
            flaggedSection.style.display = 'none';
        }
    }
}

// Helper function to update persistent guest count
function updatePersistentGuestCount() {
    const persistentGuestUsers = document.querySelectorAll('#persistentGuestMembersContent .transaction-item.guest-user');
    const persistentGuestSection = document.getElementById('persistentGuestMembersSection');

    if (persistentGuestUsers.length === 0 && persistentGuestSection) {
        persistentGuestSection.style.display = 'none';
    } else if (persistentGuestUsers.length > 0 && persistentGuestSection) {
        persistentGuestSection.style.display = 'block';
    }
}

// No ID Transaction Modal Functions
let currentNoIdTransactionId = null;
let allMembers = []; // Cache for all members
let noIdConfirmActionType = null; // Track what action to confirm

function showNoIdTransactionModal(button) {
    const transactionId = button.getAttribute('data-transaction-id');
    const transactionDate = button.getAttribute('data-transaction-date');
    const transactionAmount = button.getAttribute('data-transaction-amount');
    const transactionAccount = button.getAttribute('data-transaction-account');
    const transactionName = button.getAttribute('data-transaction-name');

    currentNoIdTransactionId = transactionId;

    // Populate transaction info
    const transactionInfo = document.getElementById('noIdTransactionInfo');
    transactionInfo.innerHTML = `
        <div style="background: #f9fafb; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
            <h5 style="margin: 0 0 10px 0; color: #374151;">Transaction Details</h5>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 14px;">
                <div><strong>Transaction ID:</strong> ${transactionId}</div>
                <div><strong>Date:</strong> ${transactionDate}</div>
                <div><strong>Amount:</strong> £${transactionAmount}</div>
                <div><strong>Account:</strong> ${transactionAccount}</div>
            </div>
            <div style="margin-top: 10px; font-size: 14px;">
                <strong>Name from Transaction:</strong> ${transactionName}
            </div>
        </div>
    `;

    // Load all members for the searchable dropdown
    loadAllMembers();

    document.getElementById('noIdTransactionModal').style.display = 'flex';
}

function closeNoIdTransactionModal() {
    document.getElementById('noIdTransactionModal').style.display = 'none';
    currentNoIdTransactionId = null;

    // Reset form sections
    document.getElementById('assignToMemberSectionNoId').style.display = 'none';

    // Reset search input and dropdown
    const searchInput = document.getElementById('memberSearchInput');
    const selectedMemberIdInput = document.getElementById('selectedMemberId');
    const dropdownList = document.getElementById('memberDropdownList');

    if (searchInput) {
        searchInput.value = '';
        searchInput.style.borderColor = '#d1d5db';
        // Remove event listeners to prevent memory leaks
        searchInput.removeEventListener('input', handleSearchInput);
        searchInput.removeEventListener('focus', handleSearchFocus);
        searchInput.removeEventListener('keydown', handleSearchKeydown);
    }
    if (selectedMemberIdInput) {
        selectedMemberIdInput.value = '';
    }
    if (dropdownList) {
        dropdownList.style.display = 'none';
        dropdownList.innerHTML = '';
    }
}

function handleNoIdAction(action) {
    // Hide all sections first
    document.getElementById('assignToMemberSectionNoId').style.display = 'none';

    switch(action) {
        case 'ignore':
            showNoIdConfirmModal('ignore', 'Are you sure you want to ignore this transaction? This action cannot be undone.');
            break;
        case 'assign':
            document.getElementById('assignToMemberSectionNoId').style.display = 'block';
            // Focus on search input when section is shown
            setTimeout(() => {
                document.getElementById('memberSearchInput').focus();
            }, 100);
            break;
    }
}

// Load all members for searchable dropdown
function loadAllMembers() {
    console.log('Loading all members...');

    if (allMembers.length > 0) {
        console.log('Using cached members:', allMembers.length);
        setupSearchableDropdown();
        return;
    }

    console.log('Fetching members from server...');

    fetch('/admin/get-all-members', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            allMembers = data.members;
            console.log('Members loaded successfully:', allMembers.length);
            setupSearchableDropdown();
        } else {
            console.error('Failed to load members:', data.message);
            showCustomSnackbar('Failed to load members: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error loading members:', error);
        showCustomSnackbar('Failed to load members. Please try again.', 'error');
    });
}

// Setup searchable dropdown functionality
function setupSearchableDropdown() {
    console.log('Setting up searchable dropdown...');

    const searchInput = document.getElementById('memberSearchInput');
    const dropdownList = document.getElementById('memberDropdownList');

    if (!searchInput || !dropdownList) {
        console.error('Search input or dropdown list not found');
        console.log('searchInput:', searchInput);
        console.log('dropdownList:', dropdownList);
        return;
    }

    console.log('Found search elements, setting up event listeners...');

    // Remove any existing event listeners to prevent duplicates
    searchInput.removeEventListener('input', handleSearchInput);
    searchInput.removeEventListener('focus', handleSearchFocus);
    searchInput.removeEventListener('keydown', handleSearchKeydown);

    // Add event listeners
    searchInput.addEventListener('input', handleSearchInput);
    searchInput.addEventListener('focus', handleSearchFocus);
    searchInput.addEventListener('keydown', handleSearchKeydown);

    console.log('Event listeners added successfully');

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.searchable-dropdown')) {
            dropdownList.style.display = 'none';
        }
    });

    // Test the search functionality by showing all members initially
    console.log('Testing initial display with all members...');
    filterAndDisplayMembers('');
}

// Handle search input
function handleSearchInput(event) {
    const searchTerm = event.target.value.toLowerCase().trim();
    console.log('Search input changed:', searchTerm);
    filterAndDisplayMembers(searchTerm);
}

// Handle search focus
function handleSearchFocus(event) {
    const searchTerm = event.target.value.toLowerCase().trim();
    console.log('Search input focused:', searchTerm);
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
        // Select focused item
        const focusedItem = dropdownList.querySelector('.keyboard-focus');
        if (focusedItem) {
            focusedItem.click();
        }
    } else if (event.key === 'Escape') {
        dropdownList.style.display = 'none';
    }
}

// Update keyboard focus
function updateKeyboardFocus(items, index) {
    items.forEach(item => item.classList.remove('keyboard-focus'));
    if (items[index]) {
        items[index].classList.add('keyboard-focus');
        items[index].scrollIntoView({ block: 'nearest' });
    }
}

// Filter and display members in dropdown
function filterAndDisplayMembers(searchTerm) {
    const dropdownList = document.getElementById('memberDropdownList');

    if (!dropdownList) {
        console.error('Dropdown list not found');
        return;
    }

    console.log('Filtering members with search term:', searchTerm);
    console.log('Total members available:', allMembers.length);

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

            console.log(`Checking member: ${member.name}, nameMatch: ${nameMatch}, emailMatch: ${emailMatch}, idMatch: ${idMatch}`);

            return nameMatch || emailMatch || idMatch;
        });
    }

    console.log('Filtered members count:', filteredMembers.length);

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
                // Remove keyboard focus from other items
                dropdownList.querySelectorAll('.dropdown-item').forEach(item => {
                    item.classList.remove('keyboard-focus');
                });
            });

            dropdownList.appendChild(memberItem);
        });
    }

    dropdownList.style.display = 'block';
}

// Helper function to escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Select a member from dropdown
function selectMember(member) {
    console.log('Selecting member:', member);

    const searchInput = document.getElementById('memberSearchInput');
    const dropdownList = document.getElementById('memberDropdownList');
    const selectedMemberIdInput = document.getElementById('selectedMemberId');

    if (!searchInput || !dropdownList || !selectedMemberIdInput) {
        console.error('Required elements not found for member selection');
        return;
    }

    searchInput.value = `${member.name} (${member.unique_id})`;
    selectedMemberIdInput.value = member.id;
    dropdownList.style.display = 'none';

    console.log('Member selected successfully. ID:', member.id);

    // Add visual feedback
    searchInput.style.borderColor = '#10b981';
    setTimeout(() => {
        searchInput.style.borderColor = '#667eea';
    }, 1000);
}

// Ignore no ID transaction
function ignoreNoIdTransaction() {
    if (!currentNoIdTransactionId) return;

    fetch('/admin/transactions/ignore-no-id', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            transaction_id: currentNoIdTransactionId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showCustomSnackbar('Transaction ignored successfully!', 'success');
            closeNoIdTransactionModal();
            // Reload page after a short delay to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showCustomSnackbar('Failed to ignore transaction: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showCustomSnackbar('An error occurred while ignoring the transaction', 'error');
    });
}

// Remove no ID transaction from UI
function removeNoIdTransactionFromUI(transactionId) {
    const transactionElement = document.querySelector(`[data-transaction-id="${transactionId}"].no-id-flag`);
    if (transactionElement) {
        transactionElement.style.transition = 'opacity 0.3s ease';
        transactionElement.style.opacity = '0';
        setTimeout(() => {
            transactionElement.remove();
        }, 300);
    }
}

// Helper function to update no ID flagged count
function updateNoIdFlaggedCount() {
    const noIdTransactions = document.querySelectorAll('.transaction-item.no-id-flag');
    const noIdCountElement = document.getElementById('noIdFlaggedCount');
    if (noIdCountElement) {
        noIdCountElement.textContent = `${noIdTransactions.length} No ID Flag`;
    }

    // Also update section visibility
    updateNoIdFlaggedSectionVisibility();
}

// Helper function to update no ID flagged section visibility
function updateNoIdFlaggedSectionVisibility() {
    const noIdSection = document.getElementById('noIdFlagTransactionsSection');
    const noIdContent = document.getElementById('noIdFlagTransactionsContent');

    if (noIdSection && noIdContent) {
        if (noIdContent.children.length > 0) {
            noIdSection.style.display = 'block';
        } else {
            noIdSection.style.display = 'none';
        }
    }
}

// Assign no ID transaction to existing member
function assignToExistingMember() {
    const selectedMemberId = document.getElementById('selectedMemberId').value;
    const searchInput = document.getElementById('memberSearchInput');

    if (!selectedMemberId) {
        showCustomSnackbar('Please select a member first', 'error');
        return;
    }

    if (!currentNoIdTransactionId) {
        showCustomSnackbar('No transaction selected', 'error');
        return;
    }

    // Get member name from search input for confirmation
    const memberName = searchInput.value;
    showNoIdConfirmModal('assign', `Are you sure you want to assign this transaction to ${memberName}?`);
}

// Show confirmation modal for no ID transactions
function showNoIdConfirmModal(actionType, message) {
    noIdConfirmActionType = actionType;

    document.getElementById('noIdConfirmMessage').textContent = message;

    const confirmButton = document.getElementById('noIdConfirmButton');
    if (actionType === 'assign') {
        confirmButton.className = 'btn-confirm assign-action';
        confirmButton.innerHTML = '<i class="fa-solid fa-check"></i> Assign';
    } else {
        confirmButton.className = 'btn-confirm';
        confirmButton.innerHTML = '<i class="fa-solid fa-check"></i> Confirm';
    }

    document.getElementById('noIdConfirmModal').style.display = 'flex';
}

// Close confirmation modal for no ID transactions
function closeNoIdConfirmModal() {
    document.getElementById('noIdConfirmModal').style.display = 'none';
    noIdConfirmActionType = null;
}

// Execute the confirmed action for no ID transactions
function executeNoIdAction() {
    if (noIdConfirmActionType === 'ignore') {
        doIgnoreNoIdTransaction();
    } else if (noIdConfirmActionType === 'assign') {
        doAssignToExistingMember();
    }
    closeNoIdConfirmModal();
}

// Actually perform the ignore action
function doIgnoreNoIdTransaction() {
    if (!currentNoIdTransactionId) return;

    fetch('/admin/transactions/ignore-no-id', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            transaction_id: currentNoIdTransactionId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showCustomSnackbar('Transaction ignored successfully!', 'success');
            closeNoIdTransactionModal();
            // Reload page after a short delay to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showCustomSnackbar('Failed to ignore transaction: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showCustomSnackbar('An error occurred while ignoring the transaction', 'error');
    });
}

// Actually perform the assign action
function doAssignToExistingMember() {
    const selectedMemberId = document.getElementById('selectedMemberId').value;

    if (!selectedMemberId || !currentNoIdTransactionId) return;

    fetch('/admin/transactions/assign-no-id', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            transaction_id: currentNoIdTransactionId,
            member_id: selectedMemberId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showCustomSnackbar('Transaction assigned successfully!', 'success');
            closeNoIdTransactionModal();
            // Reload page after a short delay to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showCustomSnackbar('Failed to assign transaction: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showCustomSnackbar('An error occurred while assigning the transaction', 'error');
    });
}


