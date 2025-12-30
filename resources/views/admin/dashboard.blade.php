@php
    $activeTab = session('tab', $tab ?? 'dashboard');
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | {{ config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-primary: #2563eb;
            --brand-secondary: #7c3aed;
            --brand-success: #10b981;
            --brand-danger: #ef4444;
            --bg-soft: #f8fafc;
        }

        body {
            background: var(--bg-soft);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
        }

        /* PREMIUM NAVBAR */
        .admin-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e2e8f0;
            padding: 0.8rem 2.5rem;
            position: sticky;
            top: 0;
            z-index: 1050;
        }

        .company-logo {
            height: 38px;
            width: auto;
            border-radius: 8px;
        }

        /* GLOSSY TABS */
        .nav-tabs {
            border: none;
            gap: 10px;
            background: #f1f5f9;
            padding: 6px;
            border-radius: 14px;
            display: inline-flex;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #64748b;
            padding: 10px 20px;
            font-weight: 700;
            border-radius: 10px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link.active {
            background: #ffffff;
            color: var(--brand-primary);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        /* ATTRACTIVE CARDS */
        .card {
            border: none;
            border-radius: 24px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04);
            transition: transform 0.3s ease;
        }

        .card:hover { transform: translateY(-5px); }

        .stat-card {
            padding: 1.5rem;
            background: #fff;
            position: relative;
            overflow: hidden;
            border-bottom: 4px solid var(--brand-primary);
        }

        .stat-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 800;
            color: #94a3b8;
            letter-spacing: 0.1em;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            margin-top: 5px;
            color: #0f172a;
        }

        /* TABLE STYLING */
        .table-card {
            background: #fff;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid #f1f5f9;
        }

        .table thead th {
            background: #f8fafc;
            padding: 1.2rem;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.05em;
        }

        .table tbody td {
            padding: 1.2rem;
            font-weight: 600;
            border-bottom: 1px solid #f8fafc;
        }

        /* STATUS BADGES */
        .badge-custom {
            padding: 6px 14px;
            border-radius: 100px;
            font-weight: 800;
            font-size: 0.65rem;
            text-transform: uppercase;
        }

        /* BUTTONS */
        .btn-action {
            border-radius: 12px;
            font-weight: 700;
            padding: 8px 16px;
            transition: all 0.2s;
        }

        .btn-primary-grad {
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            color: white;
            border: none;
        }

        .btn-primary-grad:hover { color: white; opacity: 0.9; transform: scale(1.02); }

        pre {
            background: #0f172a;
            color: #38bdf8;
            padding: 1rem;
            border-radius: 15px;
            font-size: 0.8rem;
        }
    </style>
</head>

<body>

{{-- NAVBAR --}}
<nav class="admin-nav d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-3">
        {{-- COMPANY LOGO SLOT --}}
        <img src="{{ asset('images/sq1logo.jpg') }}" alt="Logo" class="company-logo bg-light p-1">
        <div class="vr text-muted opacity-25 d-none d-md-block" style="height: 30px;"></div>
        <div class="fw-800 fs-5 text-dark">ADMIN<span class="text-primary">PORTAL</span></div>
    </div>

    <div class="d-flex align-items-center gap-3">
        {{-- USER INFO --}}
        <div class="text-end d-none d-lg-block me-2">
            <div class="fw-800 small text-dark">{{ auth()->user()->name }}</div>
            <div class="text-muted small" style="font-size: 10px;">{{ auth()->user()->email }}</div>
        </div>

        <a href="{{ route('admin.report.bulk.pdf') }}" class="btn btn-outline-success btn-action btn-sm">
            <i class="fa-solid fa-file-zipper me-1"></i> EXPORT ZIP
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-danger btn-action btn-sm">
                <i class="fa-solid fa-power-off"></i>
            </button>
        </form>
    </div>
</nav>

