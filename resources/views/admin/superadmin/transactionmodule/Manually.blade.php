@extends('layouts.admin')
@section('content')
    <style>
        /* Modern floating label styles (copied/adapted from create.blade.php) */
        .modern-form {
            padding: 30px;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.07);
            margin: 0 auto;
        }

        .form-row {
            display: flex;
            gap: 24px;
            margin-bottom: 18px;
        }

        .form-group {
            flex: 1;
            position: relative;
            margin-bottom: 18px;
        }

        .form-floating,
        .floating-label {
            position: relative;
        }

        .form-floating input,
        .form-floating select,
        .floating-label input,
        .floating-label select {
            width: 100%;
            padding: 18px 12px 8px 12px;
            font-size: 1rem;
            border: 1.5px solid #d1d5db;
            border-radius: 8px;
            background: transparent;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-floating input:focus,
        .form-floating select:focus,
        .floating-label input:focus,
        .floating-label select:focus {
            border-color: #2563eb;
        }

        .form-floating label,
        .floating-label label {
            position: absolute;
            top: 16px;
            left: 14px;
            color: #6b7280;
            font-size: 1rem;
            pointer-events: none;
            background: #fff;
            padding: 0 4px;
            transition: 0.2s;
        }

        .form-floating input:not(:placeholder-shown)+label,
        .form-floating input:focus+label,
        .form-floating select:focus+label,
        .form-floating select:not([value=""])+label,
        .floating-label input:not(:placeholder-shown)+label,
        .floating-label input:focus+label,
        .floating-label select:focus+label,
        .floating-label select:not([value=""])+label {
            top: -10px;
            left: 10px;
            font-size: 0.85rem;
            color: #2563eb;
            background: #fff;
        }

        .form-floating input.is-invalid,
        .form-floating select.is-invalid,
        .floating-label input.is-invalid,
        .floating-label select.is-invalid {
            border-color: #dc2626;
        }

        .form-floating input.is-valid,
        .form-floating select.is-valid,
        .floating-label input.is-valid,
        .floating-label select.is-valid {
            border-color: #16a34a;
        }

        .invalid-feedback {
            color: #dc2626;
            font-size: 0.92em;
            margin-top: 2px;
            display: block !important;
        }

        .valid-feedback {
            color: #16a34a;
            font-size: 0.92em;
            margin-top: 2px;
            display: block !important;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
        }

        .btn-cancel {
            background: #f3f4f6;
            color: #374151;
            border: none;
            padding: 10px 22px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-cancel:hover {
            background: #e5e7eb;
        }

        .action-button.primary {
            background: #2563eb;
            color: #fff;
            border: none;
            padding: 10px 28px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .action-button.primary:hover {
            background: #1d4ed8;
        }

        /* Custom dropdown styles (harmonized with modern-form) */
        .member-dropdown {
            position: relative;
            width: 100%;
            border: 1.5px solid #d1d5db;
            border-radius: 8px;
            background: #fff;
            margin-bottom: 0;
        }

        .member-dropdown-trigger {
            padding: 18px 12px 8px 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            min-height: 48px;
            cursor: pointer;
            font-size: 1rem;
            color: #6b7280;
        }

        .member-dropdown-trigger.selected {
            color: #222;
        }

        .member-dropdown-menu {
            display: none;
            position: absolute;
            background-color: #fff;
            border: 1.5px solid #d1d5db;
            border-radius: 0 0 8px 8px;
            max-height: 220px;
            overflow-y: auto;
            width: 100%;
            z-index: 100;
            left: 0;
            top: 100%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
        }

        .member-dropdown-search input {
            width: 100%;
            padding: 12px;
            border: none;
            border-bottom: 1.5px solid #d1d5db;
            outline: none;
            background: #fff;
            font-size: 1rem;
        }

        .member-dropdown-options-container {
            max-height: 160px;
            overflow-y: auto;
        }

        .member-dropdown-option {
            padding: 12px;
            cursor: pointer;
            font-size: 1rem;
            color: #374151;
            transition: background 0.15s;
        }

        .member-dropdown-option:hover,
        .member-dropdown-option.active {
            background-color: #f3f4f6;
        }

        .no-results {
            padding: 12px;
            color: #888;
            font-size: 0.98em;
        }

        @media (max-width: 700px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
    <main class="main-content">
        @include('layouts.header')

        <div class="content">
            <div class="table-section">
                <div class="table-header">
                    <h2>Add New Transaction</h2>
                </div>

                <form method="POST" action="{{ route('Manually.transactions.store') }}" class="modern-form" novalidate>
                    @csrf

                    {{-- Member Searchable Dropdown --}}
                    <div class="form-group form-floating" style="z-index: 10;">
                        <input type="hidden" name="user_id" id="user_id" required>
                        <div class="member-dropdown" id="custom-select-member" tabindex="0">
                            <div class="member-dropdown-trigger" id="member-trigger">
                                <span class="select-placeholder" id="member-selected-label"
                                    style="display: block; color: #2563eb; font-size: 1rem; font-weight: 500;">
                                    Member
                                </span>
                                <span class="member-selected-value" id="member-selected-value"
                                    style="display: none; color: #374151; font-size: 1.05rem; font-weight: 400; margin-top: 2px;"></span>
                                <i class="fa-light fa-chevron-down"></i>
                            </div>
                            <div class="member-dropdown-menu" id="member-menu">
                                <div class="member-dropdown-search">
                                    <input type="text" id="member-search" placeholder="Search member..." autocomplete="off">
                                </div>
                                <div class="member-dropdown-options-container" id="member-options">
                                    @foreach($members as $member)
                                        <div class="member-dropdown-option" data-id="{{ $member->id }}"
                                            data-name="{{ $member->name }}" data-uid="{{ $member->unique_id }}">
                                            {{ $member->name }} - {{ $member->unique_id }}
                                        </div>
                                    @endforeach
                                </div>
                                <div class="no-results" style="display:none;">No members found</div>
                            </div>
                        </div>
                        <!-- No floating label here, label is handled above for correct style and visibility -->
                    </div>

                    {{-- Date --}}
                     <div class="form-group form-floating">
                        <input type="date" name="date" id="date" class="form-control" required placeholder=" " value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                        <label for="date">Date</label>
                    </div>

                    {{-- Transaction Fields --}}
                    <div class="form-row">
                        <div class="form-group form-floating">
                            <input type="text" name="amount" id="amount" class="form-control" required placeholder=" ">
                            <label for="amount">Amount</label>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="form-group form-floating">
                        <select name="status" id="status" class="form-control" required>
                            <option value="cash">Cash</option>
                        </select>
                        <label for="status">Status</label>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="window.history.back()">Cancel</button>
                        <button type="submit" class="action-button primary">Add Transaction</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const customSelect = document.getElementById("custom-select-member");
            const trigger = document.getElementById("member-trigger");
            const menu = document.getElementById("member-menu");
            const searchInput = document.getElementById("member-search");
            const optionsContainer = document.getElementById("member-options");
            const hiddenInput = document.getElementById("user_id");
            const displaySpan = trigger.querySelector("span");
            const noResults = menu.querySelector(".no-results");

            let menuOpen = false;

            function closeMenu() {
                menu.style.display = "none";
                trigger.classList.remove('active');
                menuOpen = false;
            }

            function openMenu() {
                menu.style.display = "block";
                trigger.classList.add('active');
                searchInput.focus();
                menuOpen = true;
            }

            // Toggle menu on trigger click
            trigger.addEventListener("click", function (e) {
                e.stopPropagation();  // stop bubbling to document
                if (menuOpen) {
                    closeMenu();
                } else {
                    // Reset search
                    searchInput.value = "";
                    noResults.style.display = "none";
                    const options = optionsContainer.querySelectorAll(".member-dropdown-option");
                    options.forEach(opt => opt.style.display = "block");

                    openMenu();
                }
            });

            // Prevent menu from closing when clicking inside
            menu.addEventListener("click", function (e) {
                e.stopPropagation();
            });

            // ✅ Close menu only when clicking outside trigger + menu
            document.addEventListener("click", function (e) {
                if (menuOpen && !menu.contains(e.target) && !trigger.contains(e.target)) {
                    closeMenu();
                }
            });

            // Search filter
            searchInput.addEventListener("input", function () {
                const term = this.value.toLowerCase();
                let anyVisible = false;
                const options = optionsContainer.querySelectorAll(".member-dropdown-option");
                options.forEach(opt => {
                    const text = opt.textContent.toLowerCase();
                    if (text.includes(term)) {
                        opt.style.display = "block";
                        anyVisible = true;
                    } else {
                        opt.style.display = "none";
                    }
                });
                noResults.style.display = anyVisible ? "none" : "block";
            });

            // Select option
            optionsContainer.addEventListener("click", function (e) {
                if (e.target.classList.contains("member-dropdown-option")) {
                    const selectedText = e.target.textContent;
                    const selectedId = e.target.dataset.id;
                    displaySpan.textContent = selectedText;
                    displaySpan.classList.add('selected');
                    hiddenInput.value = selectedId;
                    closeMenu();
                }
            });

            // Keyboard navigation
            let currentIndex = -1;
            searchInput.addEventListener("keydown", function (e) {
                const options = Array.from(optionsContainer.querySelectorAll(".member-dropdown-option"))
                    .filter(opt => opt.style.display !== "none");
                if (options.length === 0) return;

                if (e.key === "ArrowDown") {
                    e.preventDefault();
                    currentIndex = (currentIndex + 1) % options.length;
                    options.forEach(opt => opt.classList.remove('active'));
                    options[currentIndex].classList.add('active');
                    options[currentIndex].scrollIntoView({ block: "nearest" });
                } else if (e.key === "ArrowUp") {
                    e.preventDefault();
                    currentIndex = (currentIndex - 1 + options.length) % options.length;
                    options.forEach(opt => opt.classList.remove('active'));
                    options[currentIndex].classList.add('active');
                    options[currentIndex].scrollIntoView({ block: "nearest" });
                } else if (e.key === "Enter") {
                    e.preventDefault();
                    if (currentIndex >= 0 && options[currentIndex]) {
                        options[currentIndex].click();
                    }
                } else {
                    currentIndex = -1;
                    options.forEach(opt => opt.classList.remove('active'));
                }
            });

            // Close on Escape
            searchInput.addEventListener("keydown", function (e) {
                if (e.key === "Escape") {
                    closeMenu();
                }
            });
        });
    </script>


@endsection
