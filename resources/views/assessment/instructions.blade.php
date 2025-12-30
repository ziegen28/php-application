<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Instructions | {{ config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-primary: #2563eb;
            --brand-dark: #0f172a;
            --brand-danger: #ef4444;
            --bg-body: #f8fafc;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--brand-dark);
            -webkit-font-smoothing: antialiased;
        }

        /* Narrow, focused container consistent with previous page */
        .content-wrapper {
            max-width: 700px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .header-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--border-color);
            padding: 0.75rem 0;
        }

        .instruction-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 2.5rem 2rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        .section-number {
            font-size: 0.7rem;
            font-weight: 800;
            background: #eff6ff;
            color: var(--brand-primary);
            padding: 2px 8px;
            border-radius: 6px;
            letter-spacing: 0.05em;
        }

        .section-title {
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
        }

        .rules-list {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
        }

        .rules-list li {
            position: relative;
            padding-left: 1.5rem;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
            color: #334155;
        }

        .rules-list li::before {
            content: "→";
            position: absolute;
            left: 0;
            color: var(--brand-primary);
            font-weight: bold;
        }

        .violation-box {
            background: #fff1f2;
            border-radius: 12px;
            padding: 1.25rem;
            border: 1px solid #fee2e2;
            margin: 2rem 0;
        }

        .alert-custom {
            background: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1rem;
            font-size: 0.85rem;
            color: #475569;
        }

        .form-check-custom {
            background: #f1f5f9;
            border-radius: 12px;
            padding: 1rem 1rem 1rem 2.5rem;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .form-check-custom:hover {
            border-color: var(--brand-primary);
        }

        .btn-start {
            background: var(--brand-primary);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 1rem;
            font-weight: 700;
            width: 100%;
            transition: transform 0.2s, background 0.2s;
        }

        .btn-start:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
            color: white;
        }

        .btn-logout {
            font-size: 0.7rem;
            font-weight: 800;
            color: var(--brand-danger);
            text-decoration: none;
            padding: 5px 12px;
            border: 1px solid #fecaca;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: var(--brand-danger);
            color: white;
        }
    </style>
</head>
<body>

<nav class="header-nav mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand d-flex align-items-center gap-2" href="#">
            <img src="{{ asset('images/sq1logo.jpg') }}" height="30" style="border-radius: 4px;">
            <span class="fw-bold text-dark small">SQ1</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">LOGOUT</button>
        </form>
    </div>
</nav>

<div class="content-wrapper">
    <div class="instruction-card">
        <div class="text-center mb-4">
            <h5 class="fw-bold text-muted small mb-2">PRE-ASSESSMENT</h5>
            <h3 class="fw-extrabold">Final Guidelines</h3>
            <p class="text-muted small">Please read carefully before starting your session</p>
        </div>

        <div class="section-header">
            <span class="section-number">01</span>
            <span class="section-title">General Rules</span>
        </div>
        <ul class="rules-list">
            <li>Strictly <strong>time-bound</strong> session.</li>
            <li>Responses are <strong>auto-saved</strong> in real-time.</li>
            <li>Questions cannot be skipped or changed.</li>
        </ul>

        <div class="section-header">
            <span class="section-number">02</span>
            <span class="section-title">Environment</span>
        </div>
        <ul class="rules-list">
            <li>Stable internet connection is mandatory.</li>
            <li><strong>Desktop/Laptop only</strong>. Mobile access is blocked.</li>
            <li>Do not refresh or open new browser tabs.</li>
        </ul>

        <div class="violation-box">
            <h6 class="fw-bold text-danger mb-1" style="font-size: 0.9rem;">Security Policy</h6>
            <p class="mb-0 small text-dark opacity-75">
                Copy/Paste is disabled. Reaching <strong>3 window-switch violations</strong> will trigger an immediate auto-submission.
            </p>
        </div>

        <div class="alert-custom mb-4">
            <div class="d-flex gap-3">
                <span style="font-size: 1.2rem;">⚠️</span>
                <div>
                    <strong>Technical Readiness:</strong> Ensure a stable power source. Local hardware failures do not qualify for a re-take.
                </div>
            </div>
        </div>

        <form action="{{ route('assessment.start') }}" method="GET">
            <div class="form-check form-check-custom mb-4">
                <input class="form-check-input" type="checkbox" id="agree" required>
                <label class="form-check-label small fw-semibold" for="agree">
                    I have read and I agree to the monitoring policies.
                </label>
            </div>

            <button type="submit" class="btn-start shadow-sm">
                Start Assessment Now →
            </button>
        </form>
    </div>

    <p class="text-center text-muted mt-4" style="font-size: 0.7rem;">
         Secure Assessment Protocol
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>