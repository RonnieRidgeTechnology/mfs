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
    <title>Reset Password - MFS Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset($websetting->favicon_icon) }}">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.4.0/css/all.css">
    <link rel="stylesheet" href="{{ asset('assets/admin/styles/snackbar.css') }}">

    <style>
        :root {
            --primary: #ff9800;
            --primary-dark: #f57c00;
            --text-dark: #042954;
            --muted: #64748b;
            --border: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #042954, #0a1d3d);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            max-width: 450px;
            width: 100%;
            padding: 2rem;
            animation: fadeInUp 0.6s ease;
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
            font-size: 1.8rem;
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        p.subtitle {
            text-align: center;
            color: var(--muted);
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            color: #495057;
            display: block;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255, 152, 0, 0.15);
            outline: none;
        }

        .submit-btn {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s;
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, var(--primary-dark), #ef6c00);
            transform: translateY(-2px);
        }

        .alert {
            padding: 0.75rem 1rem;
            background: #ffe0b2;
            border: 1px solid var(--primary);
            color: #b26a00;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
        }

        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Reset Password</h2>
        <p class="subtitle">Enter your email and a new password to complete the reset process.</p>

        @if ($errors->any())
            <div class="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            {{-- No token field needed for OTP-based reset --}}

            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required autofocus
                    placeholder="Enter your email" readonly>
            </div>

            <div class="form-group">
                <label for="password">New Password</label>
                <input id="password" type="password" name="password" required placeholder="Enter new password">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm New Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    placeholder="Confirm new password">
            </div>

            <button type="submit" class="submit-btn">Reset Password</button>
        </form>

        <div class="login-link">
            <a href="{{ route('login') }}"><i class="fa-solid fa-arrow-left-long"></i> Back to Login</a>
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
</body>

</html>
