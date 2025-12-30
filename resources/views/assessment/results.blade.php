<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Assessment Report | {{ config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ✅ UI UNCHANGED */
        :root {
            --primary-color: #2563eb;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --bg-gradient: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
            padding: 50px 0;
            color: #1e293b;
        }

        .result-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }

        .score-circle {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 20px auto;
            color: #fff;
        }

        .pass { background: linear-gradient(135deg, #059669, #10b981); }
        .fail { background: linear-gradient(135deg, #dc2626, #ef4444); }

        @media print {
            body { background: white; padding: 0; }
            .no-print { display: none !important; }
            .result-card { box-shadow: none; border: none; padding: 0; }
        }
    </style>
</head>

<body>

@php
    /* ===============================
       SAFE DATA PREPARATION
    =============================== */
    $questionsJson = is_array($assessment->questions_json)
        ? $assessment->questions_json
        : json_decode($assessment->questions_json ?? '[]', true);

    $totalQuestions = count($questionsJson);

    $candidate = $assessment->user;

    /* ✅ FIX: Correct dashboard routing */
    $backRoute = auth()->user()->role === 'admin'
        ? route('admin.dashboard')
        : route('user.dashboard');

    /* ✅ LOCK UI (assessment completed) */
    $locked = $assessment->status === 'completed';
@endphp

<div class="container">
<div class="row justify-content-center">
<div class="col-lg-10 col-xl-8">

<div class="result-card">

    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="fw-bold">Assessment Report</h2>
            <p class="text-muted">Generated on {{ now()->format('M d, Y') }}</p>
        </div>
        <div class="text-end">
            <img src="{{ asset('images/sq1logo.jpg') }}" width="80" alt="Logo">
            <small class="text-muted d-block">
                ID: #{{ str_pad($assessment->id, 6, '0', STR_PAD_LEFT) }}
            </small>
        </div>
    </div>

    <hr>

    <div class="row align-items-center my-4">
        <div class="col-md-6">
            <p class="text-muted mb-1">Candidate Name</p>
            <h4 class="fw-bold">{{ $candidate->name ?? 'Candidate' }}</h4>

            <p class="text-muted mb-1 mt-3">Result</p>
            <span class="fw-bold {{ $assessment->percentage >= 40 ? 'text-success' : 'text-danger' }}">
                {{ $assessment->percentage >= 40 ? 'PASSED' : 'FAILED' }}
            </span>

            <p class="text-muted mt-3">
                Correct: {{ $assessment->correct_answers }} / {{ $totalQuestions }}
            </p>
        </div>

        <div class="col-md-6 text-center">
            <div class="score-circle {{ $assessment->percentage >= 40 ? 'pass' : 'fail' }}">
                <small>Score</small>
                <div class="fs-2 fw-bold">{{ $assessment->percentage }}%</div>
            </div>
        </div>
    </div>

    <h5 class="fw-bold mt-4">Question Performance</h5>

    @php
        $answers = $assessment->answers_json ?? [];
        $questions = \App\Models\Question::whereIn('id', $questionsJson)->get();
    @endphp

    @foreach($questions as $index => $q)
        @php
            $userAnswer = $answers[$q->id] ?? null;
            $isCorrect = $userAnswer && strtolower($userAnswer) === strtolower($q->correct_option);
        @endphp

        <div class="border rounded p-3 mb-3 {{ $isCorrect ? 'border-success' : 'border-danger' }}">
            <strong>Q{{ $index + 1 }}:</strong> {{ $q->question }} <br>
            <small>
                Your Answer: {{ $userAnswer ? strtoupper($userAnswer) : 'N/A' }} |
                Correct: {{ strtoupper($q->correct_option) }}
            </small>
        </div>
    @endforeach

    {{-- ✅ ACTIONS LOCKED --}}
    <div class="d-flex justify-content-between mt-4 no-print">
        <a href="{{ $backRoute }}" class="btn btn-outline-secondary">
            Return to Dashboard
        </a>

        <button onclick="window.print()" class="btn btn-primary">
            Print / Save PDF
        </button>
    </div>

</div>

<p class="text-center text-muted mt-4 small no-print">
    &copy; {{ date('Y') }} {{ config('app.name') }}
</p>

</div>
</div>
</div>

</body>
</html>
