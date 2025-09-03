<style>
    /* Basic pagination styles */
.pagination {
    display: flex;
    list-style: none;
    padding: 10px 0;
    justify-content: center;
    align-items: center;
}

.pagination li {
    margin: 0 2px;
}

/* Style for previous and next buttons */
.page-link {
    display: inline-block;
    padding: 10px 15px;
    background-color: #e5e7eb; /* Inactive background */
    color: #334155; /* Inactive text */
    border: 1px solid #cbd5e1;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s, color 0.3s;
}

.page-link:hover {
    background-color: #6366f1;
    color: #fff;
    border-color: #6366f1;
}

/* Style for the current (active) page */
.page-item.active .page-link,
.page-link.current {
    background-color: #042954 !important;
    color: #fff !important;
    border: 1px solid #042954 !important;
    font-weight: bold;
    box-shadow: 0 2px 8px rgba(99,102,241,0.08);
}

/* Disabled state */
.page-item.disabled .page-link,
.disabled .page-link {
    pointer-events: none;
    opacity: 0.6;
    background-color: #f1f5f9 !important;
    color: #a1a1aa !important;
    border: 1px solid #e5e7eb !important;
}

.page-item a{
    display: flex !important;
    align-items: center !important;
}
.page-item span{
    display: flex !important;
    align-items: center !important;
}
</style>

<!-- Enhanced Pagination with Items Per Page -->
<div class="enhanced-pagination-container" style="display: flex; justify-content: space-between; align-items: center; padding: 20px; background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 12px; margin-top: 20px; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);">

    <!-- Items Per Page Control -->
    <div class="items-per-page-control" style="display: flex; align-items: center; gap: 10px;">
        <label style="font-size: 0.9em; color: #64748b; font-weight: 500;">Show:</label>
        <select id="itemsPerPage" onchange="changeItemsPerPage(this.value)" style="padding: 6px 12px; border: 1px solid #e2e8f0; border-radius: 6px; background: #fff; font-size: 0.9em; color: #334155; cursor: pointer;">
            {{-- <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5 items</option> --}}
            <option value="10" {{ request('per_page', 5) == 10 ? 'selected' : '' }}>10 items</option>
            <option value="25" {{ request('per_page', 5) == 25 ? 'selected' : '' }}>25 items</option>
            <option value="50" {{ request('per_page', 5) == 50 ? 'selected' : '' }}>50 items</option>
            <option value="100" {{ request('per_page', 5) == 100 ? 'selected' : '' }}>100 items</option>
        </select>
        <span style="font-size: 0.9em; color: #64748b;">per page</span>
    </div>

    <!-- Pagination Info -->
    <div class="pagination-info" style="display: flex; align-items: center; gap: 15px;">
        <span style="font-size: 0.9em; color: #64748b;">
            Showing {{ $paginator->firstItem() ?? 0 }} to {{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }} results
        </span>
    </div>

    <!-- Pagination Controls -->
    @if ($paginator->lastPage() > 1)
        <ul class="pagination" style="margin: 0;">
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">&laquo;</span>
            </li>
        @else
            <li class="page-item" id="previousPage">
                <a href="{{ $paginator->previousPageUrl() }}" class="page-link">&laquo;</a>
            </li>
        @endif

        @php
            $visiblePages = 5; // Number of visible pages
            $half = floor($visiblePages / 2);
            $start = max(1, $paginator->currentPage() - $half);
            $end = min($paginator->lastPage(), $start + $visiblePages - 1);
        @endphp

        @if ($start > 1)
            <li class="page-item">
                <a href="{{ $paginator->url(1) }}" class="page-link">1</a>
            </li>
            <li class="page-item disabled">
                <span class="page-link">...</span>
            </li>
        @endif

        @for ($i = $start; $i <= $end; $i++)
            @if ($i == $paginator->currentPage())
                <li class="page-item active">
                    <span class="page-link">{{ $i }}</span>
                </li>
            @else
                <li class="page-item">
                    <a href="{{ $paginator->url($i) }}" class="page-link">{{ $i }}</a>
                </li>
            @endif
        @endfor

        @if ($end < $paginator->lastPage())
            <li class="page-item disabled">
                <span class="page-link">...</span>
            </li>
            <li class="page-item">
                <a href="{{ $paginator->url($paginator->lastPage()) }}" class="page-link">{{ $paginator->lastPage() }}</a>
            </li>
        @endif

        @if ($paginator->hasMorePages())
            <li class="page-item" id="nextPage">
                <a href="{{ $paginator->nextPageUrl() }}" class="page-link">&raquo;</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">&raquo;</span>
            </li>
        @endif
        </ul>
    @endif
</div>




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Get the current URL parameters
        const currentParams = new URLSearchParams(window.location.search);

        // Add a click event handler to all pagination links
        $('.pagination a.page-link').click(function (event) {
            event.preventDefault();

            // Get the URL from the clicked pagination link
            const url = new URL($(this).attr('href'), window.location.origin);

            // Get the page parameter from the clicked URL
            const pageParam = url.searchParams.get('page');

            if (pageParam) {
                // If the clicked URL contains a page parameter, update it
                currentParams.set('page', pageParam);
            } else {
                // If the clicked URL doesn't contain a page parameter, remove it from the current parameters
                currentParams.delete('page');
            }

            // Update the URL with the modified parameters
            url.search = currentParams.toString();
            window.location.href = url.toString();
        });
    });

    // Function to change items per page
    function changeItemsPerPage(itemsPerPage) {
        const currentUrl = new URL(window.location);

        // Set the per_page parameter
        currentUrl.searchParams.set('per_page', itemsPerPage);

        // Reset to first page when changing items per page
        currentUrl.searchParams.delete('page');

        // Preserve all other existing parameters (type, period, etc.)
        const existingParams = new URLSearchParams(window.location.search);
        existingParams.forEach((value, key) => {
            if (key !== 'page' && key !== 'per_page') {
                currentUrl.searchParams.set(key, value);
            }
        });

        window.location.href = currentUrl.toString();
    }
</script>
