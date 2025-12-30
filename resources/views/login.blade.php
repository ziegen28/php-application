<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | {{ config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-primary: #2563eb;
            --brand-dark: #0f172a;
            --brand-bg: radial-gradient(circle at top right, #1e293b, #0f172a);
            --text-muted: #64748b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--brand-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            color: #ffffff;
            overflow: hidden;
        }

        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 440px;
            padding: 3.5rem 2.5rem;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            text-align: center;
            animation: fadeInScale 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.95) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .login-title {
            font-weight: 800;
            color: var(--brand-dark);
            font-size: 1.6rem;
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-bottom: 2.5rem;
            line-height: 1.5;
        }

        /* Fixed Button Styling for "Connecting" State */
        .btn-microsoft {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: var(--brand-dark);
            font-weight: 700;
            min-height: 54px; /* Essential: keeps button height stable */
            border-radius: 12px;
            transition: all 0.2s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            position: relative;
            cursor: pointer;
        }

        .btn-microsoft:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            color: var(--brand-dark);
        }

        .btn-microsoft.disabled {
            pointer-events: none;
            background: #f1f5f9;
            border-color: #e2e8f0;
            color: #94a3b8;
        }

        .spinner-border-sm {
            width: 1.1rem;
            height: 1.1rem;
            border-width: 0.2em;
        }

        .brand-logo {
            border-radius: 12px;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .footer-text {
            margin-top: 2.5rem;
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .security-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f0fdf4;
            color: #166534;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>

<div class="login-card">
    <div class="security-badge">
        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
        </svg>
        Secure Environment
    </div>

    <div class="mb-2">
        <img src="{{ asset('images/sq1logo.jpg') }}" alt="Logo" width="70" height="70" class="brand-logo">
    </div>

    <h2 class="login-title">Welcome Back</h2>
    <p class="login-subtitle">Sign in with your corporate Microsoft account to continue to your assessment.</p>

    @if(session('error'))
        <div class="alert alert-danger text-start py-2 px-3 small border-0 mb-4" style="border-radius: 10px;">
            {{ session('error') }}
        </div>
    @endif

    <a href="{{ route('login.microsoft') }}"
       id="microsoftLoginBtn"
       class="btn btn-microsoft w-100">
        <img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg" 
             alt="MS" width="20" height="20" id="msIcon">
        <span id="btnText">Continue with Microsoft</span>
        <div class="spinner-border spinner-border-sm d-none text-primary" role="status" id="btnSpinner"></div>
    </a>

    <div class="footer-text">
        <p class="mb-1">&copy; {{ date('Y') }} Assessment Platform</p>
        
    </div>
</div>

<script>
    document.getElementById('microsoftLoginBtn').addEventListener('click', function (e) {
        const btn = this;
        const text = document.getElementById('btnText');
        const spinner = document.getElementById('btnSpinner');
        const icon = document.getElementById('msIcon');

        // Prevent multiple clicks
        if (btn.classList.contains('disabled')) {
            e.preventDefault();
            return;
        }

        // Apply visual changes immediately
        btn.classList.add('disabled');
        icon.classList.add('d-none'); // Hide icon to focus on "Connecting"
        text.innerText = 'Connecting...';
        spinner.classList.remove('d-none');

        // Force a UI repaint so the user actually sees the change before the redirect
        void btn.offsetWidth;
    });
</script>

</body>
</html>