<div class="container-fluid px-5 mt-4">

    {{-- ERROR MESSAGES --}}
    @if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm rounded-4 fw-bold">
        <ul class="mb-0 small">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- TAB NAVIGATION --}}
    <div class="mb-4">
        <ul class="nav nav-tabs shadow-sm">
            <li class="nav-item">
                <button class="nav-link {{ $activeTab=='dashboard'?'active':'' }}" data-bs-toggle="tab" data-bs-target="#tab-dashboard">
                    <i class="fa-solid fa-chart-pie me-2"></i>Dashboard
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link {{ $activeTab=='invite'?'active':'' }}" data-bs-toggle="tab" data-bs-target="#tab-invite">
                    <i class="fa-solid fa-paper-plane me-2"></i>Invites
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link {{ $activeTab=='questions'?'active':'' }}" data-bs-toggle="tab" data-bs-target="#tab-questions">
                    <i class="fa-solid fa-database me-2"></i>Questions
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link {{ $activeTab=='users'?'active':'' }}" data-bs-toggle="tab" data-bs-target="#tab-users">
                    <i class="fa-solid fa-users-gear me-2"></i>Candidates
                </button>
            </li>
        </ul>
    </div>

    <div class="tab-content mt-2">

        {{-- DASHBOARD --}}
        <div class="tab-pane fade {{ $activeTab=='dashboard'?'show active':'' }}" id="tab-dashboard">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card stat-card border-0">
                        <div class="stat-label">Total Users</div>
                        <h3 class="stat-value text-primary">{{ $users->count() }}</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card border-0" style="border-bottom-color: var(--brand-success);">
                        <div class="stat-label">Resumes Uploaded</div>
                        <h3 class="stat-value text-success">{{ $users->where('resume_label','Uploaded')->count() }}</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card border-0" style="border-bottom-color: #f59e0b;">
                        <div class="stat-label">In Progress</div>
                        <h3 class="stat-value" style="color: #f59e0b;">{{ $users->where('status_label','In Progress')->count() }}</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card border-0" style="border-bottom-color: var(--brand-secondary);">
                        <div class="stat-label">Completed</div>
                        <h3 class="stat-value" style="color: var(--brand-secondary);">{{ $users->where('status_label','Completed')->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- INVITES --}}
        <div class="tab-pane fade {{ $activeTab=='invite'?'show active':'' }}" id="tab-invite">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card p-4 h-100 border-0 shadow-sm">
                        <h6 class="fw-800 mb-3"><i class="fa-solid fa-envelope me-2 text-primary"></i>Invite Single</h6>
                        <form method="POST" action="{{ route('admin.invite.single') }}">
                            @csrf
                            <input type="hidden" name="tab" value="invite">
                            <input type="email" name="email" class="form-control mb-3" placeholder="candidate@example.com" required>
                            <button class="btn btn-primary-grad w-100 py-2 btn-action">Send Invitation</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 h-100 border-0 shadow-sm">
                        <h6 class="fw-800 mb-3"><i class="fa-solid fa-file-csv me-2 text-success"></i>Bulk Invite</h6>
                        <form method="POST" action="{{ route('admin.invite.bulk') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="tab" value="invite">
                            <input type="file" name="csv_file" class="form-control mb-3" required>
                            <button class="btn btn-dark w-100 py-2 btn-action">Upload CSV</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 h-100 border-0 bg-white shadow-sm">
                        <h6 class="fw-800 mb-2">CSV Format</h6>
                        <pre class="mb-0">email
user1@test.com
user2@test.com</pre>
                    </div>
                </div>
            </div>
        </div>

        {{-- QUESTIONS --}}
        <div class="tab-pane fade {{ $activeTab=='questions'?'show active':'' }}" id="tab-questions">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card p-4 border-0 shadow-sm h-100">
                        <h6 class="fw-800 mb-3">Sync Question Bank</h6>
                        <form method="POST" action="{{ route('admin.upload.questions') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="tab" value="questions">
                            <input type="file" name="csv_file" class="form-control mb-3" required>
                            <button class="btn btn-success w-100 py-2 btn-action">Update Questions</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card p-4 border-0 bg-dark text-white h-100">
                        <h6 class="fw-800 text-info">CSV Schema</h6>
                        <pre class="bg-transparent border-0 p-0 text-info small">question,option_a,option_b,option_c,option_d,correct_option</pre>
                    </div>
                </div>
            </div>
        </div>

        {{-- USERS --}}
        <div class="tab-pane fade {{ $activeTab=='users'?'show active':'' }}" id="tab-users">
            <div class="table-card shadow-sm border-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Candidate</th>
                                <th>Email Address</th>
                                <th class="text-center">Resume</th>
                                <th class="text-center">Performance</th>
                                <th>Assessment</th>
                                <th class="text-center">Violations</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $u)
                            <tr>
                                <td class="fw-bold">{{ $u->name ?? 'Pending Registration' }}</td>
                                <td class="text-muted small">{{ $u->email }}</td>
                                <td class="text-center">
                                    @if($u->resume_label === 'Uploaded')
                                        <span class="badge-custom bg-success text-white">UPLOADED</span>
                                    @else
                                        <span class="badge-custom bg-light text-muted border">EMPTY</span>
                                    @endif
                                </td>
                                <td class="text-center fw-800 text-primary">
                                    {{ $u->percentage ?? '—' }}{{ $u->percentage ? '%' : '' }}
                                </td>
                                <td>
                                    @php
                                        $sc = match($u->status_label) {
                                            'Completed' => 'bg-success',
                                            'In Progress' => 'bg-warning text-dark',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge-custom {{ $sc }} text-white">{{ $u->status_label }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge-custom {{ $u->violations_count > 0 ? 'bg-danger text-white' : 'bg-light text-muted' }}">
                                        {{ $u->violations_count }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        @if($u->resume_label === 'Uploaded')
                                            <a href="{{ route('admin.resume.download',$u->id) }}" class="btn btn-sm btn-light p-2 shadow-sm border rounded-3" title="Resume">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </a>
                                        @endif
                                        @if($u->assessment_id)
                                            <a href="{{ route('admin.report.view',$u->id) }}" class="btn btn-sm btn-outline-primary px-3 fw-bold rounded-3">VIEW</a>
                                            <a href="{{ route('admin.report.pdf',$u->id) }}" class="btn btn-sm btn-primary px-3 fw-bold rounded-3">PDF</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>