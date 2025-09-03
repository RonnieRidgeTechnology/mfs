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

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: none;
        border-radius: 6px;
        padding: 6px 14px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        background: #f1f5f9;
        color: #1e293b;
        margin-right: 6px;
    }

    .action-btn.edit {
        background: #4f8cff;
        color: #fff;
    }

    .action-btn.delete {
        background: #f44336;
        color: #fff;
    }

    .action-btn.edit:hover {
        background: #2357c5;
    }

    .action-btn.delete:hover {
        background: #c82333;
    }

    .add-new {
        background: linear-gradient(90deg, #4f8cff 0%, #2357c5 100%);
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 10px 22px;
        font-weight: 600;
        font-size: 1rem;
        box-shadow: 0 2px 8px rgba(79, 140, 255, 0.08);
        transition: background 0.2s, box-shadow 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    /* Additional styles for filter and table controls */
    .filter-container, .table-controls-container {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
    }
    .filter-title {
        color: #1e293b;
        font-size: 1.1em;
        font-weight: 600;
        margin: 0;
    }
    .badge {
        display: flex;
        align-items: center;
        gap: 5px;
        background: #f1f5f9;
        color: #6366f1;
        border-radius: 12px;
        padding: 6px 12px;
        font-size: 0.9em;
        font-weight: 500;
    }
    .quick-action-btn {
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 0.9em;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
</style>
@section('content')
    <main class="main-content">
        @include('layouts.header')
        <div class="content">

            <!-- Filter Container -->
            <div class="filter-container">
                <div class="filter-header" style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div style="margin: 18px 0 8px 0;">
                        <h3 class="filter-title">
                            <i class="fa-solid fa-filter" style="margin-right: 8px; color: #6366f1;"></i>
                            Advanced Filters
                        </h3>
                        <p style="color: #475569; font-size: 0.85em; margin-bottom: 7px;">
                            Use the advanced filters below to quickly locate specific Pillar records by status, date, or keyword.
                        </p>
                    </div>
                    <div class="filter-actions">
                        <button type="button" onclick="resetFilters()" class="reset-btn" style="background: none; border: none; color: #6366f1; font-weight: 600; cursor: pointer;">
                            <i class="fa-light fa-rotate-left"></i>
                            <span>Reset All</span>
                        </button>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                    <div class="custom-dropdown">
                        <form method="GET" action="{{ route('five_pillars.index') }}" class="custom-dropdown" style="display:inline;">
                            <select name="status" onchange="this.form.submit()" class="dropdown-trigger"
                                style="font-size: 14px; padding: 6px;">
                                <option value="" {{ request('status') === null ? 'selected' : '' }}>All Entries</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </form>
                    </div>
                    <form method="GET" action="{{ route('five_pillars.index') }}" class="filter-search-form"
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
                                placeholder="Search pillar name ">
                        </div>
                        <button type="submit" class="action-button primary"
                            style="padding: 6px 18px; border-radius: 6px; background: var(--primary, #4f8cff); color: #fff; border: none; font-size:14px; display: flex; align-items: center; gap: 6px;">
                            <i class="fa-light fa-magnifying-glass"></i>
                            <span>Search</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Table Controls Section -->
            <div class="table-controls-container">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                    <div>
                        <div style="margin: 18px 0 8px 0;">
                            <h3 style="margin: 0; color: #1e293b; font-size: 1.1em; font-weight: 600;">
                                <i class="fa-solid fa-table" style="margin-right: 8px; color: #6366f1;"></i>
                                Table Controls
                            </h3>
                            <p style="color: #475569; font-size: 0.85em; margin-bottom: 7px;">
                                Use the table controls below to show or hide columns, making it easy to customize your view and focus on the Pillar details that matter most to you.
                            </p>
                        </div>
                        <!-- Column Visibility Controls -->
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <span class="badge">
                                <input type="checkbox" id="showId" checked onchange="toggleColumn('id')" style="margin: 0;">
                                <label for="showId" style="margin-left: 5px; cursor:pointer;">ID</label>
                            </span>
                            <span class="badge">
                                <input type="checkbox" id="showName" checked onchange="toggleColumn('name')" style="margin: 0;">
                                <label for="showName" style="margin-left: 5px; cursor:pointer;">Name</label>
                            </span>
                            <span class="badge">
                                <input type="checkbox" id="showImage" checked onchange="toggleColumn('image')" style="margin: 0;">
                                <label for="showImage" style="margin-left: 5px; cursor:pointer;">Image</label>
                            </span>
                            <span class="badge">
                                <input type="checkbox" id="showStatus" checked onchange="toggleColumn('status')" style="margin: 0;">
                                <label for="showStatus" style="margin-left: 5px; cursor:pointer;">Status</label>
                            </span>
                            <span class="badge">
                                <input type="checkbox" id="showUpdated" checked onchange="toggleColumn('updated')" style="margin: 0;">
                                <label for="showUpdated" style="margin-left: 5px; cursor:pointer;">Updated At</label>
                            </span>
                            <span class="badge">
                                <input type="checkbox" id="showActions" checked onchange="toggleColumn('actions')" style="margin: 0;">
                                <label for="showActions" style="margin-left: 5px; cursor:pointer;">Actions</label>
                            </span>
                        </div>
                    </div>
                    <!-- Quick Actions -->
                    <div style="display: flex; gap: 10px;">
                        <button type="button" onclick="showAllColumns()" class="quick-action-btn"
                            style="background: linear-gradient(135deg, #6366f1 0%, #60a5fa 100%); color: white;">
                            <i class="fa-solid fa-eye" style="margin-right: 5px;"></i>
                            Show All
                        </button>
                        <button type="button" onclick="hideAllColumns()" class="quick-action-btn"
                            style="background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); color: #64748b;">
                            <i class="fa-solid fa-eye-slash" style="margin-right: 5px;"></i>
                            Hide All
                        </button>
                    </div>
                </div>
            </div>

            <div class="table-section">
                <div
                    style="display: flex; align-items: center; justify-content: space-between; padding: 20px 22px 15px 22px;">
                    <h2>Pillars Of Islam</h2>
                    <a href="{{ route('five_pillars.create') }}" class="add-new">
                        <i class="fa-light fa-plus"></i>
                        Add Pillar
                    </a>
                </div>

                <!-- Skeleton Loader -->
                <div id="skeleton-loader" class="skeleton-loader">
                    @for($i = 0; $i < 8; $i++)
                        <div class="skeleton-row">
                            <div style="width: 40px;">
                                <div class="skeleton-line short"></div>
                            </div>
                            <div style="flex: 2;">
                                <div class="skeleton-line medium"></div>
                            </div>
                            <div style="flex: 3;">
                                <div class="skeleton-line medium"></div>
                            </div>
                            <div style="flex: 2;">
                                <div class="skeleton-line medium"></div>
                            </div>
                            <div style="flex: 1;">
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
                    <table class="data-table" style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th class="col-id" style="width:40px;">#</th>
                                <th class="col-name">Name</th>
                                <th class="col-image">Image</th>
                                <th class="col-status">Status</th>
                                <th class="col-updated">Updated At</th>
                                <th class="col-actions" style="width:120px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($pillars->isEmpty())
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 30px; color: #888;">
                                        <i class="fa-light fa-circle-exclamation"
                                            style="font-size: 2em; color: #ff9800; margin-bottom: 8px;"></i>
                                        <div style="margin-top: 8px;">No Pillars found.</div>
                                    </td>
                                </tr>
                            @else
                                @foreach($pillars as $pillar)
                                    <tr>
                                        <td class="col-id">{{ $loop->iteration }}</td>
                                        <td class="col-name">
                                            <div class="employee-info">
                                                <div>
                                                    <h4>{{ $pillar->name }}</h4>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="col-image">
                                            @if($pillar->image)
                                                <img src="{{ asset($pillar->image) }}" alt="{{ $pillar->name }}"
                                                    style="width:48px; height:48px; object-fit:cover; border-radius:8px;">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($pillar->name) }}"
                                                    alt="{{ $pillar->name }}"
                                                    style="width:48px; height:48px; object-fit:cover; border-radius:8px;">
                                            @endif
                                        </td>
                                        <td class="col-status">
                                            <span class="status-badge"
                                                style="background: {{ $pillar->is_active ? '#4CAF50' : '#F44336' }}; color: #fff; padding: 4px 12px; border-radius: 12px; font-size: 0.95em;">
                                                {{ $pillar->is_active ? 'active' : 'inactive' }}
                                            </span>
                                        </td>
                                        <td class="col-updated">
                                            {{ $pillar->updated_at ? $pillar->updated_at->format('Y-m-d H:i') : '-' }}
                                        </td>
                                        <td class="col-actions">
                                            <a href="{{ route('five_pillars.edit', $pillar->id) }}" class="action-btn edit">
                                                <i class="fa-light fa-pen"></i> Edit
                                            </a>
                                            <form action="{{ route('five_pillars.destroy', $pillar->id) }}" method="POST"
                                                style="display:inline;"
                                                onsubmit="return confirm('Are you sure you want to delete this Pillar?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn delete">
                                                    <i class="fa-light fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div id="pagination-container" style="margin-top: 18px;">
                        @include('layouts.custom_pagination', ['paginator' => $pillars])
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
            }, 900);
        });

        // Table column show/hide logic
        function toggleColumn(col) {
            var show = document.getElementById('show' + capitalize(col));
            if (!show) return;
            show = show.checked;
            var ths = document.querySelectorAll('.col-' + col);
            ths.forEach(function (el) {
                el.style.display = show ? '' : 'none';
            });
        }
        function hideAllColumns() {
            ['id','name','image','status','updated','actions'].forEach(function(col) {
                var checkbox = document.getElementById('show' + capitalize(col));
                if (checkbox) {
                    checkbox.checked = false;
                    toggleColumn(col);
                }
            });
        }
        function showAllColumns() {
            ['id','name','image','status','updated','actions'].forEach(function(col) {
                var checkbox = document.getElementById('show' + capitalize(col));
                if (checkbox) {
                    checkbox.checked = true;
                    toggleColumn(col);
                }
            });
        }
        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
        // On page load, ensure columns are set according to checkboxes
        document.addEventListener('DOMContentLoaded', function () {
            ['id','name','image','status','updated','actions'].forEach(function(col) {
                toggleColumn(col);
            });
        });

        // Reset filters function
        function resetFilters() {
            window.location.href = "{{ route('five_pillars.index') }}";
        }
    </script>
@endsection
