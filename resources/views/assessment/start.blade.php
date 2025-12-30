<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Portal | {{ config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-primary: #2563eb;
            --brand-dark: #0f172a;
            --brand-success: #10b981;
            --brand-danger: #ef4444;
            --bg-body: #f8fafc;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--brand-dark);
            user-select: none;
            -webkit-font-smoothing: antialiased;
        }

        /* Fixed Navigation */
        .header-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            padding: 0.75rem 0;
            position: sticky;
            top: 0;
            z-index: 1050;
        }

        .progress-bar-container {
            height: 4px;
            background: #e2e8f0;
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        .progress-bar-fill {
            height: 100%;
            background: var(--brand-primary);
            width: {{ ($index + 1) / $total * 100 }}%;
            transition: width 0.3s ease;
        }

        /* Layout */
        .assessment-container {
            max-width: 1200px;
            margin: 2.5rem auto;
            padding: 0 1.5rem;
        }

        .content-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 3rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        /* Question UI */
        .question-meta {
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--brand-primary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .question-text {
            font-size: 1.35rem;
            font-weight: 700;
            line-height: 1.5;
            margin-bottom: 2.5rem;
            color: #1e293b;
        }

        /* Options */
        .option-card {
            display: flex;
            align-items: center;
            padding: 1.25rem 1.5rem;
            border: 2px solid #f1f5f9;
            border-radius: 12px;
            cursor: pointer;
            margin-bottom: 1rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .option-card:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
        }

        .option-card.active {
            background: #eff6ff;
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .option-card input {
            width: 20px;
            height: 20px;
            margin-right: 16px;
            accent-color: var(--brand-primary);
        }

        /* Sidebar Elements */
        .sidebar-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            position: sticky;
            top: 110px;
        }

        .q-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
        }

        .q-box {
            aspect-ratio: 1/1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            font-size: 0.85rem;
            font-weight: 700;
            text-decoration: none;
            color: #64748b;
            transition: all 0.2s;
        }

        .q-box.active { background: var(--brand-dark); color: #fff; border-color: var(--brand-dark); }
        .q-box.answered { background: #dcfce7; color: #166534; border-color: #bbf7d0; }

        /* Timer & Alerts */
        #timer {
            background: var(--brand-dark);
            color: #fff;
            padding: 8px 16px;
            border-radius: 10px;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: 1px;
        }

        .instruction-mini-box {
            background: #fffbeb;
            border: 1px solid #fef3c7;
            padding: 1rem;
            border-radius: 12px;
            font-size: 0.8rem;
            color: #92400e;
            margin-top: 1.5rem;
        }

        .violation-toast {
            position: fixed;
            top: 30px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--brand-danger);
            color: white;
            padding: 14px 28px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.9rem;
            box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.4);
            display: none;
            z-index: 2000;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { top: -50px; opacity: 0; }
            to { top: 30px; opacity: 1; }
        }

        .btn-nav {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 700;
            transition: all 0.2s;
        }
    </style>
</head>

<body>

