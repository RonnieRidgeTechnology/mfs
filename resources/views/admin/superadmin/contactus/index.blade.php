@extends('layouts.admin')
<style>
    input:focus {
        outline: none;
        border: none;
    }
    .skeleton-loader {
        display: none;
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
    }
    .skeleton-loader.show {
        display: block;
    }
    .skeleton-row {
        display: flex;
        align-items: center;
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
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
    .skeleton-badge {
        width: 80px;
        height: 24px;
        background: #e9ecef;
        border-radius: 12px;
    }
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
    /* Custom Modal Styles */
    .custom-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100vw;
        height: 100vh;
        overflow: auto;
        justify-content: center;
        align-items: center;
        /* No background color here */
    }
    .custom-modal.show {
        display: flex;
    }
    .custom-modal-content {
        /* No background color here */
        border-radius: 12px;
        padding: 32px 28px 24px 28px;
        min-width: 340px;
        max-width: 95vw;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        background: #ffffff;
        position: relative;
        animation: customModalFadeIn 0.2s;
    }
    @keyframes customModalFadeIn {
        from { transform: translateY(30px) scale(0.98); opacity: 0; }
        to { transform: translateY(0) scale(1); opacity: 1; }
    }
    .custom-modal-close {
        position: absolute;
        top: 16px;
        right: 16px;
        background: none;
        border: none;
        font-size: 1.5em;
        color: #64748b;
        cursor: pointer;
        transition: color 0.2s;
    }
    .custom-modal-close:hover {
        color: #dc3545;
    }
    .custom-modal-title {
        font-size: 1.2em;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .custom-modal-details-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
    }
    .custom-modal-details-table td {
        padding: 7px 0;
        vertical-align: top;
        color: #334155;
        font-size: 1em;
    }
    .custom-modal-details-table td:first-child {
        font-weight: 600;
        color: #6366f1;
        width: 110px;
        padding-right: 18px;
        white-space: nowrap;
    }
    .custom-modal-details-message {
        /* No background color here */
        border-radius: 8px;
        padding: 12px 14px;
        color: #475569;
        font-size: 1em;
        margin-top: 8px;
        word-break: break-word;
        max-height: 200px;
        overflow-y: auto;
    }
