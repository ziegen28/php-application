<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assessment Result</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            min-height: 100vh;
            padding: 40px 0;
        }

        .result-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 35px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.25);
            animation: fadeIn 0.6s ease-in-out;
        }

        .score-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
            font-size: 26px;
            font-weight: 700;
            color: #fff;
        }

        .pass {
            background: linear-gradient(135deg, #00b09b, #96c93d);
        }

        .fail {
            background: linear-gradient(135deg, #ff416c, #ff4b2b);
        }

        .question-box {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 12px;
        }

        .answered {
            border-left: 6px solid #22c55e;
        }

        .unanswered {
            border-left: 6px solid #ef4444;
        }

        .badge-answer {
            font-size: 0.8rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <div class="result-card">

                <!-- HEADER -->
                <div class="text-center mb-4">
                    <h2 class="fw-bold mb-1">Assessment Result</h2>
                    <p class="text-muted mb-0">
                        Candidate: <strong>{{ auth()->user()->name }}</strong>
                    </p>
                </div>

                <!-- SCORE -->
                <div class="score-circle {{ $assessment->percentage >= 40 ? 'pass' : 'fail' }} mb-3">
                    {{ $assessment->percentage }}%
                </div>

                <div class="text-center mb-4">
                    @if($assessment->percentage >= 40)
                        <h5 class="text-success fw-bold">PASSED 🎉</h5>
                    @else
                        <h5 class="text-danger fw-bold">FAILED ❌</h5>
                    @endif
                </div>

                <!-- STATS -->
                <div class="row text-center mb-4">
                    <div class="col">
                        <p class="mb-1 text-muted">Total Questions</p>
                        <h6>{{ count($assessment->questions_json) }}</h6>
                    </div>
                    <div class="col">
                        <p class="mb-1 text-muted">Correct Answers</p>
                        <h6>{{ $assessment->correct_answers }}</h6>
                    </div>
                    <div class="col">
                        <p class="mb-1 text-muted">Unanswered</p>
                        <h6>
                            {{ count($assessment->questions_json) - count($assessment->answers_json ?? []) }}
                        </h6>
                    </div>
                </div>

                <hr>

                <!-- QUESTION REVIEW -->
                <h5 class="fw-bold mb-3">Question Review</h5>

                <div style="max-height: 360px; overflow-y: auto; padding-right: 6px;">

                    @php
                        $answers = $assessment->answers_json ?? [];
                        $questions = \App\Models\Question::whereIn(
                            'id',
                            $assessment->questions_json
                        )->get();
                    @endphp

                    @foreach($questions as $index => $q)
                        @php
                            $userAnswer = $answers[$q->id] ?? null;
                        @endphp

                        <div class="question-box {{ $userAnswer ? 'answered' : 'unanswered' }}">
                            <p class="fw-semibold mb-1">
                                Q{{ $index + 1 }}. {{ $q->question }}
                            </p>

                            @if($userAnswer)
                                <span class="badge bg-info badge-answer">
                                    Your Answer: {{ strtoupper($userAnswer) }}
                                </span>

                                <span class="badge bg-success badge-answer ms-1">
                                    Correct: {{ strtoupper($q->correct_option) }}
                                </span>
                            @else
                                <span class="badge bg-danger badge-answer">
                                    Not Answered
                                </span>
                            @endif
                        </div>
                    @endforeach

                </div>

                <!-- ACTION BUTTONS -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('resume.upload') }}" class="btn btn-outline-secondary">
                        ⬅ Back to Resume
                    </a>

                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        Go to Dashboard
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>

</body>
</html>