<nav class="header-nav">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ asset('images/sq1logo.jpg') }}" height="32" class="rounded">
            <span class="fw-extrabold small tracking-tight">SECURE ASSESSMENT</span>
        </div>

        <div class="d-flex align-items-center gap-4">
            <div id="timer">00:00</div>
            <div class="text-end d-none d-lg-block border-start ps-4">
                <div class="fw-bold small" style="line-height: 1;">{{ auth()->user()->name }}</div>
                <span class="text-muted" style="font-size: 10px;">{{ auth()->user()->email }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-link text-danger fw-bold text-decoration-none small p-0">LOGOUT</button>
            </form>
        </div>
    </div>
    <div class="progress-bar-container">
        <div class="progress-bar-fill"></div>
    </div>
</nav>

<div class="assessment-container">
    <div class="row g-4">
        {{-- Question Sidebar Navigation --}}
        <div class="col-lg-3 order-2 order-lg-1">
            <div class="sidebar-card">
                <h6 class="fw-bold mb-3 small text-muted text-uppercase">Question Map</h6>
                <div class="q-grid mb-4">
                    @foreach($assessment->questions_json as $i => $qid)
                        <a href="{{ route('assessment.take', [$assessment->id,'q'=>$i]) }}"
                           class="q-box {{ $i==$index?'active':'' }} {{ isset($assessment->answers_json[$qid])?'answered':'' }}"
                           onclick="markNavigation()">
                            {{ $i+1 }}
                        </a>
                    @endforeach
                </div>
                
                <div class="pt-3 border-top mt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small text-muted">Answered</span>
                        <span class="small fw-bold text-success">{{ count($assessment->answers_json ?? []) }} / {{ $total }}</span>
                    </div>
                </div>

                <div class="instruction-mini-box">
                    <div class="fw-bold mb-1">⚠️ REMINDER</div>
                    Do not switch tabs, refresh the page, or use keyboard shortcuts. All activity is logged.
                </div>
            </div>
        </div>

        {{-- Primary Content Area --}}
        <div class="col-lg-9 order-1 order-lg-2">
            <div class="content-card">
                <div class="question-meta">
                    <span class="badge bg-primary">PHASE 01</span>
                    <span>Item {{ $index + 1 }} of {{ $total }}</span>
                </div>

                <div class="question-text">
                    {{ $question->question }}
                </div>

                <div class="options-wrapper">
                    @foreach(['a','b','c','d'] as $opt)
                        @php $checked = ($assessment->answers_json[$question->id] ?? '') === $opt; @endphp
                        <label class="option-card {{ $checked ? 'active' : '' }}" id="label-{{ $opt }}">
                            <input type="radio" name="answer" value="{{ $opt }}"
                                   {{ $checked ? 'checked' : '' }}
                                   onclick="saveAnswer({{ $question->id }}, '{{ $opt }}')">
                            <span class="fw-semibold text-secondary me-2">{{ strtoupper($opt) }}.</span>
                            <span class="fw-medium">{{ $question->{'option_'.$opt} }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="d-flex justify-content-between mt-5 pt-4 border-top">
                    @if($index > 0)
                        <a href="{{ route('assessment.take', [$assessment->id,'q'=>$index-1]) }}"
                           class="btn btn-light btn-nav border"
                           onclick="markNavigation()">← Previous</a>
                    @else
                        <div></div>
                    @endif

                    @if($index < $total-1)
                        <a href="{{ route('assessment.take', [$assessment->id,'q'=>$index+1]) }}"
                           class="btn btn-primary btn-nav px-5 shadow-sm"
                           onclick="markNavigation()">Save & Continue →</a>
                    @else
                        <button class="btn btn-danger btn-nav px-5 shadow-sm" onclick="confirmSubmit()">Finish Assessment</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div id="violationToast" class="violation-toast"></div>

<script>
    let remaining = {{ $remainingSeconds }};
    let submitted = false;
    let navigating = false;
    let violations = 0;
    const MAX_VIOLATIONS = 3;

    function saveAnswer(qid, ans) {
        document.querySelectorAll('.option-card').forEach(e => e.classList.remove('active'));
        document.getElementById('label-'+ans).classList.add('active');

        fetch("{{ route('assessment.save', $assessment->id) }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ question_id: qid, answer: ans })
        });
    }

    function markNavigation() {
        navigating = true;
    }

    function confirmSubmit() {
        if (confirm("Are you sure you want to finish the assessment?")) {
            safeSubmit();
        }
    }

    function safeSubmit() {
        if (submitted) return;
        submitted = true;

        fetch("{{ route('assessment.submit', $assessment->id) }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(() => {
            window.location.href = "{{ route('assessment.results', $assessment->id) }}";
        });
    }

    /* ================= TIMER ================= */
    setInterval(() => {
        if (remaining <= 0) safeSubmit();

        let m = Math.floor(remaining / 60);
        let s = remaining % 60;
        document.getElementById('timer').innerText =
            `${m}:${s.toString().padStart(2, '0')}`;

        remaining--;
    }, 1000);

    /* ================= VIOLATION HANDLER ================= */
    function violation(reason) {
        if (submitted) return;

        violations++;

        // 🔥 SEND TO BACKEND (THIS WAS MISSING)
        fetch("{{ route('assessment.violation', $assessment->id) }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        });

        const box = document.getElementById('violationToast');
        box.innerText =
            `⚠️ SECURITY VIOLATION: ${reason} (${violations}/${MAX_VIOLATIONS})`;
        box.style.display = 'block';

        if (violations >= MAX_VIOLATIONS) {
            safeSubmit();
        }

        setTimeout(() => box.style.display = 'none', 4000);
    }

    /* ================= ANTI-CHEAT EVENTS ================= */
    window.addEventListener('blur', () => {
        if (!navigating) violation('Tab Switch Detected');
    });

    document.addEventListener('contextmenu', e => {
        e.preventDefault();
        violation('Right Click Blocked');
    });

    document.addEventListener('keydown', e => {
        if (e.ctrlKey || e.metaKey) violation('Shortcut Blocked');
        if (['F12', 'F5'].includes(e.key)) violation('System Key Blocked');
    });
</script>


</body>
</html>