</style>
@section('content')
    <main class="main-content">
        @include('layouts.header')
        <div class="content">
            <div class="table-section">
                <div
                    style="display: flex ;align-items: center;justify-content: space-between;padding: 20px 22px 15px 22px;">
                    <h2>Contact Us Messages</h2>
                </div>

                <!-- Filter Container -->
                <div class="filter-container">
                    <div class="filter-header">
                        <div style="margin: 18px 0 8px 0;">
                            <h3 class="filter-title">
                                <i class="fa-solid fa-filter" style="margin-right: 8px; color: #6366f1;"></i>
                                Advanced Filters
                            </h3>
                            <p style="color: #475569; font-size: 0.85em; margin-bottom: 7px;">
                                Use the advanced filters below to quickly locate specific contact messages by status, date,
                                or email.
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

                        <form method="GET" action="{{ route('admin.contactus.index') }}" class="filter-search-form"
                            style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                            <input type="date" name="start_date" value="{{ request('start_date') }}" class="custom-input"
                                style="padding: 6px 12px; border: 1px solid #e2e8f0; border-radius: 6px; background: #f8fafc; color: #495057; font-size: 14px;"
                                placeholder="Start Date">
                            <span style="color: #888;">to</span>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="custom-input"
                                style="padding: 6px 12px; border: 1px solid #e2e8f0; border-radius: 6px; background: #f8fafc; color: #495057; font-size: 14px;"
                                placeholder="End Date">
                            <input type="text" name="search" value="{{ request('search') }}" class="custom-input"
                                style="padding: 6px 12px; border: 1px solid #e2e8f0; border-radius: 6px; background: #f8fafc; color: #495057; font-size: 14px; min-width: 220px;"
                                placeholder="Name, Email, Phone, Address, or Message">
                            <button type="submit" class="action-button primary"
                                style="padding: 6px 18px; border-radius: 6px; background: var(--primary); color: #fff; border: none; font-size:14px; display: flex; align-items: center; gap: 6px;">
                                <i class="fa-light fa-magnifying-glass"></i>
                                <span>Search</span>
                            </button>
                        </form>
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
                                    Use the table controls below to show or hide columns, making it easy to customize your
                                    view and focus on the message details that matter most to you.
                                </p>
                            </div>
                            <!-- Column Visibility Controls -->
                            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showId" checked onchange="toggleColumn('id')"
                                        style="margin: 0;">
                                    <label for="showId" style="margin-left: 5px; cursor:pointer;">ID</label>
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showName" checked onchange="toggleColumn('name')"
                                        style="margin: 0;">
                                    <label for="showName" style="margin-left: 5px; cursor:pointer;">Name</label>
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showEmail" checked onchange="toggleColumn('email')"
                                        style="margin: 0;">
                                    <label for="showEmail" style="margin-left: 5px; cursor:pointer;">Email</label>
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showSubject" checked onchange="toggleColumn('subject')"
                                        style="margin: 0;">
                                    <label for="showSubject" style="margin-left: 5px; cursor:pointer;">Phone</label>
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showAddress" checked onchange="toggleColumn('address')"
                                        style="margin: 0;">
                                    <label for="showAddress" style="margin-left: 5px; cursor:pointer;">Address</label>
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showMessage" checked onchange="toggleColumn('message')"
                                        style="margin: 0;">
                                    <label for="showMessage" style="margin-left: 5px; cursor:pointer;">Message</label>
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showCreated" checked onchange="toggleColumn('created')"
                                        style="margin: 0;">
                                    <label for="showCreated" style="margin-left: 5px; cursor:pointer;">Received At</label>
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showActions" checked onchange="toggleColumn('actions')"
                                        style="margin: 0;">
                                    <label for="showActions" style="margin-left: 5px; cursor:pointer;">Actions</label>
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

                <!-- Skeleton Loader -->
                <div id="skeleton-loader" class="skeleton-loader">
                    @for($i = 0; $i < 10; $i++)
                        <div class="skeleton-row">
                            <div style="width: 60px;">
                                <div class="skeleton-line short"></div>
                            </div>
                            <div style="flex: 2;">
                                <div class="skeleton-line medium"></div>
                            </div>
                            <div style="flex: 2;">
                                <div class="skeleton-line medium"></div>
                            </div>
                            <div style="flex: 2;">
                                <div class="skeleton-line medium"></div>
                            </div>
                            <div style="flex: 2;">
                                <div class="skeleton-line medium"></div>
                            </div>
                            <div style="flex: 2;">
                                <div class="skeleton-line medium"></div>
                            </div>
                            <div style="flex: 2;">
                                <div class="skeleton-line medium"></div>
                            </div>
                            <div class="skeleton-badge"></div>
                            <div style="width: 120px;">
                                <div class="skeleton-line short"></div>
                            </div>
                        </div>
                    @endfor
                </div>

                <!-- Table Content -->
                <div id="table-content" class="table-container" style="display: none;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th data-col="id">ID</th>
                                <th data-col="name">Name</th>
                                <th data-col="email">Email</th>
                                <th data-col="subject">Phone</th>
                                <th data-col="address">Address</th>
                                <th data-col="message">Message</th>
                                <th data-col="created">Received At</th>
                                <th data-col="actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($contacts) && $contacts->isEmpty())
                                <tr>
                                    <td colspan="9" style="text-align: center; padding: 30px; color: #888;">
                                        <i class="fa-light fa-circle-exclamation"
                                            style="font-size: 2em; color: #ff9800; margin-bottom: 8px;"></i>
                                        <div style="margin-top: 8px;">No contact Us found.</div>
                                    </td>
                                </tr>
                            @elseif(isset($contacts))
                                @foreach($contacts as $contact)
                                    <tr>
                                        <td data-col="id">{{ $loop->iteration }}</td>
                                        <td data-col="name" style="max-width: 180px; word-break: break-word;">
                                            {{ $contact->name }}
                                        </td>
                                        <td data-col="email" style="max-width: 220px; word-break: break-word;">
                                            {{ $contact->email }}
                                        </td>
                                        <td data-col="subject" style="max-width: 220px; word-break: break-word;">
                                            {{ $contact->phone }}
                                        </td>
                                        <td data-col="address" style="max-width: 300px; word-break: break-word;">
                                            {{ $contact->address }}
                                        </td>
                                        <td data-col="message" style="max-width: 300px; word-break: break-word;">
                                            {{ \Illuminate\Support\Str::limit($contact->message, 120) }}
                                        </td>
                                        <td data-col="created">
                                            <div class="activity-info">
                                                <i class="fa-light fa-clock"></i>
                                                <span>{{ \Carbon\Carbon::parse($contact->created_at)->diffForHumans() }}</span>
                                            </div>
                                        </td>
                                        <td data-col="actions">
                                            <div class="action-buttons" style="display: flex; gap: 6px;">
                                                <!-- View Button -->
                                                <button type="button"
                                                    class="action-btn view"
                                                    style="background: linear-gradient(135deg, #60a5fa 0%, #6366f1 100%); color: #fff; border: none; border-radius: 6px; padding: 6px 14px; font-size: 14px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 5px;"
                                                    onclick="openContactModal(this)"
                                                    data-id="{{ $contact->id }}"
                                                    data-name="{{ $contact->name }}"
                                                    data-email="{{ $contact->email }}"
                                                    data-phone="{{ $contact->phone }}"
                                                    data-address="{{ $contact->address }}"
                                                    data-message="{{ htmlspecialchars($contact->message, ENT_QUOTES) }}"
                                                    data-created="{{ \Carbon\Carbon::parse($contact->created_at)->format('Y-m-d H:i:s') }}"
                                                >
                                                    <i class="fa-light fa-eye"></i>
                                                    <span>View</span>
                                                </button>
                                                <!-- Delete Button -->
                                                <form action="{{ route('admin.contactus.delete', $contact->id) }}" method="POST"
                                                    style="display:inline;"
                                                    onsubmit="return confirm('Are you sure you want to delete this contact us?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn delete">
                                                        <span class="btn-content">
                                                            <i class="fa-light fa-trash"></i>
                                                            <p class="btn-text">Delete</p>
                                                        </span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div id="pagination-container">
                        @if(isset($contacts))
                            @include('layouts.custom_pagination', ['paginator' => $contacts])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Contact Detail Modal -->
    <div id="contactDetailModal" class="custom-modal" tabindex="-1">
        <div class="custom-modal-content">
            <button class="custom-modal-close" onclick="closeContactModal()" aria-label="Close">&times;</button>
            <div class="custom-modal-title">
                <i class="fa-solid fa-user"></i>
                <span>Contact Details</span>
            </div>
            <table class="custom-modal-details-table">
                <tr>
                    <td>ID</td>
                    <td id="modal-contact-id"></td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td id="modal-contact-name"></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td id="modal-contact-email"></td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td id="modal-contact-phone"></td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td id="modal-contact-address"></td>
                </tr>
                <tr>
                    <td>Received At</td>
                    <td id="modal-contact-created"></td>
                </tr>
            </table>
            <div>
                <div style="font-weight:600; color:#6366f1; margin-bottom: 4px;">Message</div>
                <div class="custom-modal-details-message" id="modal-contact-message"></div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Skeleton Loader Logic
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('skeleton-loader').classList.add('show');
            document.getElementById('table-content').style.display = 'none';

            setTimeout(function () {
                document.getElementById('skeleton-loader').classList.remove('show');
                document.getElementById('table-content').style.display = '';
            }, 1200);
        });

        // Reset Filters Logic
        function resetFilters() {
            window.location.href = "{{ route('admin.contactus.index') }}";
        }

        // Column visibility control functions
        // Map columnType to data-col attribute
        const columnMap = {
            id: 'id',
            name: 'name',
            email: 'email',
            subject: 'subject',
            address: 'address',
            message: 'message',
            status: 'status',
            created: 'created',
            actions: 'actions'
        };

        function toggleColumn(columnType) {
            const colAttr = columnMap[columnType];
            if (!colAttr) return;

            // Toggle header
            const headerCell = document.querySelector('.data-table thead th[data-col="' + colAttr + '"]');
            // Find all data cells for this column
            const dataCells = document.querySelectorAll('.data-table tbody td[data-col="' + colAttr + '"]');

            // Determine current state
            const isHidden = headerCell && headerCell.style.display === 'none';

            // Toggle header
            if (headerCell) headerCell.style.display = isHidden ? '' : 'none';

            // Toggle data cells
            dataCells.forEach(cell => {
                cell.style.display = isHidden ? '' : 'none';
            });
        }

        function showAllColumns() {
            // Set all checkboxes to checked
            document.querySelectorAll('input[type="checkbox"][id^="show"]').forEach(checkbox => {
                checkbox.checked = true;
            });

            // Show all columns
            Object.values(columnMap).forEach(colAttr => {
                const headerCell = document.querySelector('.data-table thead th[data-col="' + colAttr + '"]');
                const dataCells = document.querySelectorAll('.data-table tbody td[data-col="' + colAttr + '"]');
                if (headerCell) headerCell.style.display = '';
                dataCells.forEach(cell => cell.style.display = '');
            });
        }

        function hideAllColumns() {
            // Set all checkboxes to unchecked
            document.querySelectorAll('input[type="checkbox"][id^="show"]').forEach(checkbox => {
                checkbox.checked = false;
            });

            // Hide all columns
            Object.values(columnMap).forEach(colAttr => {
                const headerCell = document.querySelector('.data-table thead th[data-col="' + colAttr + '"]');
                const dataCells = document.querySelectorAll('.data-table tbody td[data-col="' + colAttr + '"]');
                if (headerCell) headerCell.style.display = 'none';
                dataCells.forEach(cell => cell.style.display = 'none');
            });
        }

        // Initialize column visibility based on checkboxes
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('input[type="checkbox"][id^="show"]').forEach(checkbox => {
                const columnType = checkbox.id.replace('show', '').toLowerCase();
                if (!checkbox.checked) {
                    toggleColumn(columnType);
                }
            });
        });

        // Modal Logic
        function openContactModal(btn) {
            // Get data attributes
            document.getElementById('modal-contact-id').textContent = btn.getAttribute('data-id') || '';
            document.getElementById('modal-contact-name').textContent = btn.getAttribute('data-name') || '';
            document.getElementById('modal-contact-email').textContent = btn.getAttribute('data-email') || '';
            document.getElementById('modal-contact-phone').textContent = btn.getAttribute('data-phone') || '';
            document.getElementById('modal-contact-address').textContent = btn.getAttribute('data-address') || '';
            document.getElementById('modal-contact-created').textContent = btn.getAttribute('data-created') || '';
            // For message, decode HTML entities
            let msg = btn.getAttribute('data-message') || '';
            document.getElementById('modal-contact-message').textContent = msg;

            document.getElementById('contactDetailModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        function closeContactModal() {
            document.getElementById('contactDetailModal').classList.remove('show');
            document.body.style.overflow = '';
        }
        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === "Escape") {
                closeContactModal();
            }
        });
        // Close modal on click outside content
        document.getElementById('contactDetailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeContactModal();
            }
        });
    </script>
@endsection
