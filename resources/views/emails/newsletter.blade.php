<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MFS Newsletter</title>
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
        .newsletter-title {
            color: #2563eb;
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 18px;
            text-align: center;
        }
        .content {
            padding: 36px 32px 28px 32px;
        }
        .newsletter-content {
            font-size: 1.08rem;
            color: #333;
            line-height: 1.7;
        }
        .divider {
            border: none;
            border-top: 1.5px solid #e3e8ee;
            margin: 32px 0 24px 0;
        }
        .footer {
            background: #f6f8fa;
            padding: 22px 0;
            text-align: center;
            font-size: 13px;
            color: #8a94a6;
            border-top: 1px solid #e3e8ee;
        }
        .unsubscribe {
            display: inline-block;
            margin-top: 18px;
            color: #2563eb;
            text-decoration: underline;
            font-size: 13px;
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
            <!-- <img src="https://yourdomain.com/logo.png" alt="MFS Logo"> -->
            <h1>Muslim Funeral Society</h1>
        </div>
        <div class="content">
            <div class="newsletter-title">
                {{ $subjectLine ?? 'Newsletter' }}
            </div>
            <div class="newsletter-content">
                {!! $content !!}
            </div>
            <hr class="divider">
            <div style="text-align:center; color:#4b5563; font-size: 1rem;">
                Thank you for being a valued part of our community.<br>
                <strong>The MFS Team</strong>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} MFS. All rights reserved.<br>
                </div>
    </div>
</body>
</html>
