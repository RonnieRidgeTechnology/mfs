<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>403 Forbidden</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap');
        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            overflow: hidden;
        }
        .error-container {
            background: rgba(255,255,255,0.97);
            border-radius: 22px;
            padding: 56px 38px 44px 38px;
            max-width: 410px;
            width: 100%;
            text-align: center;
            position: relative;
            animation: fadeInUp 0.8s cubic-bezier(.4,0,.2,1);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(60px) scale(0.98);}
            to { opacity: 1; transform: translateY(0) scale(1);}
        }
        .icon-lock {
            width: 70px;
            height: 70px;
            margin-bottom: 20px;
            display: inline-block;
            animation: bounceIn 1.1s cubic-bezier(.4,0,.2,1);
        }
        @keyframes bounceIn {
            0% { transform: scale(0.7) translateY(-40px); opacity: 0; }
            60% { transform: scale(1.1) translateY(10px); opacity: 1; }
            80% { transform: scale(0.95) translateY(-4px);}
            100% { transform: scale(1) translateY(0);}
        }
        .error-code {
            font-size: 5.2rem;
            font-weight: 800;
            color: #2563eb;
            margin-bottom: 6px;
            letter-spacing: -2px;
            background: linear-gradient(90deg, #2563eb 60%, #1e293b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
            animation: popIn 0.7s 0.2s cubic-bezier(.4,0,.2,1) backwards;
        }
        @keyframes popIn {
            from { opacity: 0; transform: scale(0.7);}
            to { opacity: 1; transform: scale(1);}
        }
        .error-title {
            font-size: 2.1rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 14px;
            letter-spacing: -1px;
            position: relative;
            z-index: 2;
        }
        .error-highlight {
            display: inline-block;
            background: linear-gradient(90deg, #fbbf24 0%, #f87171 100%);
            color: #fff;
            font-weight: 800;
            padding: 2px 12px 2px 12px;
            border-radius: 8px;
            font-size: 1.1em;
            margin-bottom: 8px;
            margin-top: 2px;
            box-shadow: 0 2px 8px rgba(251,191,36,0.08);
            animation: highlightFade 1.2s cubic-bezier(.4,0,.2,1);
        }
        @keyframes highlightFade {
            from { opacity: 0; transform: scale(0.9);}
            to { opacity: 1; transform: scale(1);}
        }
        .error-message {
            color: #64748b;
            font-size: 1.13rem;
            margin-bottom: 32px;
            line-height: 1.6;
            animation: fadeIn 1.1s 0.2s cubic-bezier(.4,0,.2,1) backwards;
        }
        .back-btn {
            display: inline-block;
            background: linear-gradient(90deg, #2563eb 0%, #1e40af 100%);
            color: #fff;
            padding: 13px 36px;
            border-radius: 10px;
            font-weight: 700;
            text-decoration: none;
            font-size: 1.08rem;
            box-shadow: 0 2px 12px rgba(37,99,235,0.10);
            transition: background 0.18s, box-shadow 0.18s, transform 0.13s;
            border: none;
            outline: none;
            letter-spacing: 0.5px;
            cursor: pointer;
            animation: fadeIn 1.2s 0.3s cubic-bezier(.4,0,.2,1) backwards;
        }
        .back-btn:hover, .back-btn:focus {
            background: linear-gradient(90deg, #1e40af 0%, #2563eb 100%);
            box-shadow: 0 6px 24px rgba(37,99,235,0.18);
            transform: translateY(-2px) scale(1.03);
        }
        .glow {
            position: absolute;
            top: -60px;
            left: 50%;
            transform: translateX(-50%);
            width: 180px;
            height: 80px;
            background: radial-gradient(circle, #2563eb55 0%, #fff0 80%);
            filter: blur(18px);
            z-index: 0;
            pointer-events: none;
            animation: glowPulse 2.5s infinite alternate cubic-bezier(.4,0,.2,1);
        }
        @keyframes glowPulse {
            from { opacity: 0.7; }
            to { opacity: 1; }
        }
        @media (max-width: 500px) {
            .error-container {
                padding: 28px 6vw 22px 6vw;
                max-width: 98vw;
            }
            .error-code {
                font-size: 2.5rem;
            }
            .error-title {
                font-size: 1.1rem;
            }
            .icon-lock {
                width: 44px;
                height: 44px;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="glow"></div>
        <svg class="icon-lock" fill="none" viewBox="0 0 48 48">
            <rect x="10" y="20" width="28" height="18" rx="5" fill="#2563eb" fill-opacity="0.12"/>
            <rect x="10" y="20" width="28" height="18" rx="5" stroke="#2563eb" stroke-width="2"/>
            <path d="M16 20v-5a8 8 0 1 1 16 0v5" stroke="#2563eb" stroke-width="2" stroke-linecap="round"/>
            <circle cx="24" cy="29" r="2.5" fill="#2563eb"/>
            <rect x="22.5" y="31.5" width="3" height="5" rx="1.5" fill="#2563eb"/>
        </svg>
        <div class="error-code">403</div>
        <div class="error-title">Access Denied</div>
        <div class="error-highlight">You are <span style="font-weight:900;">not authorized</span> to view this page</div>
        <div class="error-message">
            <strong style="color:#ef4444; font-weight:700;">Reason:</strong> <span style="color:#1e293b; font-weight:600;">Insufficient permissions</span>.<br>
            You do not have the required access rights to view this resource.<br>
            <span style="color:#64748b;">If you believe this is a mistake, please contact your system administrator for assistance.</span>
        </div>
        <a href="{{ url()->previous() ?? url('/') }}" class="back-btn">Go Back</a>
    </div>
</body>
</html>