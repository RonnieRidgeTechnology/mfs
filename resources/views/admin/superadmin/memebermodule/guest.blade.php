@extends('layouts.admin')
<style>
    input:focus {
        outline: none;
        border: none;
    }

    /* Simple Skeleton Loader */
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

    .skeleton-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        margin-right: 15px;
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

    .alphabet-btn.active {
        background: #2563eb !important;
        color: #fff !important;
        font-weight: 700 !important;
    }
</style>
@section('content')
    <main class="main-content">
        @include('layouts.header')
        <div class="content">
            <div class="table-section">
                <div style="display: flex; justify-content: space-between; padding: 20px 22px 15px 22px;">
                    <h2>Members Guest ({{ $members->total() }})</h2>
                </div>
                <!-- Alphabet Filter Container -->
                <div class="alphabet-container">
                    <div class="alphabet-header">
                        <div>
                            <div class="alphabet-title">
                                <i class="fa-solid fa-filter"></i>
                                Alphabet Filter
                            </div>
                            <div class="alphabet-description">
                                Click on any letter to filter members by names or emails starting with that
                                letter.
                                This helps you quickly find specific members in large datasets.
                            </div>
                        </div>
                        <button class="alphabet-reset-btn" id="alphabetResetBtn" type="button">
                            <i class="fa-solid fa-rotate"></i>
                            Reset Filter
                        </button>
                    </div>
                    <div class="alphabet-grid" id="alphabetGrid">
                        <button class="alphabet-btn" data-letter="" type="button">ALL</button>
                        @foreach(range('A', 'Z') as $letter)
                            <button class="alphabet-btn" data-letter="{{ $letter }}" type="button">{{ $letter }}</button>
                        @endforeach
                    </div>
                </div>
                <!-- Filter Container -->
                <div class="filter-container">
                    <div class="filter-header">
                        <div style="margin: 18px 0 8px 0;">

                            <h3 class="filter-title">
                                <i class="fa-solid fa-filter" style="margin-right: 8px; color: #6366f1;"></i>
                                Advance Filters
                            </h3>
                            <p style="color: #475569; font-size: 0.85em; margin-bottom: 7px;">
                                Use the advanced filters below to quickly locate specific member records by status, type,
                                date, or keyword.
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
                        <div class="custom-dropdown">
                            <form method="GET" action="{{ route('guest.list') }}" class="custom-dropdown"
                                style="display:inline;">
                                <select name="status" onchange="this.form.submit()" class="dropdown-trigger"
                                    style="font-size: 14px; padding: 6px;">
                                    <option value="" {{ request('status') === null ? 'selected' : '' }}>All Entries</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </form>
                        </div>
                        <div class="custom-dropdown">
                            <form method="GET" action="{{ route('guest.list') }}" class="custom-dropdown"
                                style="display:inline;">
                                <select name="is_guest" onchange="this.form.submit()" class="dropdown-trigger"
                                    style="font-size: 14px; padding: 6px;">
                                    <option value="" {{ request('is_guest', '') === '' ? 'selected' : '' }}>All Users</option>
                                    <option value="0" {{ request('is_guest') == '0' ? 'selected' : '' }}>Members</option>
                                    <option value="1" {{ request('is_guest') == '1' ? 'selected' : '' }}>Guest Users</option>
                                </select>
                            </form>
                        </div>
                        <form method="GET" action="{{ route('guest.list') }}" class="filter-search-form"
                            style="display: flex; align-items: center; gap: 10px;">
                            <input type="date" name="start_date" value="{{ request('start_date') }}" class="custom-input"
                                style="padding: 6px 12px; border: 1px solid #e2e8f0; border-radius: 6px; background: #f8fafc; color: #495057; font-size: 14px;"
                                placeholder="Start Date">
                            <span style="color: #888;">to</span>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="custom-input"
                                style="padding: 6px 12px; border: 1px solid #e2e8f0; border-radius: 6px; background: #f8fafc; color: #495057; font-size: 14px;"
                                placeholder="End Date">
                            <div
                                style="display: flex; align-items: center;padding: 6px; width: 200px; border-radius: 6px; justify-content: space-between; border: 1px solid #e2e8f0; background: #f8fafc;">
                                <input type="text" name="search" value="{{ request('search') }}" class="custom-input"
                                    style=" border: none; border-radius: 6px; background:none; width: 90%;  color: #495057; font-size: 14px;"
                                    placeholder="Search ID, name, email, or phone">
                            </div>
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
                            <h3 style="margin: 0; color: #1e293b; font-size: 1.1em; font-weight: 600;">
                                <i class="fa-solid fa-table" style="margin-right: 8px; color: #6366f1;"></i>
                                Table Controls
                            </h3>
                            <p style="color: #475569; font-size: 0.85em; margin-bottom: 7px;">
                                Use the table controls below to show or hide columns, making it easy to customize your view
                                and focus on the member details that matter most to you.
                            </p>

                            <!-- Column Visibility Controls -->
                            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showId" checked onchange="toggleColumn('id')"
                                        style="margin: 0;">
                                    ID
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showMember" checked onchange="toggleColumn('member')"
                                        style="margin: 0;">
                                    Member
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
                                    <input type="checkbox" id="showStatus" checked onchange="toggleColumn('status')"
                                        style="margin: 0;">
                                    Status
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showActivity" checked onchange="toggleColumn('activity')"
                                        style="margin: 0;">
                                    Last Activity
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showActions" checked onchange="toggleColumn('actions')"
                                        style="margin: 0;">
                                    Actions
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

                <!-- Skeleton Loader -->
                <div id="skeleton-loader" class="skeleton-loader">
                    @for($i = 0; $i < 10; $i++)
                        <div class="skeleton-row">
                            <div class="skeleton-avatar"></div>
                            <div style="flex: 1;">
                                <div class="skeleton-line short"></div>
                                <div class="skeleton-line medium"></div>
                            </div>
                            <div style="width: 120px;">
                                <div class="skeleton-line short"></div>
                            </div>
                            <div style="width: 150px;">
                                <div class="skeleton-line short"></div>
                                <div class="skeleton-line short"></div>
                            </div>
                            <div class="skeleton-badge"></div>
                            <div style="width: 100px;">
                                <div class="skeleton-line short"></div>
                            </div>
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
                                <th>
                                    ID
                                </th>
                                <th>Member</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Last Activity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($members->isEmpty())
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 30px; color: #888;">
                                        <i class="fa-light fa-circle-exclamation"
                                            style="font-size: 2em; color: #ff9800; margin-bottom: 8px;"></i>
                                        <div style="margin-top: 8px;">No members found.</div>
                                    </td>
                                </tr>
                            @else
                                @foreach($members as $member)
                                    <tr>
                                        @if($member->type === 'member')
                                            <td>
                                                @if($member->is_guest == 1)
                                                    <i class="fa fa-user-clock guest-icon" title="Guest User"
                                                        style="color: #6366f1; margin-right: 6px; font-size: 1.1em; vertical-align: middle;"></i>
                                                @endif
                                                {{ $member->unique_id }}
                                            </td>
                                            <td>
                                                <a href="{{ route('member.transactions.detail', ['name' => str_replace(' ', '-', $member->name), 'unique_id' => $member->unique_id]) }}"
                                                    style="text-decoration: none; color: inherit;">
                                                    <div class="employee-info">
                                                        <img src="{{ $member->profile_image ? asset($member->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($member->name) }}"
                                                            alt="{{ $member->name }}">
                                                        <div>
                                                            <h4>{{ $member->name }}</h4>
                                                            <span>
                                                                {{ $member->email }}
                                                                @if(Str::startsWith($member->email, 'member_'))
                                                                    <span
                                                                        style="color: #fff; background: #e53935; border-radius: 6px; font-size: 0.85em; padding: 2px 7px; margin-left: 6px; vertical-align: middle; position: relative; cursor: pointer;"
                                                                        onmouseover="this.querySelector('.custom-tooltip').style.display='block';"
                                                                        onmouseout="this.querySelector('.custom-tooltip').style.display='none';">
                                                                        <i class="fa fa-exclamation-circle"
                                                                            style="margin-right: 2px;"></i>Fake
                                                                        <span class="custom-tooltip"
                                                                            style="display: none; position: absolute; left: 50%; transform: translateX(-50%); bottom: 120%; background: #222; color: #fff; padding: 7px 14px; border-radius: 6px; font-size: 0.93em; white-space: nowrap; z-index: 1000; box-shadow: 0 2px 8px rgba(0,0,0,0.13);">
                                                                            This email address is system-generated to ensure the user
                                                                            receives a notification when their account is promoted from
                                                                            guest to member.
                                                                            <span
                                                                                style="position: absolute; top: 100%; left: 50%; transform: translateX(-50%); width: 0; height: 0; border-left: 7px solid transparent; border-right: 7px solid transparent; border-top: 7px solid #222;"></span>
                                                                        </span>
                                                                    </span>
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </td>
                                        @endif
                                        <td>{{ $member->phone ?? '-' }}</td>
                                        <td>
                                            @if($member->street || $member->area || $member->town || $member->postal_code)
                                                <div style="font-size: 0.9em; color: #666;">
                                                    @if($member->street)
                                                        <div>{{ $member->street }}</div>
                                                    @endif
                                                    @if($member->area)
                                                        <div>{{ $member->area }}</div>
                                                    @endif
                                                    @if($member->town)
                                                        <div>{{ $member->town }}</div>
                                                    @endif
                                                    @if($member->postal_code)
                                                        <div><strong>{{ $member->postal_code }}</strong></div>
                                                    @endif
                                                </div>
                                            @else
                                                <span style="color: #999; font-style: italic;">No address</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="status-badge"
                                                style="background: {{ $member->status ? '#4CAF50' : '#F44336' }}; color: #fff; padding: 4px 12px; border-radius: 12px; font-size: 0.95em;">
                                                {{ $member->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="activity-info">
                                                <i class="fa-light fa-clock"></i>
                                                <span>{{ $member->updated_at->diffForHumans() }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <div class="guest-action-buttons">
                                                    <button class="guest-action-btn" data-guest-id="{{ $member->id }}"
                                                        data-guest-name="{{ $member->name }}"
                                                        data-guest-unique-id="{{ $member->unique_id }}"
                                                        data-guest-original-unique-id="{{ $member->original_unique_id ?? ($member->originaluniqueid ?? '') }}"
                                                        onclick="showGuestUserModalFromButton(this)">
                                                        <i class="fa-solid fa-cog"></i> Manage
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div id="pagination-container">
                        @include('layouts.custom_pagination', ['paginator' => $members])
                    </div>
                </div>
            </div>
        </div>
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
    </main>
@endsection
@section('script')

    <script>
        // Alphabet Filter Logic
        document.addEventListener('DOMContentLoaded', function () {
            // Highlight the active alphabet button based on current query param
            function setActiveAlphabetBtn() {
                const urlParams = new URLSearchParams(window.location.search);
                const currentAlpha = urlParams.get('alphabet') || '';
                document.querySelectorAll('#alphabetGrid .alphabet-btn').forEach(btn => {
                    if ((btn.dataset.letter || '') === currentAlpha) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });
            }

            // On click, update the URL and reload with the selected alphabet filter
            document.querySelectorAll('#alphabetGrid .alphabet-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const letter = this.dataset.letter || '';
                    const url = new URL(window.location.href);
                    if (letter) {
                        url.searchParams.set('alphabet', letter);
                    } else {
                        url.searchParams.delete('alphabet');
                    }
                    // Remove pagination when changing alphabet
                    url.searchParams.delete('page');
                    window.location.href = url.toString();
                });
            });

            // Reset alphabet filter
            document.getElementById('alphabetResetBtn').addEventListener('click', function () {
                const url = new URL(window.location.href);
                url.searchParams.delete('alphabet');
                url.searchParams.delete('page');
                window.location.href = url.toString();
            });

            setActiveAlphabetBtn();
        });
    </script>
    <script>
        // Skeleton Loader Logic
        document.addEventListener('DOMContentLoaded', function () {
            // Show skeleton loader and hide table content initially
            document.getElementById('skeleton-loader').classList.add('show');
            document.getElementById('table-content').style.display = 'none';

            // After 3 seconds, hide skeleton and show table
            setTimeout(function () {
                document.getElementById('skeleton-loader').classList.remove('show');
                document.getElementById('table-content').style.display = '';
            }, 3000);
        });

        // Reset Filters Logic
        function resetFilters() {
            // Redirect to clean URL without any query parameters
            window.location.href = "{{ route('guest.list') }}";
        }

        // Column visibility control functions
        function toggleColumn(columnType) {
            const table = document.querySelector('.data-table');
            const headerRow = table.querySelector('thead tr');
            const dataRows = table.querySelectorAll('tbody tr');

            let columnIndex = -1;

            // Determine column index based on type
            switch (columnType) {
                case 'id':
                    columnIndex = 0;
                    break;
                case 'member':
                    columnIndex = 1;
                    break;
                case 'phone':
                    columnIndex = 2;
                    break;
                case 'address':
                    columnIndex = 3;
                    break;
                case 'status':
                    columnIndex = 4;
                    break;
                case 'activity':
                    columnIndex = 5;
                    break;
                case 'actions':
                    columnIndex = 6;
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
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });

            const table = document.querySelector('.data-table');
            const allCells = table.querySelectorAll('th, td');
            allCells.forEach(cell => {
                cell.style.display = '';
            });
        }

        function hideAllColumns() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });

            const table = document.querySelector('.data-table');
            const allCells = table.querySelectorAll('th, td');
            allCells.forEach(cell => {
                cell.style.display = 'none';
            });
        }

        // Initialize column visibility based on checkboxes
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                const columnType = checkbox.id.replace('show', '').toLowerCase();
                if (!checkbox.checked) {
                    toggleColumn(columnType);
                }
            });
        });
    </script>
    <script>
        // --- Guest User Management Logic (from import.js) ---
        let currentGuestUser = null;
        let confirmActionType = null;

        function showCustomSnackbar(message, type = 'info') {
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
            switch(type) {
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
            setTimeout(() => {
                snackbar.style.transform = 'translateX(0)';
            }, 100);
            setTimeout(() => {
                snackbar.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(snackbar);
                }, 300);
            }, 3000);
        }

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
                        <span class="detail-value">${originalUniqueId && originalUniqueId !== 'null' && originalUniqueId !== '' ? originalUniqueId : '-'}</span>
                    </div>
                </div>
            `;
            document.getElementById('guestUserModal').style.display = 'block';
        }

        function showGuestUserModalFromButton(button) {
            const guestId = button.getAttribute('data-guest-id');
            const guestName = button.getAttribute('data-guest-name');
            const guestUniqueId = button.getAttribute('data-guest-unique-id');
            const guestOriginalUniqueId = button.getAttribute('data-guest-original-unique-id');
            showGuestUserModal(guestId, guestName, guestUniqueId, guestOriginalUniqueId);
        }
        function closeGuestUserModal() {
            document.getElementById('guestUserModal').style.display = 'none';
            document.getElementById('assignToMemberSection').style.display = 'none';
            const assignSection = document.getElementById('assignToMemberSection');
            if (assignSection) {
                assignSection.innerHTML = `
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
                        Select Existing Member:
                    </label>
                    <select id="existingMemberSelect" class="guest-select-input">
                        <option value="">Choose a member...</option>
                    </select>
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
        function showConfirmModal(type) {
            confirmActionType = type;
            let text = '';
            if (type === 'remain_guest') {
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
        function closeConfirmModal() {
            document.getElementById('customConfirmModal').style.display = 'none';
            confirmActionType = null;
        }
        // Remain as Guest
        function remainAsGuest() {
            if (!currentGuestUser) {
                showCustomSnackbar('No guest user selected', 'error');
                return;
            }
            showCustomSnackbar('Guest user will remain as guest', 'info');
            closeGuestUserModal();
            closeConfirmModal();
            // Hide the manage buttons for this guest user
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
            currentGuestUser = null;
        }
        // Promote Guest
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
                    removeGuestUserFromUI(currentGuestUser.id);
                    currentGuestUser = null;
                } else {
                    showCustomSnackbar(data.message || 'Failed to promote guest user', 'error');
                }
            })
            .catch(error => {
                console.error('Error promoting guest user:', error);
                showCustomSnackbar('Error promoting guest user', 'error');
            });
        }
        // Assign Guest
        function showAssignToMemberSection() {
            const section = document.getElementById('assignToMemberSection');
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
            fetch('/members/list')
                .then(response => response.json())
                .then(data => {
                    const selectElement = document.getElementById('existingMemberSelect');
                    selectElement.innerHTML = '<option value="">Choose a member...</option>';
                    data.members.forEach(member => {
                        selectElement.innerHTML += `<option value="${member.id}">${member.name} (${member.unique_id})</option>`;
                    });
                    section.style.display = 'block';
                    selectElement.addEventListener('change', function() {
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
            confirmActionType = 'assign_guest';
            document.getElementById('confirmModalText').textContent = 'Are you sure you want to assign this guest user to the selected member?';
            document.getElementById('customConfirmModal').style.display = 'block';
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
                    removeGuestUserFromUI(currentGuestUser.id);
                    currentGuestUser = null;
                } else {
                    showCustomSnackbar(data.message || 'Failed to assign guest user', 'error');
                }
            })
            .catch(error => {
                console.error('Error assigning guest user:', error);
                showCustomSnackbar('Error assigning guest user', 'error');
            });
        }
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
        }
        // Modal event listeners
        document.addEventListener('DOMContentLoaded', function () {
            const guestModal = document.getElementById('guestUserModal');
            if (guestModal) {
                guestModal.addEventListener('click', function (e) {
                    if (e.target === this) {
                        closeGuestUserModal();
                    }
                });
            }
            const okBtn = document.getElementById('confirmModalOkBtn');
            if (okBtn) {
                okBtn.onclick = function () {
                    if (confirmActionType === 'remain_guest') {
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
        });
    </script>
@endsection
