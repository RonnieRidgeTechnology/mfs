<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Annual Fee Payment Successful – MFS</title>
    <style>
        body {
            background-color: #f6f8fa;
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            padding: 0;
            margin: 0;
            color: #222;
        }
        .email-wrapper {
            background: #fff;
            margin: 40px auto;
            border-radius: 16px;
            box-shadow: 0 6px 32px rgba(0,0,0,0.10), 0 1.5px 4px rgba(0,0,0,0.04);
            overflow: hidden;
            border: 1px solid #e3e8ee;
        }
        .header {
            background: linear-gradient(90deg, #0052cc 0%, #2563eb 100%);
            color: #fff;
            padding: 32px 0 24px 0;
            text-align: center;
        }
        .header img {
            width: 56px;
            height: 56px;
            margin-bottom: 12px;
        }
        .header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .content {
            padding: 36px 32px 28px 32px;
        }
        .content h2 {
            margin-top: 0;
            color: #2563eb;
            font-size: 1.25rem;
            font-weight: 600;
        }
        .info-box {
            background: #f8fafc;
            border: 1.5px solid #e0e7ef;
            border-radius: 10px;
            padding: 20px 24px 16px 24px;
            margin: 28px 0 18px 0;
            box-shadow: 0 1px 4px rgba(37,99,235,0.04);
        }
        .info-row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .info-label {
            min-width: 120px;
            color: #555;
            font-weight: 500;
            font-size: 15px;
        }
        .info-value {
            color: #222;
            font-size: 15px;
            font-family: 'Fira Mono', 'Consolas', monospace;
            background: #e8eefc;
            padding: 2px 8px;
            border-radius: 4px;
            margin-left: 8px;
            letter-spacing: 0.5px;
        }
        .success {
            color: #2563eb;
            font-size: 15px;
            margin-top: 12px;
            font-weight: 600;
            background: #e8f5ff;
            border-left: 4px solid #2563eb;
            padding: 8px 14px;
            border-radius: 4px;
        }
        .login-button {
            display: inline-block;
            background: linear-gradient(90deg, #2563eb 0%, #0052cc 100%);
            color: #fff !important;
            padding: 14px 36px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.08rem;
            margin-top: 32px;
            box-shadow: 0 2px 8px rgba(37,99,235,0.10);
            transition: background 0.2s;
        }
        .login-button:hover {
            background: linear-gradient(90deg, #0052cc 0%, #2563eb 100%);
        }
        .footer {
            background: #f6f8fa;
            padding: 22px 0;
            text-align: center;
            font-size: 13px;
            color: #8a94a6;
            border-top: 1px solid #e3e8ee;
        }
        @media (max-width: 600px) {
            .email-wrapper {
                max-width: 98vw;
                margin: 0;
                border-radius: 0;
            }
            .content {
                padding: 22px 8vw 18px 8vw;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="header">
            <h1>Annual Fee Payment Successful</h1>
        </div>
        <div class="content">
            <h2>Hello {{ $user->name ?? '' }},</h2>
            <p style="font-size: 1.08rem; color: #333; margin-bottom: 0.7em;">
                We are pleased to inform you that your <strong>annual membership fee</strong> payment has been <span style="color:#2563eb;font-weight:600;">successfully completed</span>.
            </p>

            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Member Name:</span>
                    <span class="info-value">{{ $user->name ?? '' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Year:</span>
                    <span class="info-value">{{ $year ?? '' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Annual Fee Paid:</span>
                    <span class="info-value">£{{ number_format($amount ?? 0, 2) }}</span>
                </div>
                <div class="success">
                    <span style="display:inline-block;vertical-align:middle;margin-right:6px;">&#10003;</span>
                    Thank you for your timely payment and continued support!
                </div>
            </div>
            <p style="margin-top: 36px; color: #4b5563; font-size: 1rem;">
                If you have any questions or need assistance, <br>
                <a href="mailto:{{ $webSetting->email1 ?? 'support@yourdomain.com' }}" style="color:#2563eb;text-decoration:underline;">contact our support team</a> anytime.
            </p>

            <p style="margin-top: 32px; color: #222;">
                Best regards,<br>
                <strong>The MFS Team</strong>
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} MFS. All rights reserved.
        </div>
    </div>
</body>
</html>
