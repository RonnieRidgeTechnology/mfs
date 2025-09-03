@extends('layouts.admin')
<style>
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

    .data-table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 8px rgba(99, 102, 241, 0.06);
    }

    .data-table th,
    .data-table td {
        padding: 0.85em 1.1em;
        text-align: left;
    }

    .data-table th {
        background: #f1f5f9;
        color: #334155;
        font-weight: 700;
        font-size: 1.01em;
    }

    .data-table tr:nth-child(even) {
        background: #f9fafb;
    }

    .data-table tr:hover {
        background: #e0e7ef;
    }

    @media (max-width: 600px) {
        .user-profile-card {
            padding: 1.2rem 0.7rem 1rem 0.7rem;
        }

        .data-table th,
        .data-table td {
            padding: 0.6em 0.5em;
            font-size: 0.97em;
        }
    }
</style>
@section('content')
    <main class="main-content">
        @include('layouts.header')
        <div class="content">
            <!-- User Profile Section -->
            <div class="user-profile-card"
                style="height: fit-content ; margin: 0 0px 2.5rem 0px; background: #fff; border-radius: 18px;box-shadow: 0 2px 16px rgba(99,102,241,0.08); padding: 2rem 2.2rem 1.5rem 2.2rem; display: flex; gap: 1.5rem;">
                <div>
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=120"
                        alt="{{ $user->name }}"
                        style="width: 90px; height: 90px; border-radius: 50%; margin-bottom: 1.1rem; box-shadow: 0 1px 8px rgba(99,102,241,0.10);">
                </div>

                <div>
                    <h2 style="font-size: 1.45rem; font-weight: 700; color: #1e293b; margin-bottom: 0.3rem;">
                        {{ $user->name }}
                    </h2>
                    <div style="color: #64748b; font-size: 1.08rem; margin-bottom: 0.2rem;">
                        <i class="fa-light fa-envelope" style="margin-right: 7px;"></i> {{ $user->email }}
                    </div>
                    <div style="color: #64748b; font-size: 1.08rem; margin-bottom: 0.2rem;">
                        <i class="fa-light fa-id-card" style="margin-right: 7px;"></i> <span
                            style="font-weight: 500;">ID:</span> {{ $user->unique_id }}
                    </div>
                    <div style="color: #64748b; font-size: 1.08rem; margin-bottom: 0.2rem;">
                        <i class="fa-light fa-user-shield" style="margin-right: 7px;"></i>
                        <span style="font-weight: 500;">Role:</span> {{ ucfirst($user->type ?? $user->role ?? 'N/A') }}
                    </div>
                    <div style="color: #64748b; font-size: 1.08rem;">
                        <i class="fa-light fa-phone" style="margin-right: 7px;"></i> {{ $user->phone }}
                    </div>

                </div>
                <div style="margin-top: 1.5rem; width: 100%; display: flex; flex-direction: column; align-items: center;">
                    @php
                        $totalActivities = $activityLogs->total() ?? 0;
                        $isActive = $totalActivities > 0;
                        $lastActivity = $activityLogs->first();
                        $lastActivityTime = $lastActivity ? \Carbon\Carbon::parse($lastActivity->created_at)->diffForHumans() : null;
                        $recentActions = $activityLogs->take(3);
                    @endphp
                     <div style="display: flex; flex-direction: column; align-items: center; gap: 1.2em;">
                        <!-- Activity Overview Card -->
                        <div style="display: flex; align-items: center; gap: 2em;">
                              <div style="position: relative; width: 100px; height: 100px;">
                                @php
                                    // Never allow the progress to reach 100% (max 99%)
                                    $maxProgress = 99;
                                    $progress = min($totalActivities, 100);
                                    $displayProgress = min($progress, $maxProgress);
                                    $circumference = 2 * pi() * 42;
                                    $dashoffset = $circumference - ($displayProgress / 100) * $circumference;
                                @endphp
                                <svg width="100" height="100">
                                    <circle cx="50" cy="50" r="42" stroke="#e0e7ef" stroke-width="12" fill="none" />
                                    <circle cx="50" cy="50" r="42" stroke="#6366f1" stroke-width="12" fill="none"
                                        stroke-linecap="round"
                                        stroke-dasharray="{{ $circumference }}"
                                        stroke-dashoffset="{{ $dashoffset }}"
                                        style="transition: stroke-dashoffset 0.7s cubic-bezier(.4,2.3,.3,1);" />
                                </svg>
                                <div
                                    style="position: absolute; top: 0; left: 0; width: 100px; height: 100px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                    <span style="font-size: 2em; font-weight: 800; color: #3730a3; text-shadow: 0 2px 8px rgba(99,102,241,0.08);">{{ $totalActivities }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Logs Table Section -->
            <div class="table-section" style="margin: 0 !important;">
                <div class="table-header" style="display: flex; align-items: center; justify-content: space-between;">
                    <h2>Activity Logs</h2>
                </div>
                 <div class="sleek-filter-container" style="margin-bottom: 0;">
                    <div style="margin: 18px 0 8px 0;">
                        <div class="sleek-filter-header">
                            <i class="fa-solid fa-sliders"></i>
                            Activity Log Filters
                        </div>
                        <p style="color: #475569; font-size: 0.85em; margin-bottom: 7px;">
                            Use the filters below to quickly find activity logs by date range. This helps you review specific admin actions efficiently.
                        </p>
                    </div>
                    <form method="GET" action="" id="activityLogFilterForm" autocomplete="off"
                        style="display: flex; flex-wrap: wrap; gap: 1.1rem; align-items: end; padding: 0 1.5rem 1.5rem 1.5rem;">
                        <div class="sleek-filter-group" style="min-width: 160px;">
                            <label for="start_date" class="sleek-filter-label">Start Date</label>
                            <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                                class="sleek-filter-input" style="padding:4px 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; background: #fff;">
                        </div>
                        <div class="sleek-filter-group" style="min-width: 160px;">
                            <label for="end_date" class="sleek-filter-label">End Date</label>
                            <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                                class="sleek-filter-input" style="padding:4px 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; background: #fff;">
                        </div>
                        <button type="submit"
                            style="background: #6366f1; color: #fff; display:flex; gap:4px; align-items:center; border: none; border-radius: 8px; padding:7px 18px; font-size: 15px; font-weight: 600; cursor: pointer; box-shadow: 0 1px 4px rgba(99,102,241,0.10); transition: background 0.2s;">
                            <i class="fa-light fa-filter" style="margin-right: 0.5em;"></i>Filter
                        </button>
                        @if($startDate || $endDate || $year)
                            <a href="{{ route('admin.transactions.detail', ['name' => str_replace(' ', '-', $user->name), 'unique_id' => $user->unique_id]) }}"
                                style="display:flex; gap:4px; align-items:center; margin-left: 5px; color: #64748b; background: #f1f5f9; border: 1px solid #e5e7eb; border-radius: 8px; padding:7px 18px; font-size: 15px; text-decoration: none; font-weight: 500; transition: background 0.2s;">
                                <i class="fa-light fa-xmark" style="margin-right: 0.4em; "></i>Reset
                            </a>
                        @endif
                    </form>
                </div>
                <div class="table-controls-container"
                    style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 16px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); border: 1px solid #e2e8f0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                        <div>
                            <div style="margin: 18px 0 8px 0;">
                                <h3 style="margin: 0; color: #1e293b; font-size: 1.1em; font-weight: 600;">
                                    <i class="fa-solid fa-table" style="margin-right: 8px; color: #6366f1;"></i>
                                    Table Controls
                                </h3>
                                <p style="color: #475569; font-size: 0.85em; margin-bottom: 7px;">
                                    Use the table controls below to show or hide columns, making it easy to customize your view and focus on the activity log details that matter most to you.
                                </p>
                            </div>
                            <!-- Column Visibility Controls -->
                            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showIndex" checked onchange="toggleColumn('index')" style="margin: 0;">
                                    ID
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showActionType" checked onchange="toggleColumn('action-type')" style="margin: 0;">
                                    Action Type
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showActivity" checked onchange="toggleColumn('activity')" style="margin: 0;">
                                    Activity
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showIp" checked onchange="toggleColumn('ip')" style="margin: 0;">
                                    IP Address
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showUserAgent" checked onchange="toggleColumn('user-agent')" style="margin: 0;">
                                    User Agent
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showDate" checked onchange="toggleColumn('date')" style="margin: 0;">
                                    Date
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" id="showTime" checked onchange="toggleColumn('time')" style="margin: 0;">
                                    Time
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
                <script>
                    // Helper: get all tables with class 'data-table' (skeleton and actual)
                    function getAllTables() {
                        return Array.from(document.querySelectorAll('.data-table'));
                    }

                    // Helper: get checkbox by column key
                    function getCheckbox(col) {
                        // Map col to checkbox id
                        var map = {
                            'index': 'showIndex',
                            'action-type': 'showActionType',
                            'activity': 'showActivity',
                            'ip': 'showIp',
                            'user-agent': 'showUserAgent',
                            'date': 'showDate',
                            'time': 'showTime'
                        };
                        return document.getElementById(map[col]);
                    }

                    // Map column key to index
                    var columnKeyToIndex = {
                        'index': 0,
                        'action-type': 1,
                        'activity': 2,
                        'ip': 3,
                        'user-agent': 4,
                        'date': 5,
                        'time': 6
                    };

                    function toggleColumn(col) {
                        var colIndex = columnKeyToIndex[col];
                        if (colIndex === undefined) return;
                        var checked = getCheckbox(col).checked;
                        getAllTables().forEach(function(table) {
                            // header
                            var ths = table.querySelectorAll('thead tr th');
                            if (ths[colIndex]) ths[colIndex].style.display = checked ? '' : 'none';
                            // body
                            table.querySelectorAll('tbody tr').forEach(function(row) {
                                var tds = row.children;
                                if (tds[colIndex]) tds[colIndex].style.display = checked ? '' : 'none';
                            });
                        });
                    }

                    function showAllColumns() {
                        Object.keys(columnKeyToIndex).forEach(function(col) {
                            var cb = getCheckbox(col);
                            if (cb && !cb.checked) {
                                cb.checked = true;
                            }
                            toggleColumn(col);
                        });
                    }

                    function hideAllColumns() {
                        Object.keys(columnKeyToIndex).forEach(function(col) {
                            var cb = getCheckbox(col);
                            if (cb && cb.checked) {
                                cb.checked = false;
                            }
                            toggleColumn(col);
                        });
                    }

                    // On page load, ensure columns match checkboxes (for reloads)
                    document.addEventListener('DOMContentLoaded', function() {
                        // Simulate loading: hide skeleton and show table after data is ready
                        setTimeout(function() {
                            document.getElementById('skeleton-loader').style.display = 'none';
                            document.getElementById('actual-table').style.display = 'block';
                            // After table is visible, apply column visibility
                            Object.keys(columnKeyToIndex).forEach(function(col) {
                                toggleColumn(col);
                            });
                        }, 800); // Adjust delay as needed

                        // Also apply to skeleton loader immediately
                        Object.keys(columnKeyToIndex).forEach(function(col) {
                            toggleColumn(col);
                        });
                    });
                </script>
                 <!-- Skeleton Loader -->
                <div id="skeleton-loader" style="display: block;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Action Type</th>
                                <th>Activity</th>
                                <th>IP Address</th>
                                <th>User Agent</th>
                                <th>Date</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 5; $i++)
                                <tr>
                                    <td><div class="skeleton-cell" style="width: 40px; height: 18px; background: #e5e7eb; border-radius: 4px;"></div></td>
                                    <td><div class="skeleton-cell" style="width: 80px; height: 18px; background: #e5e7eb; border-radius: 4px;"></div></td>
                                    <td><div class="skeleton-cell" style="width: 120px; height: 18px; background: #e5e7eb; border-radius: 4px;"></div></td>
                                    <td><div class="skeleton-cell" style="width: 100px; height: 18px; background: #e5e7eb; border-radius: 4px;"></div></td>
                                    <td><div class="skeleton-cell" style="width: 140px; height: 18px; background: #e5e7eb; border-radius: 4px;"></div></td>
                                    <td><div class="skeleton-cell" style="width: 70px; height: 18px; background: #e5e7eb; border-radius: 4px;"></div></td>
                                    <td><div class="skeleton-cell" style="width: 60px; height: 18px; background: #e5e7eb; border-radius: 4px;"></div></td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>

                <!-- Actual Table (Hidden Initially) -->
                <div id="actual-table" style="display: none;">
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Action Type</th>
                                    <th>Activity</th>
                                    <th>IP Address</th>
                                    <th>User Agent</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activityLogs as $i => $log)
                                    <tr>
                                        <td>{{ $activityLogs->firstItem() + $i }}</td>
                                        <td>
                                            <span
                                                class="status-badge {{ isset($log->action_type) && strtolower($log->action_type) == 'login' ? 'active' : 'inactive' }}">
                                                {{ isset($log->action_type) ? ucfirst($log->action_type) : '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if(isset($log->activity))
                                                <span>{{ $log->activity }}</span>
                                            @else
                                                <span>-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($log->ip_address))
                                                <span>{{ $log->ip_address }}</span>
                                            @else
                                                <span>-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($log->user_agent))
                                                <span
                                                    title="{{ $log->user_agent }}">{{ \Illuminate\Support\Str::limit($log->user_agent, 30) }}</span>
                                            @else
                                                <span>-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span>{{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d') }}</span>
                                        </td>
                                        <td>
                                            <span>{{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" style="text-align: center; padding: 30px; color: #888;">
                                            <i class="fa-light fa-circle-exclamation"
                                                style="font-size: 2em; color: #ff9800; margin-bottom: 8px;"></i>
                                            <div style="margin-top: 8px;">No activity logs found.</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div id="pagination-container" style="margin-top: 1.2rem;">
                            @include('layouts.custom_pagination', ['paginator' => $activityLogs])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
