<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Password Reset OTP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            background-color: #f4f7fa;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .email-header {
            background: linear-gradient(135deg, #042954, #0a1d3d);
            padding: 24px;
            text-align: center;
        }

        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 22px;
        }

        .email-body {
            padding: 30px;
        }

        .email-body h2 {
            color: #042954;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .email-body p {
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 20px;
            color: #4a4a4a;
        }

        .otp-box {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            color: #f57c00;
            background: #fff3e0;
            border: 2px dashed #ff9800;
            border-radius: 10px;
            padding: 16px;
            margin: 20px auto;
            width: fit-content;
        }

        .email-footer {
            text-align: center;
            padding: 20px;
            font-size: 13px;
            color: #888;
            background-color: #f9fafc;
        }

        .email-footer a {
            color: #ff9800;
            text-decoration: none;
        }

        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 20px;
            }

            .otp-box {
                font-size: 24px;
                padding: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="email-header">
            <h1>MFS Admin</h1>
        </div>

        <div class="email-body">
            <h2>Hello {{ $user->name }},</h2>
            <p>You recently requested to reset your password for your MFS account.</p>

            <p>Please use the following OTP code to complete your password reset:</p>

            <div class="otp-box">
                {{ $otp }}
            </div>

            <p>This OTP is valid for the next 5 minutes. Do not share it with anyone.</p>

            <p>If you did not request a password reset, you can safely ignore this email.</p>

            <p>Thanks,<br>The MFS Team</p>
        </div>

        <div class="email-footer">
            &copy; {{ date('Y') }} MFS. All rights reserved.
            <br>
            <a href="{{ url('/') }}">{{ url('/') }}</a>
        </div>
    </div>
</body>

</html>
