
document.addEventListener('DOMContentLoaded', function() {
    // Show skeleton preloader on page load
    showSkeleton();

    // Hide skeleton after 3-5 seconds
    setTimeout(() => {
        hideSkeleton();
    }, Math.random() * 2000 + 3000);

    // Alphabet filter functionality
    const alphabetButtons = document.querySelectorAll('.alphabet-btn');
    let currentAlphabet = '';

    alphabetButtons.forEach(button => {
        button.addEventListener('click', function() {
            const letter = this.getAttribute('data-letter');

            // Update active state
            alphabetButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            currentAlphabet = letter;

            // Show skeleton and load data
            showSkeleton();
            loadTransactionsWithAlphabet(letter);
        });
    });

    // Filter form submission
    const filterForm = document.getElementById('filterForm');
    const filterInputs = filterForm.querySelectorAll('input, select');

    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            showSkeleton();
            setTimeout(() => {
                filterForm.submit();
            }, 500);
        });
    });

    // Member search functionality
    setupMemberSearch();
    setupUniqueIdSearch();
});

function showSkeleton() {
    document.getElementById('skeletonContainer').style.display = 'block';
    document.getElementById('tableSection').style.display = 'none';
}

function hideSkeleton() {
    document.getElementById('skeletonContainer').style.display = 'none';
    document.getElementById('tableSection').style.display = 'block';
}

function resetAlphabetFilter() {
    const alphabetButtons = document.querySelectorAll('.alphabet-btn');
    alphabetButtons.forEach(btn => btn.classList.remove('active'));

    // Reset to "ALL" button
    const allButton = document.querySelector('.alphabet-btn[data-letter=""]');
    if (allButton) {
        allButton.classList.add('active');
    }

    currentAlphabet = '';
    showSkeleton();
    loadTransactionsWithAlphabet('');
}

function loadTransactionsWithAlphabet(letter) {
    const url = new URL(window.location);
    if (letter) {
        url.searchParams.set('alphabet', letter);
    } else {
        url.searchParams.delete('alphabet');
    }

    // Remove other filters when using alphabet
    url.searchParams.delete('member_name');
    url.searchParams.delete('unique_id');

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Parse the HTML and extract the table body
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newTableBody = doc.getElementById('transactionTableBody');

        if (newTableBody) {
            document.getElementById('transactionTableBody').innerHTML = newTableBody.innerHTML;
        }

        hideSkeleton();
    })
    .catch(error => {
        console.error('Error loading transactions:', error);
        hideSkeleton();
    });
}

function setupMemberSearch() {
    const memberInput = document.getElementById('memberInput');
    const memberOptions = document.getElementById('memberOptions');
    const memberSearch = document.getElementById('memberSearch');
    const selectedMember = document.getElementById('selectedMember');

    if (!memberInput || !memberOptions) return;

    const optionItems = memberOptions.querySelectorAll('.option-item-member');
    const noResults = memberOptions.querySelector('.no-results');

    memberInput.addEventListener('focus', function() {
        memberOptions.style.display = 'block';
        memberSearch.value = '';
        filterMemberOptions('');
        memberSearch.focus();
    });

    memberInput.addEventListener('click', function() {
        memberOptions.style.display = 'block';
        memberSearch.value = '';
        filterMemberOptions('');
        memberSearch.focus();
    });

    document.addEventListener('mousedown', function(e) {
        if (!memberInput.contains(e.target) && !memberOptions.contains(e.target)) {
            memberOptions.style.display = 'none';
        }
    });

    memberSearch.addEventListener('input', function() {
        filterMemberOptions(this.value);
    });

    function filterMemberOptions(search) {
        let found = false;
        optionItems.forEach(function(item) {
            if (item.textContent.toLowerCase().includes(search.toLowerCase())) {
                item.style.display = '';
                found = true;
            } else {
                item.style.display = 'none';
            }
        });
        noResults.style.display = found ? 'none' : '';
    }

    optionItems.forEach(function(item) {
        item.addEventListener('mousedown', function(e) {
            e.preventDefault();
            memberInput.value = this.getAttribute('data-value');
            selectedMember.value = this.getAttribute('data-value');
            memberOptions.style.display = 'none';
        });
    });

    if (selectedMember.value) {
        memberInput.value = selectedMember.value;
    }
}

function setupUniqueIdSearch() {
    const input = document.getElementById('uniqueInput');
    const optionsList = document.getElementById('optionsList');
    const searchBox = document.getElementById('searchBox');
    const hiddenInput = document.getElementById('selectedUniqueId');

    if (!input || !optionsList) return;

    const optionItems = optionsList.querySelectorAll('.option-item');
    const uniqueNoResults = optionsList.querySelector('.no-results');

    input.addEventListener('focus', () => {
        optionsList.style.display = 'block';
        searchBox.value = '';
        filterOptions('');
        searchBox.focus();
    });

    input.addEventListener('click', () => {
        optionsList.style.display = 'block';
        searchBox.value = '';
        filterOptions('');
        searchBox.focus();
    });

    document.addEventListener('mousedown', function(e) {
        if (!input.contains(e.target) && !optionsList.contains(e.target)) {
            optionsList.style.display = 'none';
        }
    });

    optionItems.forEach(item => {
        item.addEventListener('mousedown', (e) => {
            e.preventDefault();
            input.value = item.textContent;
            hiddenInput.value = item.dataset.value;
            optionsList.style.display = 'none';
        });
    });

    searchBox.addEventListener('input', () => {
        const filter = searchBox.value.toLowerCase();
        filterOptions(filter);
    });

    function filterOptions(filter) {
        let anyVisible = false;
        optionItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            const show = text.includes(filter);
            item.style.display = show ? '' : 'none';
            if (show) anyVisible = true;
        });
        if (uniqueNoResults) {
            uniqueNoResults.style.display = anyVisible ? 'none' : '';
        }
    }

    const selectedVal = hiddenInput.value;
    if (selectedVal) {
        const selected = [...optionItems].find(item => item.dataset.value === selectedVal);
        if (selected) {
            input.value = selected.textContent;
        }
    }
    
}

