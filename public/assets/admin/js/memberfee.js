
// Table Controller Script
document.addEventListener('DOMContentLoaded', function () {
    function setColVisibility(colClass, visible) {
        document.querySelectorAll('.' + colClass).forEach(function (el) {
            el.style.display = visible ? '' : 'none';
        });
    }
    // Handle individual column toggles
    document.querySelectorAll('.toggle-col').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            setColVisibility(this.dataset.col, this.checked);
        });
    });
    // Show all columns
    document.getElementById('show-all-columns').addEventListener('click', function () {
        document.querySelectorAll('.toggle-col').forEach(function (cb) {
            cb.checked = true;
            setColVisibility(cb.dataset.col, true);
        });
    });

    // Hide all columns
    document.getElementById('hide-all-columns').addEventListener('click', function () {
        document.querySelectorAll('.toggle-col').forEach(function (cb) {
            cb.checked = false;
            setColVisibility(cb.dataset.col, false);
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Show skeleton for 3 seconds, then show real table
    setTimeout(() => {
        document.getElementById('skeleton-loader').style.display = 'none';
        document.getElementById('real-table-container').style.display = 'block';
    }, 3000);

    // --- Modal & Custom Select Logic ---
    const modalOverlay = document.querySelector('.modal-overlay');
    const closeModalBtn = document.querySelector('.close-modal');
    const cancelButton = document.querySelector('.btn-cancel');
    const modal = document.querySelector('.modal');
    const form = document.getElementById('membership-form');
    const modalTitle = document.querySelector('.modal-title');
    const formMethod = document.getElementById('form-method');
    const settingIdInput = document.getElementById('setting-id');
    const memberTypeInput = document.getElementById('member_type');
    const amountInput = document.getElementById('amount');
    const yearInput = document.getElementById('year');

    const customSelectMemberType = document.getElementById('custom-select-member-type');
    const selectTriggerMemberType = customSelectMemberType.querySelector('.select-trigger');
    const selectMenuMemberType = customSelectMemberType.querySelector('.select-menu');
    const selectOptionsMemberType = customSelectMemberType.querySelectorAll('.select-option');
    const triggerTextMemberType = selectTriggerMemberType.querySelector('span');

    const customSelectYear = document.getElementById('custom-select-year');
    const selectTriggerYear = customSelectYear.querySelector('.select-trigger');
    const selectMenuYear = customSelectYear.querySelector('.select-menu');
    const yearSearchInput = document.getElementById('year-search');
    const selectOptionsYear = customSelectYear.querySelectorAll('.select-option');
    const triggerTextYear = selectTriggerYear.querySelector('span');

    const addBtn = document.getElementById('add-membership-setting');

    // Open modal
    addBtn.addEventListener('click', () => openModal(false, null));

    document.querySelectorAll('.action-btn.edit').forEach(btn => {
        btn.addEventListener('click', function () {
            if (btn.hasAttribute('disabled')) return;
            const id = this.getAttribute('data-id');
            fetch(`/membership-setting/${id}/edit`)
                .then(res => res.json())
                .then(data => openModal(true, data));
        });
    });

    // Fix: Use plain URLs for form actions, not Blade syntax
    function openModal(isEdit, data) {
        modalOverlay.style.display = 'block';
        setTimeout(() => modalOverlay.classList.add('active'), 10);
        document.body.style.overflow = 'hidden';

        if (isEdit && data) {
            modalTitle.textContent = 'Edit Membership Fee Setting';
            // Use plain URL for edit (PUT)
            form.action = `/membership-setting/${data.id}`;
            formMethod.value = 'PUT';
            settingIdInput.value = data.id;
            setCustomSelectValue('member_type', data.member_type);
            setCustomSelectValue('year', data.year.toString());
            amountInput.value = data.amount;
        } else {
            modalTitle.textContent = 'Add Membership Fee Setting';
            // Use plain URL for add (POST)
            form.action = '/membership-setting';
            formMethod.value = 'POST';
            settingIdInput.value = '';
            setCustomSelectValue('member_type', '');
            setCustomSelectValue('year', new Date().getFullYear().toString());
            amountInput.value = '';
        }
    }

    function closeModalFunc() {
        modalOverlay.classList.remove('active');
        document.body.style.overflow = 'auto';
        setTimeout(() => modalOverlay.style.display = 'none', 200);
    }
    closeModalBtn.addEventListener('click', closeModalFunc);
    cancelButton.addEventListener('click', closeModalFunc);
    modalOverlay.addEventListener('click', e => e.target === modalOverlay && closeModalFunc());
    modal.addEventListener('click', e => e.stopPropagation());

    // Custom Select Handling
    function setCustomSelectValue(type, value) {
        const isYear = type === 'year';
        const options = isYear ? selectOptionsYear : selectOptionsMemberType;
        const triggerText = isYear ? triggerTextYear : triggerTextMemberType;
        const input = isYear ? yearInput : memberTypeInput;
        let found = false;
        options.forEach(opt => {
            if (opt.dataset.value === value) {
                triggerText.textContent = opt.textContent;
                input.value = value;
                opt.classList.add('selected');
                found = true;
            } else {
                opt.classList.remove('selected');
            }
        });
        if (!found) triggerText.textContent = isYear ? 'Select Year' : 'Select Member Type';
    }

    [selectTriggerMemberType, selectTriggerYear].forEach(trigger => {
        trigger.addEventListener('click', function (e) {
            e.stopPropagation();
            const isYear = this.closest('#custom-select-year');
            const menu = isYear ? selectMenuYear : selectMenuMemberType;
            const triggerEl = isYear ? selectTriggerYear : selectTriggerMemberType;
            [selectTriggerMemberType, selectTriggerYear].forEach(t => {
                if (t !== triggerEl) t.classList.remove('active');
            });
            [selectMenuMemberType, selectMenuYear].forEach(m => {
                if (m !== menu) m.classList.remove('active');
            });
            triggerEl.classList.toggle('active');
            menu.classList.toggle('active');
            if (isYear && menu.classList.contains('active')) {
                yearSearchInput.focus();
                filterYearOptions('');
            }
        });
    });

    yearSearchInput?.addEventListener('input', e => filterYearOptions(e.target.value.trim().toLowerCase()));
    function filterYearOptions(query) {
        selectOptionsYear.forEach(opt => {
            const text = opt.textContent.trim().toLowerCase();
            opt.style.display = query === '' || text.includes(query) ? 'block' : 'none';
        });
    }

    [...selectOptionsMemberType, ...selectOptionsYear].forEach(option => {
        option.addEventListener('click', function () {
            const isYear = this.closest('#custom-select-year');
            const triggerText = isYear ? triggerTextYear : triggerTextMemberType;
            const input = isYear ? yearInput : memberTypeInput;
            triggerText.textContent = this.textContent;
            input.value = this.dataset.value;
            (isYear ? selectOptionsYear : selectOptionsMemberType).forEach(o => o.classList.remove('selected'));
            this.classList.add('selected');
            if (isYear) {
                selectTriggerYear.classList.remove('active');
                selectMenuYear.classList.remove('active');
                yearSearchInput.value = '';
            } else {
                selectTriggerMemberType.classList.remove('active');
                selectMenuMemberType.classList.remove('active');
            }
        });
    });

    document.addEventListener('click', () => {
        selectTriggerMemberType.classList.remove('active');
        selectMenuMemberType.classList.remove('active');
        selectTriggerYear.classList.remove('active');
        selectMenuYear.classList.remove('active');
        if (yearSearchInput) yearSearchInput.value = '';
    });

    // --- Filtering & Table Controls ---
    const filterYear = document.getElementById('filter-year');
    const filterType = document.getElementById('filter-type');
    const tableRows = () => Array.from(document.querySelectorAll('#membership-fee-table tbody tr[data-id]'));
    const noDataRow = document.querySelector('.no-data-row');
    const noFilterDataRow = document.querySelector('.no-filter-data-row');

    function filterTable() {
        const yearVal = filterYear.value;
        const typeVal = filterType.value;
        let visibleCount = 0;

        tableRows().forEach(row => {
            const rowYear = row.getAttribute('data-year');
            const rowType = row.getAttribute('data-type');
            const matchYear = !yearVal || rowYear === yearVal;
            const matchType = !typeVal || rowType === typeVal;
            if (matchYear && matchType) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Show appropriate message
        if (tableRows().length === 0) {
            noDataRow.style.display = 'table-row';
            noFilterDataRow.style.display = 'none';
        } else if (visibleCount === 0) {
            noDataRow.style.display = 'none';
            noFilterDataRow.style.display = 'table-row';
        } else {
            noDataRow.style.display = 'none';
            noFilterDataRow.style.display = 'none';
        }
    }

    filterYear.addEventListener('change', filterTable);
    filterType.addEventListener('change', filterTable);

    document.getElementById('reset-filters').addEventListener('click', function () {
        filterYear.selectedIndex = 0;
        filterType.selectedIndex = 0;
        filterTable();
    });

    // --- Column Visibility ---
    const columnMap = [
        { id: 'showMemberId', className: 'member-id' },
        { id: 'showName', className: 'name' },
        { id: 'showPhone', className: 'phone' },
        { id: 'showAddress', className: 'address' },
        { id: 'showAccount', className: 'account' },
        { id: 'showStatus', className: 'status' },
        { id: 'showDate', className: 'date' },
        { id: 'showProgress', className: 'progress' },
        { id: 'showAmount', className: 'amount' }
    ];

    function toggleColumn(checkbox, className) {
        const isChecked = checkbox.checked;
        document.querySelectorAll('.' + className).forEach(el => {
            el.style.display = isChecked ? '' : 'none';
        });
    }

    columnMap.forEach(col => {
        const checkbox = document.getElementById(col.id);
        checkbox.addEventListener('change', () => toggleColumn(checkbox, col.className));
    });

    document.getElementById('show-all-columns').addEventListener('click', () => {
        columnMap.forEach(col => {
            const checkbox = document.getElementById(col.id);
            checkbox.checked = true;
            toggleColumn(checkbox, col.className);
        });
    });

    document.getElementById('hide-all-columns').addEventListener('click', () => {
        columnMap.forEach(col => {
            const checkbox = document.getElementById(col.id);
            checkbox.checked = false;
            toggleColumn(checkbox, col.className);
        });
        // Always show Actions
        document.querySelectorAll('.actions').forEach(el => el.style.display = '');
    });

    // Init on load
    columnMap.forEach(col => {
        const checkbox = document.getElementById(col.id);
        toggleColumn(checkbox, col.className);
    });
    filterTable();
});
