<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analysis Result | {{ config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-primary: #2563eb;
            --brand-dark: #0f172a;
            --brand-success: #10b981;
            --brand-danger: #ef4444;
            --bg-body: #f8fafc;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--brand-dark);
            -webkit-font-smoothing: antialiased;
        }

        /* Narrower, more focused container */
        .content-wrapper {
            max-width: 700px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .header-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid #e2e8f0;
            padding: 0.75rem 0;
        }

        .result-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 2.5rem 2rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        /* Compact Circular Progress */
        .stat-circle {
            position: relative;
            width: 140px; /* Reduced from 180px */
            height: 140px;
            margin: 0 auto 1.25rem;
        }

        .stat-circle svg { transform: rotate(-90deg); }
        .stat-circle circle { fill: none; stroke-width: 10; stroke-linecap: round; }
        .circle-bg { stroke: #f1f5f9; }
        .circle-prog {
            stroke: var(--brand-primary);
            stroke-dasharray: 377; /* 2 * PI * 60 (radius) */
            stroke-dashoffset: calc(377 - (377 * {{ $bestSkill['percentage'] }}) / 100);
            transition: stroke-dashoffset 1s ease-out;
        }

        .stat-value {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--brand-primary);
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.4rem 1.2rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            margin-bottom: 2rem;
        }

        .pill-eligible { background: #dcfce7; color: #166534; }
        .pill-not-eligible { background: #fee2e2; color: #991b1b; }

        /* Compact Skill List */
        .skill-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.85rem 1rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .skill-name { font-weight: 600; font-size: 0.95rem; }

        .btn-action {
            border-radius: 10px;
            font-weight: 700;
            padding: 0.75rem 1.5rem;
            transition: all 0.2s;
        }

        .btn-details {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 4px 12px;
            border-radius: 6px;
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
            <button type="submit" class="btn btn-sm btn-outline-secondary fw-bold" style="font-size: 0.7rem;">LOGOUT</button>
        </form>
    </div>
</nav>

<div class="content-wrapper">
    <div class="result-card">
        <div class="text-center">
            <h5 class="fw-bold text-muted small mb-4">ANALYSIS RESULTS</h5>
            
            <div class="stat-circle">
                <svg width="140" height="140">
                    <circle class="circle-bg" cx="70" cy="70" r="60"></circle>
                    <circle class="circle-prog" cx="70" cy="70" r="60"></circle>
                </svg>
                <div class="stat-value">{{ $bestSkill['percentage'] }}%</div>
            </div>

            <h3 class="fw-extrabold mb-1">{{ ucfirst($bestSkill['skill_name']) }}</h3>
            <p class="text-muted small mb-3">Best Matching Career Path</p>

            <div class="status-pill {{ $isEligible ? 'pill-eligible' : 'pill-not-eligible' }}">
                {{ $isEligible ? '✓ ELIGIBLE FOR ASSESSMENT' : '✕ CRITERIA NOT MET' }}
            </div>
        </div>

        <div class="mt-4 pt-4 border-top">
            <p class="text-uppercase fw-800 text-muted mb-3" style="font-size: 0.7rem; letter-spacing: 0.1em;">Skill Breakdown</p>
            
            <div class="skills-container">
                @foreach ($results as $index => $row)
                <div class="skill-item">
                    <span class="skill-name">{{ ucfirst($row['skill_name']) }}</span>
                    @if(!empty($row['matched_keywords']))
                        <button class="btn btn-outline-primary btn-details fw-bold" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modal{{ $index }}">Details</button>
                    @else
                        <span class="text-muted small fst-italic" style="font-size: 0.75rem;">No keywords</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <div class="mt-4">
            @if ($isEligible)
                <a href="{{ route('assessment.instructions') }}" class="btn btn-primary btn-action w-100 shadow-sm">
                    Start Assessment →
                </a>
            @else
                <div class="d-flex gap-2">
                    <a href="{{ route('resume.upload') }}" class="btn btn-primary btn-action flex-grow-1">Re-upload</a>
                    <a href="{{ route('user.dashboard') }}" class="btn btn-light btn-action border">Dashboard</a>
                </div>
            @endif
        </div>
    </div>
    
    <p class="text-center text-muted mt-4" style="font-size: 0.7rem;">© {{ date('Y') }} {{ config('app.name') }} Intelligence System</p>
</div>

{{-- Modals --}}
@foreach ($results as $index => $row)
<div class="modal fade" id="modal{{ $index }}" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="fw-bold m-0">{{ ucfirst($row['skill_name']) }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="font-size: 0.6rem;"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-wrap gap-2">
                    @forelse ($row['matched_keywords'] as $kw)
                        <span class="badge bg-light text-primary border px-2 py-1" style="font-size: 0.7rem;">{{ $kw }}</span>
                    @empty
                        <span class="text-muted small">None</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>