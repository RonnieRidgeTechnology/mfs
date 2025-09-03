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
    <title>Login - MFS Portal</title>
    <link rel="icon" type="image/x-icon" href="{{ isset($websetting->favicon_icon) ? asset($websetting->favicon_icon) : '' }}">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.4.0/css/all.css ">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/styles/snackbar.css') }}">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        :root {
            --primary: #ff9800;
            --secondary: #fff;
            --text: #495057;
            --dark-blue: #042954;
            --light-bg: #f8fafc;
            --border: #e2e8f0;
        }

        body {
            background: linear-gradient(135deg, #042954, #0a1d3d);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            max-width: 450px;
            width: 100%;
            overflow: hidden;
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

        .login-header {
            padding: 2rem 2rem 1rem;
            text-align: center;
        }

        .login-header h2 {
            font-size: 1.8rem;
            color: var(--dark-blue);
            font-weight: 600;
        }

        .login-header p {
            color: #64748b;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        .login-form {
            padding: 0 2rem 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text);
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.9rem;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255, 152, 0, 0.15);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
        }

        .remember-forgot label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.3s;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .login-btn {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, #ff9800, #f57c00);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s;
        }

        .login-btn:hover {
            background: linear-gradient(135deg, #f57c00, #ef6c00);
            transform: translateY(-2px);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: #94a3b8;
            font-size: 0.85rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span {
            padding: 0 0.75rem;
        }

        .social-login {
            display: flex;
            gap: 1rem;
        }

        .social-btn {
            flex: 1;
            padding: 0.65rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            background: white;
            color: #495057;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .social-btn:hover {
            background: #f1f5f9;
        }

        .signup-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
        }

        .signup-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            border-radius: 6px;
            background: #ffe0b2;
            color: #b26a00;
            font-size: 0.95rem;
            border: 1px solid #ff9800;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="login-header">
            <h2>Welcome Back!</h2>
            <p>Please login to your account</p>
        </div>

        @if ($errors->any())
            <div class="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form class="login-form" method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    placeholder="Enter email">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required placeholder="Enter password">
            </div>

            <div class="remember-forgot">
                <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                </label>
                <a href="{{ route('reset') }}" class="forgot-link">Forgot Password?</a>
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>
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
