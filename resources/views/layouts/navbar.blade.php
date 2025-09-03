

<div class="navbar-main">
    <div class="nav-log">
        <a href="{{ route('index') }}">Muslim Funeral Society</a>
    </div>

    <div class="nav-menu">
        <ul>
            <li><a href="{{ route('index') }}">Home</a></li>
            <li><a href="{{route('paymentsucces.public')}}">Membership Payment Status</a></li>
            <li><a href="{{ route('faq.public') }}">FAQ</a></li>
            <li><a href="{{ route('newmember.public') }}">New Membership</a></li>
            <li><a href="{{ route('rules.public') }}">Rules and Regulations</a></li>
            <li><a href="{{ route('payment.public') }}">Payment Info</a></li>
            <li><a href="{{ route('hmbc.public') }}">HMBC</a></li>
            <li class="dropdown-parent">
                <a href="#">
                    More
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="icon"
                        viewBox="0 0 24 24">
                        <path
                            d="M18 9c.852 0 1.297 .986 .783 1.623l-.076 .084-6 6a1 1 0 0 1 -1.32 .083l-.094 -.083-6-6-.083 -.094-.054 -.077-.054 -.096-.017 -.036-.027 -.067-.032 -.108-.01 -.053-.01 -.06-.004 -.057v-.118l.005 -.058.009 -.06.01 -.052.032 -.108.027 -.067.07 -.132.065 -.09.073 -.081.094 -.083.077 -.054.096 -.054.036 -.017.067 -.027.108 -.032.053 -.01.06 -.01.057 -.004h12.059z" />
                    </svg>
                </a>
                <div class="dropdown">
                    <ul>
                        <li><a href="{{ route('council.public') }}">What to do during a Bereavement?</a></li>
                        <li><a href="{{ route('contact.public') }}">Contact Us</a></li>
                        <li><a href="{{ route('about.public') }}">About Us</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
    <!-- Modern Sleek Search Dropdown -->
    <div class="nav-btn" style="position: relative; display:contents;">
        <button id="gd-search-toggle" type="button" aria-label="Search" class="gd-search-toggle-btn"
            style="background:#ffffff00;border: 1.5px solid #ffffff; color: #ffffff;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                style="vertical-align: middle;">
                <circle cx="11" cy="11" r="7" stroke="#ffffff" stroke-width="2" />
                <line x1="16.5" y1="16.5" x2="21" y2="21" stroke="#ffffff" stroke-width="2" stroke-linecap="round" />
            </svg>
            <span style="color: #ffffff;">Search</span>
        </button>
        <a href="{{ route('login') }}" class="gd-search-toggle-btn"
            style="fontsize:12px; background:#ffffff00; border: 1.5px solid #ffffff; color: #ffffff; margin-left: 10px; display: inline-flex; align-items: center; gap: 0.4em; padding: 6px 16px; border-radius: 999px; text-decoration: none;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                style="vertical-align: middle;">
                <path d="M10 17v-1a4 4 0 1 1 8 0v1" stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <circle cx="14" cy="7" r="4" stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M6 9v6" stroke="#ffffff" stroke-width="2" stroke-linecap="round" />
                <path d="M6 12h.01" stroke="#ffffff" stroke-width="2" stroke-linecap="round" />
            </svg>
            <span style="color: #ffffff;">Login</span>
        </a>
        <div id="gd-search-box" class="gd-search-box" style="display: none;">
            <div class="gd-search-input-wrapper">
                <span class="gd-search-icon">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="7" stroke="#888" stroke-width="2" />
                        <line x1="16.5" y1="16.5" x2="21" y2="21" stroke="#888" stroke-width="2"
                            stroke-linecap="round" />
                    </svg>
                </span>
                <input id="gd-search-input" type="text" placeholder="Search (e.g. thsis, membership, rules...)"
                    autocomplete="off" class="gd-search-input">
                <button id="gd-search-close" type="button" class="gd-search-close" aria-label="Close search">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24">
                        <path d="M18 6L6 18M6 6l12 12" stroke="#888" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </button>
            </div>
            <div class="gd-search-dropdown" id="gd-search-dropdown" style="display: none;">
                <div class="gd-search-tabs">
                    <button class="gd-search-tab active" data-tab="site">This site</button>
                    <button class="gd-search-tab" data-tab="embedded">
                        Embedded files
                        <span id="gd-embedded-count" class="gd-embedded-count"></span>
                    </button>
                </div>
                <div id="gd-embedded-count-badge" class="gd-embedded-count-badge">
                    <span>
                        <span id="gd-embedded-count-badge-number"></span> file<span
                            id="gd-embedded-count-badge-plural">s</span> found
                    </span>
                </div>
                <div class="gd-search-results" id="gd-search-results"></div>
                <div class="gd-search-no-results" id="gd-search-no-results">
                    <div>No results found</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Modern Sleek Search Dropdown Styles */
    .gd-search-toggle-btn {
        font-size: 12px;
        background: #fff;
        border: 1.5px solid #e3e8f0;
        border-radius: 999px;
        display: flex;
        align-items: center;
        gap: 0.5em;
        color: #1976d2;
        /* font-size: 1em; */
        font-weight: 500;
        padding: 0.45em 1.1em 0.45em 0.9em;
        box-shadow: 0 1px 4px #0001;
        transition: box-shadow 0.18s, border-color 0.18s, background 0.18s;
        cursor: pointer;
    }

    .gd-search-toggle-btn:hover,
    .gd-search-toggle-btn:focus {
        background: #f5f7fa;
        border-color: #b6c6e3;
        box-shadow: 0 2px 8px #1976d21a;
        outline: none;
    }

    .gd-search-toggle-btn span {
        font-weight: 500;
        letter-spacing: 0.01em;
    }

    .gd-search-box {
        position: absolute;
        top: 85%;
        right: 0;
        width: 370px;
        max-width: 98vw;
        z-index: 1000;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 32px #1976d21a, 0 2px 8px #0001;
        padding: 0.9em 0.9em 0.3em 0.9em;
        border: 1.5px solid #e3e8f0;
        transition: box-shadow 0.2s, border-color 0.2s;
        animation: gd-dropdown-fade 0.22s cubic-bezier(.4, 0, .2, 1);
    }

    .gd-search-input-wrapper {
        display: flex;
        align-items: center;
        background: #f5f7fa;
        border-radius: 999px;
        padding: 0.18em 0.9em 0.18em 0.9em;
        box-shadow: 0 1px 4px #1976d20a;
        position: relative;
        border: 1.5px solid #e3e8f0;
        margin-bottom: 0.5em;
    }

    .gd-search-icon {
        position: absolute;
        left: 1.1em;
        pointer-events: none;
        color: #888;
        top: 50%;
        transform: translateY(-50%);
    }

    .gd-search-input {
        width: 100%;
        border: none;
        outline: none;
        background: transparent;
        padding: 0.7em 2.2em 0.7em 2.5em;
        font-size: 1.08em;
        border-radius: 999px;
        color: #222;
        font-family: inherit;
        letter-spacing: 0.01em;
        transition: background 0.15s;
    }

    .gd-search-input:focus {
        background: #eaf1fb;
    }

    .gd-search-close {
        background: none;
        border: none;
        margin-left: 0.2em;
        cursor: pointer;
        padding: 0.2em;
        display: flex;
        align-items: center;
        border-radius: 50%;
        transition: background 0.15s;
    }

    .gd-search-close:hover,
    .gd-search-close:focus {
        background: #e3e8f0;
    }

    .gd-search-dropdown {
        margin-top: 0.5em;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 16px #1976d21a, 0 1px 4px #0001;
        animation: gd-dropdown-fade 0.22s cubic-bezier(.4, 0, .2, 1);
        min-height: 40px;
        border: 1.5px solid #e3e8f0;
        padding-bottom: 0.5em;
    }

    @keyframes gd-dropdown-fade {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .gd-search-tabs {
        display: flex;
        border-bottom: 1.5px solid #e3e8f0;
        background: #f7fafd;
        border-radius: 14px 14px 0 0;
        overflow: hidden;
        margin-bottom: 0.2em;
    }

    .gd-search-tab {
        flex: 1;
        padding: 0.7em 0;
        background: none;
        border: none;
        font-weight: 500;
        color: #1976d2;
        cursor: pointer;
        font-size: 1em;
        border-bottom: 2.5px solid transparent;
        transition: color 0.15s, border-bottom 0.15s, background 0.15s;
        border-radius: 0;
        position: relative;
    }

    .gd-search-tab.active {
        color: #fff;
        border-bottom: 2.5px solid #1976d2;
        background: linear-gradient(90deg, #1976d2 0%, #42a5f5 100%);
        font-weight: 600;
        z-index: 1;
    }

    .gd-embedded-count {
        display: none;
        margin-left: 0.5em;
        background: #e53935;
        color: #fff;
        border-radius: 50%;
        padding: 0 0.6em;
        font-size: 0.95em;
        font-weight: 600;
        vertical-align: middle;
    }

    .gd-embedded-count-badge {
        display: none;
        text-align: left;
        margin: 0.7em 0 0.2em 0;
    }

    .gd-embedded-count-badge span {
        display: inline-block;
        background: #e53935;
        color: #fff;
        border-radius: 999px;
        padding: 0.25em 0.9em;
        font-size: 1em;
        font-weight: 600;
    }

    .gd-search-results {
        max-height: 320px;
        overflow-y: auto;
        padding: 0.5em 0.2em 0.5em 0.2em;
    }

    .gd-search-result {
        padding: 1em 1.2em 0.9em 1.2em;
        border-radius: 12px;
        margin: 0.3em 0.2em;
        background: linear-gradient(90deg, #f8fafd 60%, #f0f4fa 100%);
        box-shadow: 0 1px 8px #1976d20a;
        cursor: pointer;
        transition: background 0.13s, box-shadow 0.13s;
        border: 1.5px solid transparent;
        display: flex;
        flex-direction: column;
        gap: 0.18em;
    }

    .gd-search-result:hover,
    .gd-search-result:focus {
        background: linear-gradient(90deg, #e3edfa 60%, #eaf1fb 100%);
        box-shadow: 0 2px 12px #1976d21a;
        border: 1.5px solid #1976d2;
        outline: none;
    }

    .gd-search-result-title {
        font-weight: 700;
        color: #1976d2;
        font-size: 1.13em;
        margin-bottom: 0.12em;
        text-decoration: none;
        display: block;
        letter-spacing: 0.01em;
        transition: color 0.13s;
    }

    .gd-search-result-desc {
        color: #444;
        font-size: 0.99em;
        opacity: 0.92;
        letter-spacing: 0.01em;
    }

    .gd-search-result-pdf-icon {
        display: inline-block;
        vertical-align: middle;
        margin-right: 0.4em;
    }

    .gd-search-no-results {
        display: none;
        padding: 1.5em 0;
        text-align: center;
        color: #888;
        font-size: 1.1em;
    }

    .gd-search-no-results>div {
        padding: 1.5em 0;
    }

    @media (max-width: 600px) {
        .gd-search-box {
            width: 98vw;
            left: 1vw;
            right: 1vw;
            min-width: 0;
            max-width: 99vw;
            padding: 0.5em 0.2em 0.2em 0.2em;
        }

        .gd-search-input-wrapper {
            padding: 0.18em 0.3em 0.18em 0.3em;
        }

        .gd-search-result {
            padding: 0.8em 0.7em 0.7em 0.7em;
        }
    }
</style>

@php
    // Fetch the latest HMBC PDF from the database
    $hmbcPdf = \App\Models\HMBC::orderByDesc('id')->value('pdf');
    $hmbcPdfUrl = $hmbcPdf ? asset('uploads/hmbc/' . $hmbcPdf) : null;
    $googlePdfViewerUrl = $hmbcPdfUrl ? 'https://docs.google.com/gview?url=' . urlencode($hmbcPdfUrl) . '&embedded=true' : null;

    // Simulated search data with categories
    $gdSearchData = [
        'site' => [
            [
                'title' => 'Home',
                'url' => route('index'),
                'description' => 'Muslim Funeral Society Home Page'
            ],
            [
                'title' => 'FAQ',
                'url' => route('faq.public'),
                'description' => 'Frequently Asked Questions'
            ],
            [
                'title' => 'New Membership',
                'url' => route('newmember.public'),
                'description' => 'Apply for new membership'
            ],
            [
                'title' => 'Rules and Regulations',
                'url' => route('rules.public'),
                'description' => 'Read the rules and regulations'
            ],
            [
                'title' => 'HMBC',
                'url' => route('hmbc.public'),
                'description' => 'HMBC Information'
            ],
            [
                'title' => 'Payment Info',
                'url' => route('payment.public'),
                'description' => 'Information about payment-info'
            ],
            [
                'title' => 'Thrse',
                'url' => '#',
                'description' => 'Thrse page or resource'
            ],
            [
                'title' => 'Contact Us',
                'url' => route('contact.public'),
                'description' => 'Contact the Muslim Funeral Society'
            ],
            [
                'title' => 'About Us',
                'url' => route('about.public'),
                'description' => 'Learn more about the Muslim Funeral Society'
            ],
            [
                'title' => 'What to do during a Bereavement?',
                'url' => route('council.public'),
                'description' => 'Guidance on what to do during a bereavement'
            ],
            [
                'title' => 'Membership Payment Status',
                'url' => route('paymentsucces.public'),
                'description' => 'View membership payment status'
            ],
        ],
        'embedded' => array_filter([
            $hmbcPdfUrl ? [
                'title' => 'HMBC Information (PDF)',
                'url' => $googlePdfViewerUrl,
                'description' => 'View the HMBC Information PDF file',
                'icon' => 'pdf',
                'target' => '_blank'
            ] : null,
            [
                'title' => 'New Member Application (PDF)',
                'url' => \App\Models\NewMember::orderByDesc('id')->value('pdf')
                    ? 'https://docs.google.com/gview?url=' . urlencode(asset(\App\Models\NewMember::orderByDesc('id')->value('pdf'))) . '&embedded=true'
                    : null,
                'description' => 'View the New Member Application PDF file',
                'icon' => 'pdf',
                'target' => '_blank'
            ],
            // Add "thsis" to embedded files as an example
            !empty(\App\Models\PaymentStatus::orderByDesc('id')->value('excel_file')) ? [
                'title' => 'Membership Payment Status (Excel)',
                'url' => \App\Models\PaymentStatus::orderByDesc('id')->value('excel_file'),
                'description' => 'View the Membership Payment Status Excel file',
                'icon' => 'excel',
                'target' => '_blank'
            ] : null,
        ])
    ];
 @endphp

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Data from PHP
            const gdSearchData = @json($gdSearchData);

            // Elements
            const searchToggle = document.getElementById('gd-search-toggle');
            const searchBox = document.getElementById('gd-search-box');
            const searchInput = document.getElementById('gd-search-input');
            const searchClose = document.getElementById('gd-search-close');
            const searchDropdown = document.getElementById('gd-search-dropdown');
            const searchResults = document.getElementById('gd-search-results');
            const searchNoResults = document.getElementById('gd-search-no-results');
            const searchTabs = searchDropdown.querySelectorAll('.gd-search-tab');
            const embeddedCountSpan = document.getElementById('gd-embedded-count');
            const embeddedCountBadge = document.getElementById('gd-embedded-count-badge');
            const embeddedCountBadgeNumber = document.getElementById('gd-embedded-count-badge-number');
            const embeddedCountBadgePlural = document.getElementById('gd-embedded-count-badge-plural');

            let activeTab = 'site';

            // Show/hide search box
            function openSearch() {
                searchBox.style.display = 'block';
                setTimeout(() => {
                    searchInput.focus();
                }, 100);
            }
            function closeSearch() {
                searchBox.style.display = 'none';
                searchInput.value = '';
                searchDropdown.style.display = 'none';
                // Hide badge when closing
                if (embeddedCountSpan) embeddedCountSpan.style.display = 'none';
                if (embeddedCountBadge) embeddedCountBadge.style.display = 'none';
            }

            searchToggle.addEventListener('click', function (e) {
                e.preventDefault();
                openSearch();
            });
            searchClose.addEventListener('click', function () {
                closeSearch();
            });

            // Hide on click outside
            document.addEventListener('mousedown', function (e) {
                if (searchBox.style.display === 'block' && !searchBox.contains(e.target) && !searchToggle.contains(e.target)) {
                    closeSearch();
                }
            });

            // Hide on ESC
            document.addEventListener('keydown', function (e) {
                if (e.key === "Escape" && searchBox.style.display === 'block') {
                    closeSearch();
                }
            });

            // Tab switching
            searchTabs.forEach(tab => {
                tab.addEventListener('click', function () {
                    searchTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    activeTab = this.getAttribute('data-tab');
                    performSearch(searchInput.value);
                });
            });

            // Search logic
            function performSearch(query) {
                const trimmed = query.trim().toLowerCase();
                let results = [];
                if (trimmed.length > 0) {
                    results = gdSearchData[activeTab].filter(item =>
                        item.title.toLowerCase().includes(trimmed) ||
                        item.description.toLowerCase().includes(trimmed)
                    );
                }
                renderResults(results, trimmed.length > 0, trimmed);
            }

            // Modified renderResults to show embedded file count on "site" tab if any embedded files found
            function renderResults(results, show, trimmedQuery) {
                searchResults.innerHTML = '';

                // Hide badge by default
                if (embeddedCountSpan) embeddedCountSpan.style.display = 'none';
                if (embeddedCountBadge) embeddedCountBadge.style.display = 'none';

                // Calculate embedded file matches for the current query
                let embeddedMatches = [];
                if (show && trimmedQuery !== undefined) {
                    embeddedMatches = gdSearchData['embedded'].filter(item =>
                        item.title.toLowerCase().includes(trimmedQuery) ||
                        item.description.toLowerCase().includes(trimmedQuery)
                    );
                }

                if (show && results.length > 0) {
                    searchDropdown.style.display = 'block';
                    searchNoResults.style.display = 'none';

                    // Show count badge for embedded files
                    if (activeTab === 'embedded') {
                        if (embeddedCountSpan) {
                            embeddedCountSpan.textContent = results.length;
                            embeddedCountSpan.style.display = 'inline-block';
                        }
                        if (embeddedCountBadge && embeddedCountBadgeNumber && embeddedCountBadgePlural) {
                            embeddedCountBadgeNumber.textContent = results.length;
                            embeddedCountBadgePlural.style.display = (results.length === 1) ? 'none' : '';
                            embeddedCountBadge.style.display = 'block';
                        }
                    } else {
                        // If on "site" tab, show embedded count badge in the tab if any embedded files found
                        if (embeddedCountSpan) {
                            if (embeddedMatches.length > 0) {
                                embeddedCountSpan.textContent = embeddedMatches.length;
                                embeddedCountSpan.style.display = 'inline-block';
                            } else {
                                embeddedCountSpan.style.display = 'none';
                            }
                        }
                        if (embeddedCountBadge) embeddedCountBadge.style.display = 'none';
                    }

                    // Only show the badge above results for embedded tab
                    if (activeTab !== 'embedded' && embeddedCountBadge) {
                        embeddedCountBadge.style.display = 'none';
                    }

                    results.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'gd-search-result';
                        div.tabIndex = 0;
                        let iconHtml = '';
                        if (item.icon === 'pdf') {
                            iconHtml = `<span class="gd-search-result-pdf-icon" title="PDF">
                                                 <svg width="18" height="18" viewBox="0 0 24 24" fill="white" style="vertical-align:middle;">
                                                    <path d="M6 2a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8.828A2 2 0 0 0 19.414 7.414l-4.828-4.828A2 2 0 0 0 12.172 2H6zm6 1.414L18.586 10H13a1 1 0 0 1-1-1V3.414zM6 4h5v5a3 3 0 0 0 3 3h5v10a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V4zm3.5 8a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-1 0v-2h-1v2a.5.5 0 0 1-1 0v-5a.5.5 0 0 1 1 0v2h1v-2a.5.5 0 0 1 .5-.5zm3.5.5a.5.5 0 0 1 .5-.5h1.5a1.5 1.5 0 0 1 0 3H14v1.5a.5.5 0 0 1-1 0v-5zm1.5 1H14v1h1a.5.5 0 0 0 0-1zm2.5-.5a.5.5 0 0 1 1 0v5a.5.5 0 0 1-1 0v-5z"/>
                                                </svg>
                                            </span>`;
                        }
                        div.innerHTML = `
                                            <a href="${item.url}" class="gd-search-result-title">${iconHtml}${item.title}</a>
                                            <div class="gd-search-result-desc">${item.description}</div>
                                        `;
                        div.addEventListener('click', function (e) {
                            // If user clicks the link, let it work as normal
                            if (e.target.tagName.toLowerCase() === 'a') return;
                            window.location.href = item.url;
                        });
                        div.addEventListener('keydown', function (e) {
                            if (e.key === 'Enter') window.location.href = item.url;
                        });
                        searchResults.appendChild(div);
                    });
                } else if (show && results.length === 0) {
                    searchDropdown.style.display = 'block';
                    searchNoResults.style.display = 'block';
                    // Show embedded count badge in the tab if any embedded files found
                    if (activeTab === 'embedded') {
                        if (embeddedCountSpan) embeddedCountSpan.style.display = 'none';
                        if (embeddedCountBadge) embeddedCountBadge.style.display = 'none';
                    } else {
                        if (embeddedCountSpan) {
                            if (embeddedMatches.length > 0) {
                                embeddedCountSpan.textContent = embeddedMatches.length;
                                embeddedCountSpan.style.display = 'inline-block';
                            } else {
                                embeddedCountSpan.style.display = 'none';
                            }
                        }
                        if (embeddedCountBadge) embeddedCountBadge.style.display = 'none';
                    }
                } else {
                    searchDropdown.style.display = 'none';
                    if (embeddedCountSpan) embeddedCountSpan.style.display = 'none';
                    if (embeddedCountBadge) embeddedCountBadge.style.display = 'none';
                }
            }

            // Input event
            searchInput.addEventListener('input', function () {
                performSearch(this.value);
            });

            // On open, reset everything
            searchToggle.addEventListener('click', function () {
                searchInput.value = '';
                performSearch('');
            });
        });
    </script>
@endsection
