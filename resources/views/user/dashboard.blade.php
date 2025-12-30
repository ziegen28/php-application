<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | {{ config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 280px;
            --brand-primary: #2563eb;
            --brand-dark: #0f172a;
            --bg-light: #f8fafc;
            --sidebar-bg: #ffffff;
            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: var(--brand-dark);
            margin: 0;
        }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--sidebar-bg);
            border-right: 1px solid #e2e8f0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: var(--transition);
        }

        .brand-section {
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        .brand-logo {
            height: 38px;
            width: auto;
            border-radius: 8px;
        }

        .brand-text {
            font-weight: 800;
            font-size: 1.25rem;
            color: var(--brand-dark);
            letter-spacing: -0.5px;
        }

        .nav-container {
            padding: 20px 16px;
            flex-grow: 1;
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border-radius: 12px;
            color: #64748b;
            text-decoration: none;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: var(--transition);
        }

        .nav-link-custom i {
            font-size: 1.1rem;
            width: 24px;
        }

        .nav-link-custom:hover, .nav-link-custom.active {
            background: #eff6ff;
            color: var(--brand-primary);
        }

        .nav-link-custom.active {
            background: var(--brand-primary);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2.5rem 3.5rem;
            min-height: 100vh;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
        }

        .page-title {
            font-weight: 800;
            font-size: 1.75rem;
            letter-spacing: -0.02em;
            color: var(--brand-dark);
        }

        .user-profile-btn {
            background: white;
            padding: 6px 16px 6px 6px;
            border-radius: 50px;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .avatar-img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* CARDS */
        .stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 1.75rem;
            height: 100%;
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px -10px rgba(0,0,0,0.08);
        }

        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 1.25rem;
        }

        .icon-blue { background: #eff6ff; color: var(--brand-primary); }
        .icon-orange { background: #fff7ed; color: #f97316; }

        .action-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            padding: 2.5rem;
            margin-top: 2rem;
            position: relative;
            overflow: hidden;
        }

        .btn-action {
            padding: 12px 28px;
            border-radius: 12px;
            font-weight: 700;
            transition: var(--transition);
        }

        .btn-logout {
            color: #ef4444;
            background: #fef2f2;
            border: none;
            width: 100%;
            justify-content: center;
            font-weight: 700;
        }

        .btn-logout:hover { background: #fee2e2; color: #dc2626; }

        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; padding: 1.5rem; }
            .top-bar { flex-direction: column; align-items: flex-start; gap: 1rem; }
        }
    </style>
</head>

<body>

<aside class="sidebar">
    <div class="brand-section">
        <img src="{{ asset('images/sq1logo.jpg') }}" alt="Logo" class="brand-logo">
        <span class="brand-text">SQ1 PORTAL</span>
    </div>

    <div class="nav-container">
        <a href="#" class="nav-link-custom active">
            <i class="fa-solid fa-grid-2 me-3"></i> Overview
        </a>
        <a href="{{ route('resume.upload') }}" class="nav-link-custom">
            <i class="fa-solid fa-file-export me-3"></i> Resume Upload
        </a>
        <a href="#" class="nav-link-custom">
            <i class="fa-solid fa-clipboard-check me-3"></i> My Assessment
        </a>
    </div>

    <div class="p-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-logout d-flex align-items-center p-3">
                <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Logout
            </button>
        </form>
    </div>
</aside>

<main class="main-content">
    
    <header class="top-bar">
        <div>
            <h1 class="page-title">Candidate Overview</h1>
            <p class="text-muted fw-medium">Track your application and assessment progress</p>
        </div>
        
        <div class="user-profile-btn">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=2563eb&color=fff" 
                 class="avatar-img" alt="User">
            <div class="d-none d-md-block">
                <div class="fw-bold text-dark small">{{ $user->name }}</div>
                <div style="font-size: 11px; color: #94a3b8;">{{ $user->email }}</div>
            </div>
        </div>
    </header>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4 rounded-4 p-3 fw-bold">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        
        <div class="col-md-6">
            <div class="stat-card">
                <div class="icon-box icon-blue">
                    <i class="fa-solid fa-file-pdf"></i>
                </div>
                <h6 class="text-muted fw-bold small text-uppercase tracking-wider">Document Status</h6>
                <div class="mt-3">
                    @if($resume)
                        <div class="d-flex align-items-center text-success fw-bold fs-5">
                            <i class="fa-solid fa-circle-check me-2"></i> Profile Verified
                        </div>
                        <p class="text-muted small mt-1">Your resume has been processed.</p>
                    @else
                        <div class="d-flex align-items-center text-secondary fw-bold fs-5">
                            <i class="fa-solid fa-circle-dot me-2"></i> Pending Upload
                        </div>
                        <p class="text-muted small mt-1">Upload CV to unlock assessment.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="stat-card">
                <div class="icon-box icon-orange">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <h6 class="text-muted fw-bold small text-uppercase tracking-wider">Evaluation Score</h6>
                <div class="mt-3">
                    @if($assessment && $assessment->status === 'completed')
                        <h2 class="fw-extrabold mb-0 text-dark">{{ $assessment->percentage }}%</h2>
                        <span class="badge bg-success-subtle text-success mt-2">Passed Assessment</span>
                    @else
                        <h4 class="text-muted fw-bold mb-0">N/A</h4>
                        <p class="text-muted small mt-1">Complete the test to see results.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="action-card shadow-sm">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h4 class="fw-bold text-dark mb-2">Ready to take the next step?</h4>
                        
                        @if(!$assessment)
                            <p class="text-muted mb-4 mb-lg-0">Your profile requires a skill validation assessment to move forward with the application.</p>
                        @elseif($assessment->status === 'active')
                            <p class="text-muted mb-4 mb-lg-0">You have a session in progress. Please complete it before the link expires.</p>
                        @elseif($assessment->status === 'completed')
                            <p class="text-muted mb-4 mb-lg-0">Congratulations! You have successfully completed the assessment suite.</p>
                        @endif
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        @if(!$assessment)
                            @if($resume)
                                <a href="{{ route('assessment.instructions') }}" class="btn btn-primary btn-action shadow-sm">Start Assessment <i class="fa-solid fa-arrow-right ms-2"></i></a>
                            @else
                                <a href="{{ route('resume.upload') }}" class="btn btn-dark btn-action shadow-sm">Upload Resume First</a>
                            @endif
                        @elseif($assessment->status === 'active')
                            <a href="{{ route('assessment.take', $assessment->id) }}" class="btn btn-warning btn-action shadow-sm">Resume Session</a>
                        @elseif($assessment->status === 'completed')
                            <a href="{{ route('assessment.results', $assessment->id) }}" class="btn btn-success btn-action shadow-sm">View Report</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

</body>
</html>