<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thank You for Contacting Us - Muslim Funeral Society</title>
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
        .header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .title {
            color: #2563eb;
            font-size: 1.35rem;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 18px;
            text-align: center;
        }
        .content {
            padding: 36px 32px 28px 32px;
        }
        .message {
            font-size: 1.08rem;
            color: #333;
            line-height: 1.7;
            margin-bottom: 18px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .details-table th, .details-table td {
            text-align: left;
            padding: 7px 0;
            font-size: 1rem;
        }
        .details-table th {
            color: #2563eb;
            width: 120px;
            font-weight: 600;
            padding-right: 18px;
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
            <h1>Muslim Funeral Society</h1>
        </div>
        <div class="content">
            <div class="title">
                Thank You for Contacting Us
            </div>
            <div class="message">
                Dear {{ $contact->name ?? 'User' }},
                <br><br>
                Thank you for reaching out to the Muslim Funeral Society. We have received your message and our team will get back to you as soon as possible.
            </div>
            <table class="details-table">
                <tr>
                    <th>Name:</th>
                    <td>{{ $contact->name ?? '' }}</td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td>{{ $contact->email ?? '' }}</td>
                </tr>
                @if(!empty($contact->phone))
                <tr>
                    <th>Phone:</th>
                    <td>{{ $contact->phone }}</td>
                </tr>
                @endif
                <tr>
                    <th>Address:</th>
                    <td style="white-space: pre-line;">{{ $contact->address ?? '' }}</td>
                </tr>
                <tr>
                    <th>Message:</th>
                    <td style="background: #f9fafb; border-radius: 6px; padding: 8px 14px; color: #374151; font-size: 1rem; font-family: inherit;">
                        {{ $contact->message ?? '' }}
                    </td>
                </tr>
            </table>
            <hr class="divider">
            <div style="text-align:center; color:#4b5563; font-size: 1rem;">
                We appreciate your interest in our services.<br>
                <strong>The MFS Team</strong>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} MFS. All rights reserved.
        </div>
    </div>
</body>
</html>
