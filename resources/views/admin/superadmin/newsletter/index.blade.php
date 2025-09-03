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
</style>
@section('content')
    <main class="main-content">
        @include('layouts.header')
        <div class="content">
            <div class="table-section">
                <div 
                    style="display: flex ;align-items: center;justify-content: space-between;padding: 20px 22px 15px 22px;">
                     <h2>Newsletter Subscribers ({{ $totalSubscribersCount }})</h2>                
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
                                 Use the advanced filters below to quickly locate specific newsletter subscribers by status, date, or email.
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
                            <form method="GET" action="{{ route('admin.newsletter.index') }}" class="custom-dropdown"
                                style="display:inline;">
                                <select name="status" onchange="this.form.submit()" class="dropdown-trigger"
                                    style="font-size: 14px; padding: 6px;">
                                    <option value="" {{ request('status') === null ? 'selected' : '' }}>All Statuses</option>
                                    <option value="subscribed" {{ request('status') === 'subscribed' ? 'selected' : '' }}>Subscribed</option>
                                    <option value="unsubscribed" {{ request('status') === 'unsubscribed' ? 'selected' : '' }}>Unsubscribed</option>
                                </select>
                            </form>
                        </div>
                        <form method="GET" action="{{ route('admin.newsletter.index') }}" class="filter-search-form"
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
                                    placeholder="Search email">
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
                                    Use the table controls below to show or hide columns, making it easy to customize your view and focus on the subscriber details that matter most to you.
                                </p>
                            </div>
                            <!-- Column Visibility Controls -->
                            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showId" checked onchange="toggleColumn('id')" style="margin: 0;">
                                    <label for="showId" style="margin-left: 5px; cursor:pointer;">ID</label>
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showEmail" checked onchange="toggleColumn('email')" style="margin: 0;">
                                    <label for="showEmail" style="margin-left: 5px; cursor:pointer;">Email</label>
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showStatus" checked onchange="toggleColumn('status')" style="margin: 0;">
                                    <label for="showStatus" style="margin-left: 5px; cursor:pointer;">Status</label>
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showSubscribed" checked onchange="toggleColumn('subscribed')" style="margin: 0;">
                                    <label for="showSubscribed" style="margin-left: 5px; cursor:pointer;">Subscribed At</label>
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showActions" checked onchange="toggleColumn('actions')" style="margin: 0;">
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
                            <div style="flex: 3;">
                                <div class="skeleton-line medium"></div>
                                <div class="skeleton-line short"></div>
                            </div>
                            <div class="skeleton-badge"></div>
                            <div style="width: 120px;">
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
                                <th data-col="id">ID</th>
                                <th data-col="email">Email</th>
                                <th data-col="status">Status</th>
                                <th data-col="subscribed">Subscribed At</th>
                                <th data-col="actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($subscribers->isEmpty())
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 30px; color: #888;">
                                        <i class="fa-light fa-circle-exclamation"
                                            style="font-size: 2em; color: #ff9800; margin-bottom: 8px;"></i>
                                        <div style="margin-top: 8px;">No newsletter subscribers found.</div>
                                    </td>
                                </tr>
                            @else
                                @foreach($subscribers as $subscriber)
                                    <tr>
                                        <td data-col="id">{{ $loop->iteration }}</td>
                                        <td data-col="email" style="max-width: 300px; word-break: break-word;">
                                            {{ $subscriber->email }}
                                        </td>
                                         <td data-col="status">
                                            @if($subscriber->is_subscribed == 1)
                                                <span class="status-badge"
                                                    style="background: #4CAF50; color: #fff; padding: 4px 12px; border-radius: 12px; font-size: 0.95em;">
                                                    Subscribed
                                                </span>
                                            @else 
                                                <span class="status-badge"
                                                    style="background: #F44336; color: #fff; padding: 4px 12px; border-radius: 12px; font-size: 0.95em;">
                                                    Unsubscribed
                                                </span>
                                            @endif
                                        </td>
                                        <td data-col="subscribed">
                                            <div class="activity-info">
                                                <i class="fa-light fa-clock"></i>
                                                <span>{{ $subscriber->created_at->diffForHumans() }}</span>
                                            </div>
                                        </td>
                                        <td data-col="actions">
                                            <div class="action-buttons">                                            
                                                <form action="{{ route('admin.newsletter.delete', $subscriber->id) }}" method="POST"
                                                    style="display:inline;"
                                                    onsubmit="return confirm('Are you sure you want to delete this subscriber?');">
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
                        @include('layouts.custom_pagination', ['paginator' => $subscribers])
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
            document.getElementById('skeleton-loader').classList.add('show');
            document.getElementById('table-content').style.display = 'none';

            setTimeout(function () {
                document.getElementById('skeleton-loader').classList.remove('show');
                document.getElementById('table-content').style.display = '';
            }, 1200);
        });

        // Reset Filters Logic
        function resetFilters() {
            window.location.href = "{{ route('admin.newsletter.index') }}";
        }

        // Column visibility control functions
        // Map columnType to data-col attribute
        const columnMap = {
            id: 'id',
            email: 'email',
            status: 'status',
            subscribed: 'subscribed',
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
            const isHidden = headerCell.style.display === 'none';

            // Toggle header
            headerCell.style.display = isHidden ? '' : 'none';

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
    </script>
@endsection
