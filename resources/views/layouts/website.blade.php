<!DOCTYPE html>
<html lang="en">
@php
    use App\Models\WebSetting;
    use App\Models\HomeUpdate;

    $websetting = WebSetting::first();
    $homeupdate = HomeUpdate::first();
@endphp

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('meta_title', 'MFS - Muslim Funeral Society')</title>
    <meta name="description" content="@yield('meta_description', 'Muslim Funeral Society - Providing compassionate funeral services and support to the community.')" />
    <meta name="keywords" content="@yield('meta_keywords', 'Muslim Funeral Society, funeral, services, support, community, Islamic funeral, burial, charity')" />
    <link rel="stylesheet" href="{{ asset('assets/web/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/web/responsive.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/styles/snackbar.css') }}">
    @if (!empty($websetting->favicon_icon))
        <link rel="icon" type="image/x-icon" href="{{ asset($websetting->favicon_icon) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/web/favicon.ico') }}">
    @endif
    <style>
        /* Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        /* Help Button - FAB */
        .hb_fab_container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
        }

        .hb_chat_fab {
            background-color: #4f46e5;
            color: white;
            border: none;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            padding: 16px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
                0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hb_chat_fab:hover {
            background-color: #4338ca;
            transform: scale(1.1);
        }

        .hb_chat_fab:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.3);
        }

        /* Transaction Lookup Modal */
        .hb_chat_modal {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 10000;
            align-items: center;
            justify-content: center;
        }

        .hb_chat_modal.active {
            display: flex;
        }

        .hb_chat_box {
            width: 100%;
            max-width: 520px;
            max-height: 700px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            display: flex;
            flex-direction: column;
        }

        /* Modal Header */
        .hb_chat_header {
            background-color: #4f46e5;
            color: white;
            padding: 24px;
            border-radius: 16px 16px 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .hb_chat_header_info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .hb_chat_avatar {
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hb_chat_title {
            font-size: 1.125rem;
            font-weight: 600;
        }

        .hb_chat_subtitle {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .hb_close_button {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 4px;
        }

        .hb_close_button:hover {
            color: rgba(255, 255, 255, 0.8);
        }

        /* Modal Content */
        .hb_chat_messages {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
            max-height: 500px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Step Container */
        .hb_step_container {
            display: none;
        }

        .hb_step_container.active {
            display: block;
        }

        /* Input Fields */
        .hb_input_group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .hb_input_label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 4px;
        }

        .hb_chat_input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            outline: none;
            font-size: 1rem;
            transition: all 0.2s;
            background: #f9fafb;
        }

        .hb_chat_input:focus {
            border-color: #4f46e5;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .hb_chat_input.error {
            border-color: #ef4444;
            background: #fef2f2;
        }

        /* Buttons */
        .hb_action_button {
            background: #4f46e5;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            width: 100%;
        }

        .hb_action_button:hover {
            background: #4338ca;
            transform: translateY(-1px);
        }

        .hb_action_button:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }

        .hb_back_button {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            margin-right: 12px;
            white-space: nowrap;
        }

        .hb_back_button:hover {
            background: #e5e7eb;
        }

        /* Progress Display */
        .hb_progress_container {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e2e8f0;
        }

        .hb_progress_header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .hb_progress_title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
        }

        .hb_progress_status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .hb_progress_status.verified {
            background: #dcfce7;
            color: #16a34a;
        }

        .hb_progress_status.pending {
            background: #eef2ff;
            color: #6366f1;
        }

        .hb_progress_bar {
            background: #f1f5f9;
            border-radius: 8px;
            height: 12px;
            overflow: hidden;
            margin-bottom: 12px;
        }

        .hb_progress_fill {
            height: 100%;
            transition: width 0.4s ease;
            border-radius: 8px;
        }

        .hb_progress_fill.verified {
            background: linear-gradient(90deg, #22c55e 0%, #16a34a 100%);
        }

        .hb_progress_fill.pending {
            background: linear-gradient(90deg, #6366f1 0%, #4f46e5 100%);
        }

        .hb_progress_details {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            color: #64748b;
        }

        .hb_progress_amount {
            font-weight: 600;
            color: #1e293b;
        }

        /* Error Messages */
        .hb_error_message {
            background: #fef2f2;
            color: #dc2626;
            padding: 12px;
            border-radius: 8px;
            font-size: 0.9rem;
            border: 1px solid #fecaca;
            display: none;
        }

        .hb_error_message.show {
            display: block;
        }

        /* Loading State */
        .hb_loading {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            color: #6b7280;
        }

        .hb_spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #e5e7eb;
            border-top: 2px solid #4f46e5;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 640px) {
            .hb_chat_box {
                margin: 20px;
                max-height: 90vh;
            }
            
            .hb_chat_header {
                padding: 20px;
            }
            
            .hb_chat_messages {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    @yield('content')

    <!-- footer-start -->
    <!-- Floating Action Button -->
    <div class="hb_fab_container">
        <button id="hb_chat_fab" class="hb_chat_fab" aria-label="Check Transaction Progress">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
        </button>
    </div>

    <!-- Transaction Progress Lookup Modal -->
    <div id="hb_chat_modal" class="hb_chat_modal">
        <div class="hb_chat_box">
            <!-- Header -->
            <div class="hb_chat_header">
                <div class="hb_chat_header_info">
                    <div class="hb_chat_avatar">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="hb_chat_title">Transaction Progress</h3>
                        <p class="hb_chat_subtitle">Check your membership fee status</p>
                    </div>
                </div>
                <button id="hb_close_chat" class="hb_close_button" aria-label="Close modal">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div id="hb_chat_messages" class="hb_chat_messages">
                <!-- Step 1: Enter Unique ID -->
                <div id="hb_step_1" class="hb_step_container active">
                    <div class="hb_input_group">
                        <label class="hb_input_label">Enter your Unique ID</label>
                        <input type="text" id="hb_unique_id" class="hb_chat_input" 
                               placeholder="e.g., M1, A2, F3" maxlength="10">
                        <div id="hb_error_unique_id" class="hb_error_message">
                            Please enter a valid Unique ID
                        </div>
                    </div>
                    <button id="hb_next_step_1" style="margin-top: 20px;" class="hb_action_button">
                        Continue
                    </button>
                </div>

                <!-- Step 2: Select Year -->
                <div id="hb_step_2" class="hb_step_container">
                    <div class="hb_input_group">
                        <label class="hb_input_label">Select Year</label>
                        <select id="hb_year" class="hb_chat_input">
                            <option value="">Choose a year</option>
                            @php
                                $currentYear = now()->year;
                                for($y = $currentYear; $y >= $currentYear - 10; $y--) {
                                    echo "<option value='{$y}'>{$y}</option>";
                                }
                            @endphp
                        </select>
                        <div id="hb_error_year" class="hb_error_message">
                            Please select a year
                        </div>
                    </div>
                    <div style="display: flex; gap: 12px;align-items: center;">
                        <button id="hb_back_step_2" style="margin-top: 20px;" class="hb_back_button">Back</button>
                        <button id="hb_check_progress" style="margin-top: 20px;" class="hb_action_button">
                            Check Progress
                        </button>
                    </div>
                </div>

                <!-- Step 3: Progress Results -->
                <div id="hb_step_3" class="hb_step_container">
                    <div id="hb_progress_results">
                        <!-- Progress will be loaded here -->
                    </div>
                    <div style="display: flex; gap: 12px; margin-top: 20px;align-items: center;">
                        <button id="hb_back_step_3" style="margin-top: 20px;" class="hb_back_button">Check Another Year</button>
                        <button id="hb_new_lookup" style="margin-top: 20px;" class="hb_action_button">New Lookup</button>
                    </div>
            </div>

                <!-- Loading State -->
                <div id="hb_loading" class="hb_loading" style="display: none;">
                    <div class="hb_spinner"></div>
                    <span>Checking your progress...</span>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-main-div">
        <div class="footer-main">
            <div class="footer-1">
                <div class="footer-logo">
                    <a href="{{ route('index') }}">Muslim Funeral Society</a>
                </div>
                <div class="footer1-description">
                    <p>
                        {{ $homeupdate->footer_desc }}
                    </p>
                </div>
                <div class="footer1-icon-main">
                    <ul>
                        <li>
                            <a href="{{ $websetting->facebook_link }}">
                                <span class="footer-icon">
                                    <!-- Facebook SVG -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="currentColor"
                                        class="icon icon-tabler icons-tabler-filled icon-tabler-brand-facebook">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M18 2a1 1 0 0 1 .993 .883l.007 .117v4a1 1 0 0 1 -.883 .993l-.117 .007h-3v1h3a1 1 0 0 1 .991 1.131l-.02 .112l-1 4a1 1 0 0 1 -.858 .75l-.113 .007h-2v6a1 1 0 0 1 -.883 .993l-.117 .007h-4a1 1 0 0 1 -.993 -.883l-.007 -.117v-6h-2a1 1 0 0 1 -.993 -.883l-.007 -.117v-4a1 1 0 0 1 .883 -.993l.117 -.007h2v-1a6 6 0 0 1 5.775 -5.996l.225 -.004h3z" />
                                    </svg>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ $websetting->youtube_link }}">
                                <span class="footer-icon">
                                    <!-- Facebook SVG -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="currentColor"
                                        class="icon icon-tabler icons-tabler-filled icon-tabler-brand-youtube">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M21.543 7.143a2.997 2.997 0 0 0-2.112-2.12C17.077 4.5 12 4.5 12 4.5s-5.077 0-7.431.523a2.997 2.997 0 0 0-2.112 2.12A31.07 31.07 0 0 0 2 12a31.07 31.07 0 0 0 .457 4.857 2.997 2.997 0 0 0 2.112 2.12C6.923 19.5 12 19.5 12 19.5s5.077 0 7.431-.523a2.997 2.997 0 0 0 2.112-2.12A31.07 31.07 0 0 0 22 12a31.07 31.07 0 0 0-.457-4.857zM10 15.5v-7l6 3.5-6 3.5z" />
                                    </svg>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ $websetting->insta_link }}">
                                <span class="footer-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="currentColor"
                                        class="icon icon-tabler icons-tabler-filled icon-tabler-brand-instagram">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M7 2c-2.757 0-5 2.243-5 5v10c0 2.757 2.243 5 5 5h10c2.757 0 5-2.243 5-5V7c0-2.757-2.243-5-5-5H7zm10 2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h10zm-5 3a5 5 0 1 0 0 10a5 5 0 0 0 0-10zm0 2a3 3 0 1 1 0 6a3 3 0 0 1 0-6zm5.5-.5a1 1 0 1 0 0 2a1 1 0 0 0 0-2z" />
                                    </svg>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ $websetting->linkdin_link }}">
                                <span class="footer-icon">
                                    <!-- Facebook SVG -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="currentColor"
                                        class="icon icon-tabler icons-tabler-filled icon-tabler-brand-linkedin">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M4 3a2 2 0 0 0 -2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2 -2v-14a2 2 0 0 0 -2 -2h-16zm4.5 6a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0 -3zm-2 3.5h3v7h-3v-7zm5 0h2.5v1h.03c.35-.66 1.2-1.35 2.47-1.35c2.64 0 3.13 1.74 3.13 4v3.35h-3v-2.97c0-.71-.01-1.62-1-1.62c-1 0-1.15.78-1.15 1.57v3.02h-3v-7z" />
                                    </svg>
                                </span>
                            </a>
                        </li>

                        <!-- Repeat the same for other icons -->
                    </ul>
                </div>
            </div>
            <div class="footer2-main">
                <h1>Services</h1>
                <ul>
                    <li><a href="{{ route('council.public') }}">What to do during a Bereavement?</a></li>
                    <li><a href="{{ route('paymentsucces.public') }}">Membership Payment Status</a></li>
                    <li><a href="{{ route('newmember.public') }}">New Membership</a></li>
                    <li><a href="{{ route('rules.public') }}">Rules and Regulations</a></li>
                    <li><a href="{{ route('payment.public') }}">Payment Inf</a></li>
                </ul>
            </div>
            <div class="footer2-main">
                <h1>Quick Links</h1>
                <ul>
                    <li><a href="{{ route('index') }}">Home </a></li>
                    <li><a href="{{ route('faq.public') }}">FAQ </a></li>
                    <li><a href="{{ route('hmbc.public') }}">HMBC</a></li>
                    <li><a href="{{ route('about.public') }}">About us</a></li>
                    <li><a href="{{ route('contact.public') }}">contact</a></li>
                </ul>
            </div>
            <div class="footer4-main">
                <h1>Newsletter</h1>
                <form class="footer4-input" method="POST" action="{{ route('newsletter.subscribe') }}">
                    @csrf
                    <input type="text" name="email" placeholder="Your Email.." value="{{ old('email') }}" />
                    <button type="submit" style="all: unset; cursor: pointer; ">
                        <a href="#"
                            onclick="event.preventDefault(); this.closest('form').submit();">subcribe</a>
                    </button>
                </form>
            </div>
        </div>
        <div class="footer-end">
            <h1>
                {{ $websetting->copy_right }}
            </h1>
        </div>
    </div>
    @if (session('success') || $errors->any())
        <script>
            function showCustomSnackbar(message, type = 'success') {
                const icons = {
                    success: `<svg class="snackbar-icon" width="20" height="20" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="10" fill="#22c55e"/><path d="M6 10.5l2.5 2.5 5-5" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
                    error: `<svg class="snackbar-icon" width="20" height="20" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="10" fill="#ef4444"/><path d="M7 7l6 6M13 7l-6 6" stroke="#fff" stroke-width="2" stroke-linecap="round"/></svg>`,
                    info: `<svg class="snackbar-icon" width="20" height="20" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="10" fill="#2563eb"/><circle cx="10" cy="7" r="1.2" fill="#fff"/><rect x="9" y="9" width="2" height="6" rx="1" fill="#fff"/></svg>`,
                    warning: `<svg class="snackbar-icon" width="20" height="20" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="10" fill="#facc15"/><path d="M10 5v6" stroke="#fff" stroke-width="2" stroke-linecap="round"/><circle cx="10" cy="14" r="1" fill="#fff"/></svg>`,
                };

                let container = document.getElementById('custom-snackbar-container');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'custom-snackbar-container';
                    container.className = 'custom-snackbar-container';
                    document.body.appendChild(container);
                }

                const snackbar = document.createElement('div');
                snackbar.className = `custom-snackbar ${type}`;
                snackbar.innerHTML = `
                                    ${icons[type] || icons.info}
                                    <div class="snackbar-message">${message}</div>
                                    <button class="close-btn" title="Close">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg">
                                            <line x1="4" y1="4" x2="12" y2="12" stroke="#888" stroke-width="2" stroke-linecap="round"/>
                                            <line x1="12" y1="4" x2="4" y2="12" stroke="#888" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                    </button>
                                `;

                snackbar.querySelector('.close-btn').addEventListener('click', () => {
                    snackbar.classList.remove('show');
                    setTimeout(() => snackbar.remove(), 500); // Wait for transition
                });

                container.appendChild(snackbar);

                setTimeout(() => {
                    snackbar.classList.add('show');
                }, 10);
            }

            @if (session('success'))
                showCustomSnackbar(@json(session('success')), 'success');
            @else
                showCustomSnackbar(@json($errors->first()), 'error');
            @endif
        </script>
    @endif
    @yield('script')
     <!-- JavaScript -->
    <script>
        // DOM Elements
        const hb_chatFab = document.getElementById('hb_chat_fab');
        const hb_chatModal = document.getElementById('hb_chat_modal');
        const hb_closeChat = document.getElementById('hb_close_chat');
        const hb_uniqueId = document.getElementById('hb_unique_id');
        const hb_year = document.getElementById('hb_year');
        const hb_nextStep1 = document.getElementById('hb_next_step_1');
        const hb_backStep2 = document.getElementById('hb_back_step_2');
        const hb_backStep3 = document.getElementById('hb_back_step_3');
        const hb_checkProgress = document.getElementById('hb_check_progress');
        const hb_newLookup = document.getElementById('hb_new_lookup');
        const hb_progressResults = document.getElementById('hb_progress_results');
        const hb_loading = document.getElementById('hb_loading');

        // Step management
        let currentStep = 1;
        let currentUniqueId = '';
        let currentYear = '';

        // Open modal
        hb_chatFab.addEventListener('click', () => {
            hb_chatModal.classList.add('active');
            setTimeout(() => hb_uniqueId.focus(), 300);
        });

        // Close modal
        hb_closeChat.addEventListener('click', () => {
            hb_chatModal.classList.remove('active');
            resetToStep1();
        });

        // Close on backdrop click
        hb_chatModal.addEventListener('click', (e) => {
            if (e.target === hb_chatModal) {
                hb_chatModal.classList.remove('active');
                resetToStep1();
            }
        });

        // Escape key to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && hb_chatModal.classList.contains('active')) {
                hb_chatModal.classList.remove('active');
                resetToStep1();
            }
        });

        // Step 1: Validate and proceed
        hb_nextStep1.addEventListener('click', () => {
            const uniqueId = hb_uniqueId.value.trim();
            
            if (!uniqueId || uniqueId.length < 2) {
                showError('hb_error_unique_id', 'Please enter a valid Unique ID');
                return;
            }
            
            currentUniqueId = uniqueId;
            showStep(2);
        });

        // Step 2: Back button
        hb_backStep2.addEventListener('click', () => {
            showStep(1);
        });

        // Step 2: Check progress
        hb_checkProgress.addEventListener('click', () => {
            const year = hb_year.value;
            
            if (!year) {
                showError('hb_error_year', 'Please select a year');
                return;
            }
            
            currentYear = year;
            checkTransactionProgress();
        });

        // Step 3: Back button
        hb_backStep3.addEventListener('click', () => {
            showStep(2);
        });

        // Step 3: New lookup
        hb_newLookup.addEventListener('click', () => {
            resetToStep1();
        });

        // Enter key handlers
        hb_uniqueId.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                hb_nextStep1.click();
            }
        });

        hb_year.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                hb_checkProgress.click();
            }
        });

        // Helper functions
        function showStep(step) {
            // Hide all steps
            document.querySelectorAll('.hb_step_container').forEach(el => {
                el.classList.remove('active');
            });
            
            // Show target step
            document.getElementById(`hb_step_${step}`).classList.add('active');
            currentStep = step;
            
            // Clear errors
            clearErrors();
            
            // Focus appropriate input
            if (step === 1) {
                hb_uniqueId.focus();
            } else if (step === 2) {
                hb_year.focus();
            }
        }

        function resetToStep1() {
            hb_uniqueId.value = '';
            hb_year.value = '';
            currentUniqueId = '';
            currentYear = '';
            clearErrors();
            showStep(1);
        }

        function showError(elementId, message) {
            const errorElement = document.getElementById(elementId);
            errorElement.textContent = message;
            errorElement.classList.add('show');
        }

        function clearErrors() {
            document.querySelectorAll('.hb_error_message').forEach(el => {
                el.classList.remove('show');
            });
        }

        function showLoading(show = true) {
            hb_loading.style.display = show ? 'flex' : 'none';
        }

        // Check transaction progress
        async function checkTransactionProgress() {
            showLoading(true);
            
            try {
                
                // Make API call to get transaction progress
                const response = await fetch(`/api/transaction-progress/${currentUniqueId}/${currentYear}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });


                if (!response.ok) {
                    throw new Error('Failed to fetch progress');
                }

                const data = await response.json();
                
                if (data.success && data.progress) {
                    displayProgress(data.progress);
                    showStep(3);
                } else {
                    throw new Error(data.message || 'Failed to get progress');
                }
                
            } catch (error) {
                
                // Show error in results area
                hb_progressResults.innerHTML = `
                    <div style="text-align: center; padding: 20px; color: #dc2626;">
                        <i class="fa fa-exclamation-triangle" style="font-size: 2em; margin-bottom: 10px;"></i>
                        <div>Unable to fetch progress for ${currentUniqueId} in ${currentYear}</div>
                        <div style="font-size: 0.9em; margin-top: 5px; color: #6b7280;">
                            Error: ${error.message}
                        </div>
                        <div style="font-size: 0.9em; margin-top: 5px; color: #6b7280;">
                            Please check your Unique ID and try again
                        </div>
                    </div>
                `;
                showStep(3);
            } finally {
                showLoading(false);
            }
        }

        // Display progress results
        function displayProgress(progress) {
            
            try {
                const { annualFee, paidAmount, progressPercent, isVerified, memberName } = progress;
                
                const statusClass = isVerified ? 'verified' : 'pending';
                const statusText = isVerified ? 'Verified (100% Complete)' : `${progressPercent.toFixed(1)}% Complete`;
                
                
                hb_progressResults.innerHTML = `
                    <div class="hb_progress_container">
                        <div style="text-align: center; margin-bottom: 20px;">
                            <h3 style="color: #1e293b; margin-bottom: 8px;">${memberName}</h3>
                            <p style="color: #64748b; font-size: 0.9rem;">Unique ID: ${currentUniqueId} | Year: ${currentYear}</p>
                        </div>
                        
                        <div class="hb_progress_header">
                            <span class="hb_progress_title">Annual Fee Progress</span>
                            <span class="hb_progress_status ${statusClass}">${statusText}</span>
                        </div>
                        
                        <div class="hb_progress_bar">
                            <div class="hb_progress_fill ${statusClass}" style="width: ${progressPercent}%;"></div>
                        </div>
                        
                        <div class="hb_progress_details">
                            <span>Annual Fee: <span class="hb_progress_amount">£${(parseFloat(annualFee) || 0).toFixed(2)}</span></span>
                            <span>Paid: <span class="hb_progress_amount">£${(parseFloat(paidAmount) || 0).toFixed(2)}</span></span>
                        </div>
                    </div>
                `;
                
            } catch (error) {
                
                hb_progressResults.innerHTML = `
                    <div style="text-align: center; padding: 20px; color: #dc2626;">
                        <i class="fa fa-exclamation-triangle" style="font-size: 2em; margin-bottom: 10px;"></i>
                        <div>Error displaying progress</div>
                        <div style="font-size: 0.9em; margin-top: 5px; color: #6b7280;">
                            ${error.message}
                        </div>
                    </div>
                `;
            }
        }
    </script>
    <script>
        // === SIDEBAR TOGGLE ===
        const sidebarToggle = document.getElementById("openSidebar");
        const sidebarClose = document.getElementById("closeSidebar");
        const sidebar = document.getElementById("customSidebar");

        if (sidebarToggle && sidebarClose && sidebar) {
            sidebarToggle.addEventListener("click", () => {
                sidebar.classList.add("active");
            });

            sidebarClose.addEventListener("click", () => {
                sidebar.classList.remove("active");
            });
        }
    </script>
</body>

</html>
