<!DOCTYPE html>
<html lang="en">
@php
    use App\Models\WebSetting;
    use App\Models\HomeUpdate;

    $websetting = WebSetting::first();
    $homeupdate = HomeUpdate::first();
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Forgot Password - MFS Portal</title>
    <link rel="icon" type="image/x-icon" href="{{ asset($websetting->favicon_icon) }}">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.4.0/css/all.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/styles/snackbar.css') }}">

    <style>
        body {
            background: linear-gradient(135deg, #042954, #0a1d3d);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            max-width: 450px;
            width: 100%;
            animation: fadeInUp 0.6s ease;
            padding: 2rem;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            text-align: center;
            color: #042954;
            margin-bottom: 1rem;
        }

        p {
            text-align: center;
            color: #64748b;
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #495057;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .form-group input:focus {
            border-color: #ff9800;
            box-shadow: 0 0 0 3px rgba(255, 152, 0, 0.15);
        }

        /* Custom valid/invalid styles */
        .form-group input.is-invalid {
            border-color: #ef4444 !important;
            background-color: #fff0f0;
        }

        .form-group input.is-valid {
            border-color: #22c55e !important;
            background-color: #f0fff4;
        }

        .form-group .invalid-feedback {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 0.3rem;
            display: block;
        }

        .form-group .valid-feedback {
            color: #22c55e;
            font-size: 0.85rem;
            margin-top: 0.3rem;
            display: block;
        }

        .submit-btn {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, #ff9800, #f57c00);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s;
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, #f57c00, #ef6c00);
            transform: translateY(-2px);
        }

        .alert {
            padding: 0.75rem 1rem;
            background: #ffe0b2;
            border: 1px solid #ff9800;
            color: #b26a00;
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
        }

        .login-link a {
            color: #ff9800;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">
        <div style="text-align:center; margin-bottom: 1.5rem;">
            <i class="fa-solid fa-unlock-keyhole" style="font-size:2.5rem; color:#ff9800;"></i>
        </div>
        <h2>Forgot Password</h2>
        <p>Enter your email and we’ll send you a OTP to reset your password.</p>

        @if (session('status'))
            <div class="alert">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('reset.submit') }}" id="forgetForm" novalidate>
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    placeholder="Enter your email"
                    class="@if($errors->has('email')) is-invalid @elseif(old('email')) is-valid @endif">
                @if($errors->has('email'))
                    <span class="invalid-feedback">{{ $errors->first('email') }}</span>
                @elseif(old('email'))
                    <span class="valid-feedback">Looks good!</span>
                @endif
            </div>

            <button type="submit" class="submit-btn">Send OTP</button>
        </form>

        <div class="login-link">
            <a href="{{ route('login') }}">Back to Login</a>
        </div>
    </div>
    @if (session('success') || $errors->any())
        <script>
            function showCustomSnackbar(message, type = 'success') {
                const icons = {
                    success: '<i class="fa-solid fa-circle-check snackbar-icon"></i>',
                    error: '<i class="fa-solid fa-circle-xmark snackbar-icon"></i>',
                    info: '<i class="fa-solid fa-circle-info snackbar-icon"></i>',
                    warning: '<i class="fa-solid fa-triangle-exclamation snackbar-icon"></i>',
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
                                <i class="fa-solid fa-times"></i>
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

    <script>
        // Custom client-side validation for email field
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('forgetForm');
            const emailInput = document.getElementById('email');

            function validateEmail(email) {
                // Simple email regex
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            }

            function setValidState(input, isValid, message = '') {
                input.classList.remove('is-valid', 'is-invalid');
                let feedback = input.parentElement.querySelector('.invalid-feedback, .valid-feedback');
                if (feedback) feedback.remove();

                if (isValid) {
                    input.classList.add('is-valid');
                    if (message) {
                        const span = document.createElement('span');
                        span.className = 'valid-feedback';
                        span.innerText = message;
                        input.parentElement.appendChild(span);
                    }
                } else {
                    input.classList.add('is-invalid');
                    if (message) {
                        const span = document.createElement('span');
                        span.className = 'invalid-feedback';
                        span.innerText = message;
                        input.parentElement.appendChild(span);
                    }
                }
            }

            emailInput.addEventListener('input', function () {
                if (emailInput.value.length === 0) {
                    setValidState(emailInput, false, 'Email is required.');
                } else if (!validateEmail(emailInput.value)) {
                    setValidState(emailInput, false, 'Please enter a valid email address.');
                } else {
                    setValidState(emailInput, true, 'Looks good!');
                }
            });

            form.addEventListener('submit', function (e) {
                let valid = true;
                if (emailInput.value.length === 0) {
                    setValidState(emailInput, false, 'Email is required.');
                    valid = false;
                } else if (!validateEmail(emailInput.value)) {
                    setValidState(emailInput, false, 'Please enter a valid email address.');
                    valid = false;
                } else {
                    setValidState(emailInput, true, 'Looks good!');
                }
                if (!valid) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>

</html>
