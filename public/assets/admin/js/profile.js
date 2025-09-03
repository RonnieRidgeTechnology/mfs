document.addEventListener('DOMContentLoaded', function () {

    // Tab switching logic
    document.querySelectorAll('.tab-nav .tab-item').forEach(item => {
        item.addEventListener('click', () => {
            const tabId = item.getAttribute('data-tab');

            document.querySelectorAll('.tab-nav .tab-item').forEach(i => i.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

            item.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });

    // Profile image preview
    document.getElementById('profileImageInput').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('profilePreview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // --- Validation logic with "touched" state ---

    // Track which fields have been changed/touched
    const touchedFields = {};

    function setValidationState(input, isValid, message = '') {
        // Only show validation if field is touched
        if (touchedFields[input.id]) {
            if (isValid) {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
            } else {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
            }
        } else {
            input.classList.remove('is-valid', 'is-invalid');
        }
    }

    // Name validation
    function validateName() {
        const input = document.getElementById('name');
        const value = input.value.trim();
        setValidationState(input, value.length > 0);
    }

    // Email validation (readonly, but still show valid if present)
    function validateEmail() {
        const input = document.getElementById('email');
        const value = input.value.trim();
        const pattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        setValidationState(input, pattern.test(value));
    }

    // Phone validation (optional, but if present, must be valid)
    function validatePhone() {
        const input = document.getElementById('phone');
        const value = input.value.trim();
        if (value.length === 0) {
            input.classList.remove('is-valid', 'is-invalid');
            return;
        }
        // Simple phone validation: 7-15 digits, can have +, -, space
        const pattern = /^[\d\+\-\s]{7,15}$/;
        setValidationState(input, pattern.test(value));
    }

    // Role validation (readonly, always valid)
    function validateType() {
        const input = document.getElementById('type');
        setValidationState(input, true);
    }

    // Password validation
    function validatePassword() {
        const input = document.getElementById('password');
        const value = input.value;
        // Password policy: at least 8 chars, 1 upper, 1 lower, 1 number
        if (value.length === 0) {
            input.classList.remove('is-valid', 'is-invalid');
            return;
        }
        const valid = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/.test(value);
        setValidationState(input, valid);
    }

    // Password confirmation validation
    function validatePasswordConfirmation() {
        const input = document.getElementById('password_confirmation');
        const password = document.getElementById('password').value;
        const value = input.value;
        if (value.length === 0) {
            input.classList.remove('is-valid', 'is-invalid');
            return;
        }
        setValidationState(input, value === password && value.length > 0);
    }

    // Current password validation (required if changing password)
    function validateCurrentPassword() {
        const input = document.getElementById('current_password');
        const value = input.value;
        // Check if there is a server-side error for current password
        const errorMsgId = 'current-password-error-msg';
        let errorMsg = document.getElementById(errorMsgId);

        // Remove previous error message if exists
        if (errorMsg) {
            errorMsg.remove();
        }

        if (value.length === 0) {
            input.classList.remove('is-valid', 'is-invalid');
            return;
        }

        // If the input has a data attribute indicating wrong password from server
        if (input.dataset.invalid === "1") {
            if (touchedFields[input.id]) {
                setValidationState(input, false);
                // Show error message below the input
                errorMsg = document.createElement('div');
                errorMsg.id = errorMsgId;
                errorMsg.className = 'invalid-feedback';
                errorMsg.innerText = 'Please enter correct password.';
                input.parentNode.appendChild(errorMsg);
            } else {
                input.classList.remove('is-valid', 'is-invalid');
            }
        } else {
            setValidationState(input, value.length > 0);
        }
    }

    // Attach events for profile form
    document.getElementById('name').addEventListener('input', function () {
        touchedFields['name'] = true;
        validateName();
    });
    document.getElementById('email').addEventListener('input', function () {
        touchedFields['email'] = true;
        validateEmail();
    });
    document.getElementById('phone').addEventListener('input', function () {
        touchedFields['phone'] = true;
        validatePhone();
    });
    document.getElementById('type').addEventListener('input', function () {
        touchedFields['type'] = true;
        validateType();
    });

    // Attach events for password form
    if (document.getElementById('password')) {
        document.getElementById('password').addEventListener('input', function () {
            touchedFields['password'] = true;
            validatePassword();
            validatePasswordConfirmation();
        });
    }
    if (document.getElementById('password_confirmation')) {
        document.getElementById('password_confirmation').addEventListener('input', function () {
            touchedFields['password_confirmation'] = true;
            validatePasswordConfirmation();
        });
    }
    if (document.getElementById('current_password')) {
        document.getElementById('current_password').addEventListener('input', function () {
            touchedFields['current_password'] = true;
            validateCurrentPassword();
        });
    }

    // On form submit, hide all validation states (remove is-valid/is-invalid)
    document.getElementById('profileForm').addEventListener('submit', function (e) {
        // Remove all validation classes and touched state
        ['name', 'email', 'phone', 'type'].forEach(function (id) {
            var input = document.getElementById(id);
            if (input) {
                input.classList.remove('is-valid', 'is-invalid');
            }
            touchedFields[id] = false;
        });
        // Allow submit (do not preventDefault)
    });
    if (document.getElementById('securityForm')) {
        document.getElementById('securityForm').addEventListener('submit', function (e) {
            ['current_password', 'password', 'password_confirmation'].forEach(function (id) {
                var input = document.getElementById(id);
                if (input) {
                    input.classList.remove('is-valid', 'is-invalid');
                }
                touchedFields[id] = false;
            });
            // Allow submit (do not preventDefault)
        });
    }

    // Do NOT show validation on page load
    // (No initial validation calls)
});
