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
    <title>Verify OTP - MFS Portal</title>
    <link rel="icon" type="image/x-icon" href="{{ asset($websetting->favicon_icon) }}">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.4.0/css/all.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: linear-gradient(to right, #1b2b64, #0f172a);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
        }

        .container {
            background: #fff;
            padding: 2.8rem 2.2rem 2.2rem 2.2rem;
            border-radius: 22px;
            box-shadow: 0 16px 40px 0 rgba(30, 41, 59, 0.18), 0 2px 8px 0 rgba(59, 130, 246, 0.08);
            width: 100%;
            max-width: 430px;
            position: relative;
        }

        .icon-wrapper {
            text-align: center;
            margin-bottom: 1.7rem;
        }

        .icon-wrapper .icon-bg {
            background: linear-gradient(135deg, #3b82f6 0%, #1b2b64 100%);
            color: #fff;
            width: 92px;
            height: 92px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 3.2rem;
            box-shadow: 0 4px 24px 0 rgba(59, 130, 246, 0.18);
            position: relative;
            animation: pulse 2.2s infinite;
        }

        .icon-wrapper .icon-bg::after {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            border-radius: 50%;
            border: 2.5px solid #3b82f6;
            opacity: 0.18;
            z-index: 0;
        }

        .icon-wrapper .icon-bg i {
            z-index: 1;
            position: relative;
            text-shadow: 0 2px 8px rgba(59, 130, 246, 0.18);
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.10);
            }
        }

        h2 {
            text-align: center;
            margin-bottom: 1.1rem;
            color: #1e293b;
            font-size: 2.1rem;
            font-weight: 700;
            letter-spacing: 0.01em;
        }

        .alert {
            background: #fee2e2;
            border: 1.5px solid #f87171;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.1rem;
            color: #991b1b;
            font-size: 1rem;
            font-weight: 500;
        }

        .otp-inputs {
            display: flex;
            justify-content: center;
            gap: 0.7rem;
            margin-bottom: 1.7rem;
        }

        .otp-inputs input[type="text"] {
            width: 48px;
            height: 56px;
            text-align: center;
            font-size: 2rem;
            font-weight: 600;
            border: 2.5px solid #e5e7eb;
            border-radius: 10px;
            background: #f8fafc;
            outline: none;
            transition: border-color 0.25s, box-shadow 0.25s;
            box-shadow: 0 2px 8px 0 rgba(59, 130, 246, 0.04);
            color: #1e293b;
            letter-spacing: 0.1em;
        }

        .otp-inputs input[type="text"]:focus {
            border-color: #3b82f6;
            background: #fff;
            box-shadow: 0 0 0 2px #3b82f633;
        }

        .otp-inputs input[type="text"].is-invalid {
            border-color: #ef4444 !important;
            background: #fff0f0 !important;
        }

        .otp-inputs input[type="text"].is-valid {
            border-color: #38c172 !important;
            background: #e6ffed !important;
        }

        .otp-inputs input[type="text"].loading {
            background: #f1f5f9 !important;
            opacity: 0.7;
        }

        .otp-loader {
            display: none;
            justify-content: center;
            align-items: center;
            margin-bottom: 1rem;
        }

        .otp-loader.active {
            display: flex;
        }

        .otp-loader .spinner {
            border: 3px solid #e5e7eb;
            border-top: 3px solid #3b82f6;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .info-text {
            font-size: 1rem;
            color: #475569;
            margin-bottom: 1.2rem;
            text-align: center;
            font-weight: 500;
        }

        @media (max-width: 500px) {
            .container {
                padding: 1.5rem 0.5rem;
                max-width: 98vw;
            }

            .otp-inputs input[type="text"] {
                width: 38px;
                height: 44px;
                font-size: 1.3rem;
            }
        }

        .submit-btn {
            width: 100%;
            padding: 0.9rem 0;
            background: linear-gradient(90deg, #3b82f6 0%, #1b2b64 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 8px 0 rgba(59, 130, 246, 0.08);
        }

        .submit-btn:active {
            background: linear-gradient(90deg, #1b2b64 0%, #3b82f6 100%);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="icon-wrapper">
            <div class="icon-bg">
                <i class="fa-duotone fa-shield-check"
                    style="--fa-primary-color: #fff; --fa-secondary-color: #3b82f6;"></i>
            </div>
        </div>

        <h2>OTP Verification</h2>
        <p class="info-text">Enter the 6-digit code sent to your email address.</p>

        @if ($errors->any())
            <div class="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="otp-loader" id="otpLoader">
            <div class="spinner"></div>
            <span style="margin-left: 0.7rem; color: #3b82f6; font-weight: 500;">Verifying...</span>
        </div>

        <form method="POST" action="{{ route('otp.verify') }}" autocomplete="off" id="otpForm">
            @csrf
            <input type="hidden" name="email" id="otpEmail" value="{{ old('email', $email) }}">

            <div class="otp-inputs" id="otpInputs">
                @for ($i = 0; $i < 6; $i++)
                    <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" name="otp_digit_{{ $i }}"
                        id="otp_digit_{{ $i }}" class=""
                        value="{{ old('otp') && strlen(old('otp')) > $i ? substr(old('otp'), $i, 1) : '' }}"
                        autocomplete="off" required>
                @endfor
            </div>
            <input type="hidden" name="otp" id="otp" value="{{ old('otp') }}">

            <button type="submit" class="submit-btn">Verify OTP</button>
        </form>
    </div>
    <script>
        // CSRF setup for AJAX
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }

        document.addEventListener("DOMContentLoaded", () => {
            const otpInputs = document.querySelectorAll('.otp-inputs input');
            const hiddenOtp = document.getElementById('otp');
            const email = document.getElementById('otpEmail').value;
            const loader = document.getElementById('otpLoader');
            let isVerifying = false;
            let lastValidOtp = null;

            // Move focus and collect OTP
            otpInputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    // Only allow digits
                    input.value = input.value.replace(/[^0-9]/g, '');

                    // Move to next field if filled
                    if (input.value.length === 1 && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }

                    // Remove validation classes while typing
                    otpInputs.forEach(i => i.classList.remove('is-valid', 'is-invalid'));

                    // If all filled, trigger AJAX
                    let otpCode = '';
                    otpInputs.forEach(i => otpCode += i.value);
                    hiddenOtp.value = otpCode;

                    // Only trigger AJAX if all 6 digits are filled
                    if (otpCode.length === 6 && !isVerifying) {
                        verifyOtpLive(otpCode, email);
                    }
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !input.value && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                });

                input.addEventListener('focus', () => {
                    input.select();
                });
            });

            function setInputsDisabled(disabled) {
                otpInputs.forEach(i => {
                    i.disabled = disabled;
                    if (disabled) {
                        i.classList.add('loading');
                    } else {
                        i.classList.remove('loading');
                    }
                });
            }

            function verifyOtpLive(otpCode, email) {
                isVerifying = true;
                loader.classList.add('active');
                setInputsDisabled(true);

                fetch("{{ url('/verify-otp-live') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": getCsrfToken(),
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({ otp: otpCode, email: email })
                })
                    .then(response => response.json())
                    .then(data => {
                        // Only show green if correct OTP, otherwise red
                        if (data?.status === 'success') {
                            otpInputs.forEach(i => {
                                i.classList.remove('is-invalid');
                                i.classList.add('is-valid');
                            });
                            lastValidOtp = otpCode;
                        } else {
                            otpInputs.forEach(i => {
                                i.classList.remove('is-valid');
                                i.classList.add('is-invalid');
                            });
                            lastValidOtp = null;
                        }
                    })
                    .catch(() => {
                        otpInputs.forEach(i => {
                            i.classList.remove('is-valid');
                            i.classList.add('is-invalid');
                        });
                        lastValidOtp = null;
                    })
                    .finally(() => {
                        loader.classList.remove('active');
                        setInputsDisabled(false);
                        isVerifying = false;
                    });
            }

            // Fix: Remove red border if user changes OTP after a valid one
            otpInputs.forEach((input) => {
                input.addEventListener('input', () => {
                    let otpCode = '';
                    otpInputs.forEach(i => otpCode += i.value);
                    if (otpCode.length !== 6) {
                        otpInputs.forEach(i => {
                            i.classList.remove('is-valid', 'is-invalid');
                        });
                    }
                });
            });

            // Fix: If user enters correct OTP, then changes a digit, remove green border
            otpInputs.forEach((input) => {
                input.addEventListener('input', () => {
                    let otpCode = '';
                    otpInputs.forEach(i => otpCode += i.value);
                    if (lastValidOtp && otpCode !== lastValidOtp) {
                        otpInputs.forEach(i => i.classList.remove('is-valid'));
                    }
                });
            });
        });
    </script>
</body>

</html>
