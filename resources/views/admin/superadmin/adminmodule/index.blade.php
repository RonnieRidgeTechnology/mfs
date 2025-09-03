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
</style>
@section('content')
    <main class="main-content">
        @include('layouts.header')
        <div class="content">
            <div class="table-section">
                <div
                    style="display: flex ;align-items: center;justify-content: space-between;padding: 20px 22px 15px 22px;}">
                    <h2>Admins ({{ $admins->total() }})</h2>
                    <a href="{{ route('admin.create') }}"
                        style="display: flex; align-items: center; gap: 8px; background: linear-gradient(90deg, #4f8cff 0%, #2357c5 100%); border: none; border-radius: 6px; padding: 10px 22px; font-weight: 600; font-size: 1rem; color: #fff; box-shadow: 0 2px 8px rgba(79,140,255,0.08); transition: background 0.2s, box-shadow 0.2s;"
                        class="action-button add-new">
                        <i class="fa-light fa-plus"></i>
                        Add More
                    </a>
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
                                 Use the advanced filters below to quickly locate specific admin records by status, date, or keyword.
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
                            <form method="GET" action="{{ route('admin.list') }}" class="custom-dropdown"
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
                        <form method="GET" action="{{ route('admin.list') }}" class="filter-search-form"
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
                            <div style="margin: 18px 0 8px 0;">
                                <h3 style="margin: 0; color: #1e293b; font-size: 1.1em; font-weight: 600;">
                                    <i class="fa-solid fa-table" style="margin-right: 8px; color: #6366f1;"></i>
                                    Table Controls
                                </h3>
                                <p style="color: #475569; font-size: 0.85em; margin-bottom: 7px;">
                                    Use the table controls below to show or hide columns, making it easy to customize your view and focus on the admin details that matter most to you.
                                </p>
                            </div>
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
                                    <input type="checkbox" id="showAdmin" checked onchange="toggleColumn('admin')"
                                        style="margin: 0;">
                                    Admin
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showPhone" checked onchange="toggleColumn('phone')"
                                        style="margin: 0;">
                                    Phone
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
                            <div style="width: 80px;">
                                <div class="skeleton-line short"></div>
                            </div>
                            <div class="skeleton-avatar"></div>
                            <div style="flex: 1;">
                                <div class="skeleton-line short"></div>
                                <div class="skeleton-line medium"></div>
                            </div>
                            <div style="width: 120px;">
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
                                <th>Admin</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Last Activity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($admins->isEmpty())
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 30px; color: #888;">
                                        <i class="fa-light fa-circle-exclamation"
                                            style="font-size: 2em; color: #ff9800; margin-bottom: 8px;"></i>
                                        <div style="margin-top: 8px;">No admins found.</div>
                                    </td>
                                </tr>
                            @else
                                @foreach($admins as $admin)
                                    <tr>
                                        <td>{{ $admin->unique_id}}</td>
                                        <td>
                                            <a href="{{ route('admin.transactions.detail', ['name' => str_replace(' ', '-', $admin->name), 'unique_id' => $admin->unique_id]) }}"
                                                style="text-decoration: none; color: inherit;">
                                                <div class="employee-info">
                                                    <img src="{{ $admin->profile_image ? asset($admin->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($admin->name) }}"
                                                        alt="{{ $admin->name }}">
                                                    <div>
                                                        <h4>{{ $admin->name }}</h4>
                                                        <span>{{ $admin->email }}</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </td>
                                        <td>{{ $admin->phone ?? '-' }}</td>
                                        <td>
                                            <span class="status-badge"
                                                style="background: {{ $admin->status ? '#4CAF50' : '#F44336' }}; color: #fff; padding: 4px 12px; border-radius: 12px; font-size: 0.95em;">
                                                {{ $admin->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="activity-info">
                                                <i class="fa-light fa-clock"></i>
                                                <span>{{ $admin->updated_at->diffForHumans() }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.edit', $admin->unique_id) }}" class="action-btn edit">
                                                    <span class="btn-content">
                                                        <i class="fa-light fa-pen"></i>
                                                        <p class="btn-text">Edit</p>
                                                    </span>
                                                </a>
                                                <form action="{{ route('admin.destroy', $admin->id) }}" method="POST"
                                                    style="display:inline;"
                                                    onsubmit="return confirm('Are you sure you want to delete {{ $admin->name }}?');">
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
                        @include('layouts.custom_pagination', ['paginator' => $admins])
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('script')
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
            }, 3000); // 3 seconds
        });

        // Reset Filters Logic
        function resetFilters() {
            // Redirect to clean URL without any query parameters
            window.location.href = "{{ route('admin.list') }}";
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
                case 'admin':
                    columnIndex = 1;
                    break;
                case 'phone':
                    columnIndex = 2;
                    break;
                case 'status':
                    columnIndex = 3;
                    break;
                case 'activity':
                    columnIndex = 4;
                    break;
                case 'actions':
                    columnIndex = 5;
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
@endsection
