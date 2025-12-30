<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Assessment Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
        }

        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            height: 50px;
        }

        .score {
            font-size: 26px;
            font-weight: bold;
            color: #2563eb;
        }

        .section {
            margin-bottom: 15px;
        }

        .label {
            font-weight: bold;
        }

        .metric-box {
            border: 1px solid #ddd;
            padding: 12px;
            margin-top: 10px;
            border-radius: 6px;
        }

        .metric {
            font-size: 15px;
            font-weight: bold;
        }

        .danger {
            color: #dc2626;
        }

        .question {
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }

        .correct {
            color: #16a34a;
            font-weight: bold;
        }

        .wrong {
            color: #dc2626;
            font-weight: bold;
        }

        .answer-line {
            margin-top: 4px;
        }
    </style>
</head>
<body>

{{-- HEADER --}}
<div class="header">
    <div>
        <img src="{{ public_path('images/sq1logo.jpg') }}" class="logo">
        <p><strong>Assessment Report</strong></p>
    </div>
    <div>
        <p>Date: {{ now()->format('d M Y') }}</p>
        <div class="score">{{ $assessment->percentage }}%</div>
    </div>
</div>

{{-- CANDIDATE INFO --}}
<div class="section">
    <p><span class="label">Candidate:</span> {{ $candidate->name }}</p>
    <p><span class="label">Email:</span> {{ $candidate->email }}</p>
</div>

<hr>

{{-- SUMMARY --}}
<div class="section metric-box">
    <p class="metric">
        Correct Answers:
        {{ $assessment->correct_answers }}
        / {{ count($assessment->questions_json) }}
    </p>

    <p class="metric {{ $assessment->violations > 0 ? 'danger' : '' }}">
        Security Violations:
        {{ $assessment->violations ?? 0 }}
    </p>
</div>

<hr>

{{-- QUESTIONS --}}
@php
    $questions = \App\Models\Question::whereIn('id', $assessment->questions_json)->get();
    $answers = $assessment->answers_json ?? [];
@endphp

@foreach($questions as $index => $q)
    @php
        $userAnswer = $answers[$q->id] ?? null;
        $isCorrect = $userAnswer === $q->correct_option;
    @endphp

    <div class="question">
        <strong>Q{{ $index + 1 }}.</strong> {{ $q->question }}

        <div class="answer-line">
            Your Answer:
            <strong>{{ $userAnswer ? strtoupper($userAnswer) : 'N/A' }}</strong>
        </div>

        <div class="answer-line">
            Correct Answer:
            <strong>{{ strtoupper($q->correct_option) }}</strong>
        </div>

        <div class="answer-line {{ $isCorrect ? 'correct' : 'wrong' }}">
            {{ $isCorrect ? '✔ Correct' : '✘ Wrong' }}
        </div>
    </div>
@endforeach

</body>
</html>
