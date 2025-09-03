document.addEventListener("DOMContentLoaded", function () {

    // Toggle sub-navigation
    document.querySelectorAll('.nav-item').forEach(item => {
        const link = item.querySelector('.nav-link');
        const subNav = item.querySelector('.sub-nav');

        if (subNav) {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                item.classList.toggle('active');
                subNav.classList.toggle('active');
            });
        }
    });

    // Dropdown functionality
    document.querySelectorAll('.custom-dropdown').forEach(dropdown => {
        const trigger = dropdown.querySelector('.dropdown-trigger');
        const menu = dropdown.querySelector('.dropdown-menu');
        const items = dropdown.querySelectorAll('.dropdown-item');
        const triggerText = trigger.querySelector('span');

        trigger.addEventListener('click', () => {
            dropdown.classList.toggle('active');
        });

        items.forEach(item => {
            item.addEventListener('click', () => {
                triggerText.textContent = item.textContent;
                items.forEach(i => i.classList.remove('active'));
                item.classList.add('active');
                dropdown.classList.remove('active');
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('active');
            }
        });
    });

    // Modal functionality
    const addButton = document.querySelector('.add-new');
    const modalOverlay = document.querySelector('.modal-overlay');
    const closeModal = document.querySelector('.close-modal');
    const cancelButton = document.querySelector('.btn-cancel');
    const modal = document.querySelector('.modal');

    function openModal() {
        modalOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModalFunc() {
        modalOverlay.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
    addButton.addEventListener('click', openModal);
    closeModal.addEventListener('click', closeModalFunc);
    cancelButton.addEventListener('click', closeModalFunc);

    modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) {
            closeModalFunc();
        }
    });

    modal.addEventListener('click', (e) => {
        e.stopPropagation();
    });

    // Custom Select Functionality
    document.querySelectorAll('.custom-select').forEach(select => {
        const trigger = select.querySelector('.select-trigger');
        const menu = select.querySelector('.select-menu');
        const options = select.querySelectorAll('.select-option');
        const triggerText = trigger.querySelector('span');

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            trigger.classList.toggle('active');
            menu.classList.toggle('active');
        });

        options.forEach(option => {
            option.addEventListener('click', () => {
                triggerText.textContent = option.textContent;
                options.forEach(opt => opt.classList.remove('selected'));
                option.classList.add('selected');
                trigger.classList.remove('active');
                menu.classList.remove('active');
            });
        });

        document.addEventListener('click', () => {
            trigger.classList.remove('active');
            menu.classList.remove('active');
        });
    });

    // User Profile Dropdown
    const userProfile = document.querySelector('.user-profile');
    const profileTrigger = userProfile.querySelector('.profile-trigger');

    profileTrigger.addEventListener('click', (e) => {
        e.stopPropagation();
        userProfile.classList.toggle('active');
    });

    // Close profile dropdown when clicking outside
    document.addEventListener('click', () => {
        userProfile.classList.remove('active');
    });

    // Add this JavaScript for the glow effect
    document.querySelectorAll('.action-button').forEach(button => {
        button.addEventListener('mousemove', e => {
            const rect = button.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;
            button.style.setProperty('--x', `${x}%`);
            button.style.setProperty('--y', `${y}%`);
        });
    });

    // Notification Dropdown
    const notification = document.querySelector('.notification');
    const notificationIcon = notification.querySelector('i');

    notificationIcon.addEventListener('click', (e) => {
        e.stopPropagation();
        notification.classList.toggle('active');
    });

    // Close notification dropdown when clicking outside
    document.addEventListener('click', () => {
        notification.classList.remove('active');
    });

})
