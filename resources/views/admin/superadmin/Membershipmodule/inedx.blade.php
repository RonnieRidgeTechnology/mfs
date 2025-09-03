@extends('layouts.admin')
@section('content')
    <main class="main-content">
        @include('layouts.header')
        <div class="content">
            <div class="table-section">

                <!-- Table Header -->
                <div class="table-header">
                    <h2>Membership Fee Settings</h2>
                    <div class="table-actions">
                        <button type="button" class="btn btn-primary" id="add-membership-setting"
                            style="display: flex; align-items: center; gap: 8px; background: linear-gradient(90deg, #4f8cff 0%, #2357c5 100%); border: none; border-radius: 6px; padding: 10px 22px; font-weight: 600; font-size: 1rem; color: #fff; box-shadow: 0 2px 8px rgba(79,140,255,0.08); transition: background 0.2s, box-shadow 0.2s;">
                            <i class="fa-light fa-plus" style="font-size: 1.2em; margin-right: 4px;"></i>
                            <span style="letter-spacing: 0.02em;">Add Membership Fee</span>
                        </button>
                    </div>
                </div>
                <!-- Filter Container -->

                <div class="filter-container" style="display: flex; gap: 16px; align-items: center; margin: 0 0 24px 0;">
                    <div style="margin: 18px 0 8px 0;">
                        <h4 style="font-weight: 700; color: #6366f1; margin-bottom: 4px;">
                            <i class="fa-solid fa-sliders" style="margin-right: 6px; color: #6366f1;"></i>
                            Advanced Filter
                        </h4>
                        <p style="color: #475569; font-size: 0.85em; margin-bottom: 7px;">
                            Use the advanced filters below to quickly locate specific membership fee records by year or fee
                            type.
                        </p>
                    </div>
                    <div>
                        <label for="filter-year" style="font-weight: 600; margin-right: 6px; color: #1e293b;">
                            <i class="fa-solid fa-calendar-alt" style="color: #6366f1; margin-right: 4px;"></i>
                            Year:
                        </label>
                        <select id="filter-year" class="form-control"
                            style="min-width: 120px; padding: 5px 10px; border-radius: 4px; border: 1px solid #d0d0d0; font-size: 1rem;">
                            <option value="">All Years</option>
                            @for ($year = 2000; $year <= date('Y'); $year++)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="filter-type" style="font-weight: 600; margin-right: 6px; color: #1e293b;">
                            <i class="fa-solid fa-tags" style="color: #6366f1; margin-right: 4px;"></i>
                            Fee Type:
                        </label>
                        <select id="filter-type" class="form-control"
                            style="min-width: 150px; padding: 5px 10px; border-radius: 4px; border: 1px solid #d0d0d0; font-size: 1rem;">
                            <option value="">All Types</option>
                            <option value="new_adult">New Adult</option>
                            <option value="annual_fee">Annual Fee</option>
                            <option value="child_turned_18">Child Turned 18</option>
                        </select>
                    </div>
                    <button type="button" id="reset-filters" class="btn btn-secondary"
                         style="margin-left: -35px; background: #e2e8f0; color: #1e293b; border: none; border-radius: 6px; padding: 8px 18px; font-weight: 500; font-size: 0.98rem; box-shadow: 0 1px 4px rgba(30,41,59,0.04); transition: background 0.2s;gap: 5px;align-items: center;display: flex;margin-top: 20px;">
                        <i class="fa-solid fa-rotate-left" style="margin-right: 5px;"></i> Reset
                    </button>
                </div>

                <!-- Table Controls Container -->
                <div class="table-controls-container"
                     style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 16px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); border: 1px solid #e2e8f0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                        <div>
                            <h3 style="margin: 0; color: #1e293b; font-size: 1.1em; font-weight: 600;">
                                <i class="fa-solid fa-table" style="margin-right: 8px; color: #6366f1;"></i>
                                Table Controls
                            </h3>
                            <p style="color: #475569; font-size: 0.85em; margin-bottom: 7px;">
                                Use the table controls below to show or hide columns, making it easy to customize
                                your view and focus on the transaction details that matter most to you.
                            </p>
                            <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px;">
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" class="toggle-col" data-col="member-id" checked>
                                    ID
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" class="toggle-col" data-col="name" checked>
                                    Member Type
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" class="toggle-col" data-col="amount" checked>
                                    Amount
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" class="toggle-col" data-col="date" checked>
                                    Year
                                </span>
                                <span class="badge"
                                    style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                                    <input type="checkbox" class="toggle-col" data-col="actions" checked>
                                    Actions
                                </span>
                            </div>
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <button id="show-all-columns" class="quick-action-btn"
                                style="background: linear-gradient(135deg, #6366f1 0%, #60a5fa 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.9em; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                                <i class="fa-solid fa-eye" style="margin-right: 5px;"></i> Show All
                            </button>
                            <button id="hide-all-columns" class="quick-action-btn"
                                style="background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); color: #64748b; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.9em; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                                <i class="fa-solid fa-eye-slash" style="margin-right: 5px;"></i> Hide All
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Skeleton Preloader -->
                <div id="skeleton-loader" style="display: block;">
                    <div
                        style="background: #f8fafc; border-radius: 12px; padding: 16px; margin-bottom: 12px; height: 50px; animation: pulse 1.5s infinite;">
                        <div style="height: 16px; background: #e2e8f0; border-radius: 4px; width: 80%;"></div>
                    </div>
                    @for ($i = 0; $i < 5; $i++)
                        <div
                            style="background: #f8fafc; border-radius: 12px; padding: 16px; margin-bottom: 12px; height: 60px; animation: pulse 1.5s infinite; display: flex; gap: 16px; align-items: center;">
                            <div style="height: 24px; width: 40px; background: #e2e8f0; border-radius: 6px;"></div>
                            <div style="height: 20px; width: 120px; background: #e2e8f0; border-radius: 4px;"></div>
                            <div style="height: 20px; width: 100px; background: #e2e8f0; border-radius: 4px;"></div>
                            <div style="height: 20px; width: 80px; background: #e2e8f0; border-radius: 4px;"></div>
                            <div style="height: 32px; width: 80px; background: #e2e8f0; border-radius: 8px;"></div>
                        </div>
                    @endfor
                </div>

                <!-- Real Table (Initially Hidden) -->
                <div id="real-table-container" style="display: none;">
                    <div class="table-container">
                        <table class="data-table" id="membership-fee-table">
                            <thead>
                                <tr>
                                    <th class="member-id">#</th>
                                    <th class="name">Member Type</th>
                                    <th class="amount">Amount</th>
                                    <th class="date">Year</th>
                                    <th class="actions">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $memberTypes = [
                                        'new_adult' => 'New Adult',
                                        'annual_fee' => 'Annual Fee',
                                        'child_turned_18' => 'Child Turned 18'
                                    ];
                                    $settings = isset($settings) ? $settings : \App\Models\MembershipFeeSetting::all();
                                    $currentYear = date('Y');
                                @endphp
                                @if($settings->count() > 0)
                                    @foreach($settings as $setting)
                                        <tr data-id="{{ $setting->id }}" data-year="{{ $setting->year }}"
                                            data-type="{{ $setting->member_type }}">
                                            <td class="member-id">{{ $loop->iteration }}</td>
                                            <td class="name">{{ $memberTypes[$setting->member_type] ?? $setting->member_type }}</td>
                                            <td class="amount">£{{ $setting->amount }}</td>
                                            <td class="date">{{ $setting->year }}</td>
                                            <td class="actions">
                                                <div class="action-buttons">
                                                    <button type="button" class="action-btn edit" data-id="{{ $setting->id }}"
                                                        data-tooltip="Edit Record" @if($setting->year != $currentYear) disabled
                                                        style="opacity:0.5;cursor:not-allowed;" @endif>
                                                        <span class="btn-content">
                                                            <i class="fa-light fa-pen"></i>
                                                            <p class="btn-text">Edit</p>
                                                        </span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                <!-- No Data in Database -->
                                <tr class="no-data-row"
                                    style="{{ $settings->count() == 0 ? 'display: table-row;' : 'display: none;' }}">
                                    <td colspan="6" style="text-align: center; padding: 30px; color: #888;">
                                        <i class="fa-light fa-circle-exclamation"
                                            style="font-size: 2em; color: #ff9800; margin-bottom: 8px;"></i>
                                        <div>No membership fee settings found.</div>
                                    </td>
                                </tr>

                                <!-- No Data After Filter -->
                                <tr class="no-filter-data-row" style="display: none;">
                                    <td colspan="6" style="text-align: center; padding: 30px; color: #6366f1;">
                                        <i class="fa-light fa-magnifying-glass"
                                            style="font-size: 2em; margin-bottom: 8px;"></i>
                                        <div style="font-size: 1.1em; font-weight: 500;">No fee management found</div>
                                        <div style="font-size: 0.95em; color: #64748b;">Try adjusting your filter criteria
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div id="pagination-container">
                            @include('layouts.custom_pagination', ['paginator' => $settings])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal -->
    <div class="modal-overlay" style="display: none;">
        <div class="modal" style="margin: 60px auto 0 auto;">
            <div class="modal-header">
                <div class="header-content">
                    <h3 class="modal-title">Add Membership Fee Setting</h3>
                    <button type="button" class="close-modal">
                        <i class="fa-light fa-xmark"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <form class="add-form" id="membership-form" method="POST" action="{{ route('membership.setting.store') }}">
                    @csrf
                    <input type="hidden" name="_method" value="POST" id="form-method">
                    <input type="hidden" name="id" id="setting-id">

                    <!-- Member Type -->
                    <div class="form-group">
                        <label for="member_type">Member Type</label>
                        <div class="custom-select" id="custom-select-member-type">
                            <div class="select-trigger">
                                <span>Select Member Type</span>
                                <i class="fa-light fa-chevron-down"></i>
                            </div>
                            <div class="select-menu">
                                <div class="select-option" data-value="">Select Member Type</div>
                                <div class="select-option" data-value="new_adult">New Adult</div>
                                <div class="select-option" data-value="annual_fee">Annual Fee</div>
                                <div class="select-option" data-value="child_turned_18">Child Turned 18</div>
                            </div>
                        </div>
                        <input type="hidden" name="member_type" id="member_type">
                    </div>

                    <!-- Amount -->
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" min="0" step="0.01" name="amount" id="amount" placeholder="Enter amount"
                            required>
                    </div>

                    <!-- Year (Searchable Dropdown) -->
                    <div class="form-group">
                        <label for="year">Year</label>
                        <div class="custom-select" id="custom-select-year">
                            <div class="select-trigger">
                                <span>Select Year</span>
                                <i class="fa-light fa-chevron-down"></i>
                            </div>
                            <div class="select-menu">
                                <div class="select-search">
                                    <input type="text" id="year-search" placeholder="Search year..." autocomplete="off">
                                </div>
                                <div class="select-options-container">
                                    @for ($year = 2000; $year <= date('Y'); $year++)
                                        <div class="select-option" data-value="{{ $year }}">{{ $year }}</div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="year" id="year" required>
                    </div>

                    <!-- Actions -->
                    <div class="form-actions">
                        <button type="button" class="btn-cancel">Cancel</button>
                        <button type="submit" class="btn-submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
