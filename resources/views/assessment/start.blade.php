<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assessment</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            min-height: 100vh;
        }

        .exam-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 32px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
        }

        .timer-badge {
            background: #fde047;
            color: #000;
            font-weight: 700;
            padding: 8px 14px;
            border-radius: 12px;
        }

        .option-card {
            border: 2px solid #e5e7eb;
            border-radius: 14px;
            padding: 14px 16px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            gap: 12px;
        }

        .option-card:hover {
            border-color: #2563eb;
            background: #eff6ff;
        }

        /* QUESTION PALETTE */
        .palette {
            background: #ffffff;
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            position: sticky;
            top: 20px;
        }

        .q-box {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            text-decoration: none;
            border: 2px solid #e5e7eb;
            color: #000;
        }

        .q-box.active {
            background: #2563eb;
            color: #fff;
            border-color: #2563eb;
        }

        .q-box.answered {
            background: #22c55e;
            color: #fff;
            border-color: #22c55e;
        }

        .q-box:hover {
            background: #e5e7eb;
        }
    </style>
</head>

<body>

<div class="container py-5">
    <div class="row">

        <!-- LEFT: QUESTION -->
        <div class="col-lg-8">
            <div class="exam-card">

                <div class="d-flex justify-content-between mb-3">
                    <h5 class="fw-bold">
                        Question {{ $index + 1 }} / {{ $total }}
                    </h5>
                    <span id="timer" class="timer-badge">⏳</span>
                </div>

                <div class="progress mb-4" style="height: 8px;">
                    <div class="progress-bar bg-primary"
                         style="width: {{ (($index + 1) / $total) * 100 }}%">
                    </div>
                </div>

                <h5 class="fw-semibold mb-4">
                    {{ $question->question }}
                </h5>

                @foreach(['a','b','c','d'] as $opt)
                    <label class="option-card">
                        <input type="radio"
                               name="answer"
                               value="{{ $opt }}"
                               {{ ($assessment->answers_json[$question->id] ?? '') === $opt ? 'checked' : '' }}
                               onclick="saveAnswer({{ $question->id }}, '{{ $opt }}')">

                        {{ $question->{'option_'.$opt} }}
                    </label>
                @endforeach

                <div class="d-flex justify-content-between mt-4">
                    @if($index > 0)
                        <a href="{{ route('assessment.take', [$assessment->id, 'q' => $index - 1]) }}"
                           class="btn btn-outline-secondary">
                            ⬅ Previous
                        </a>
                    @else
                        <div></div>
                    @endif

                    @if($index < $total - 1)
                        <a href="{{ route('assessment.take', [$assessment->id, 'q' => $index + 1]) }}"
                           class="btn btn-primary">
                            Next ➡
                        </a>
                    @else
                        <form method="POST" action="{{ route('assessment.submit', $assessment->id) }}">
                            @csrf
                            <button class="btn btn-danger">
                                Submit
                            </button>
                        </form>
                    @endif
                </div>

            </div>
        </div>

        <!-- RIGHT: QUESTION PALETTE -->
        <div class="col-lg-4">
            <div class="palette">
                <h6 class="fw-bold mb-3">Questions</h6>

                <div class="d-flex flex-wrap gap-2">
                    @foreach($assessment->questions_json as $i => $qid)
                        @php
                            $answered = isset($assessment->answers_json[$qid]);
                        @endphp

                        <a href="{{ route('assessment.take', [$assessment->id, 'q' => $i]) }}"
                           class="q-box
                           {{ $i === $index ? 'active' : '' }}
                           {{ $answered ? 'answered' : '' }}">
                            {{ $i + 1 }}
                        </a>
                    @endforeach
                </div>

                <hr>

                <small class="d-block">
                    <span class="badge bg-success">Answered</span>
                    <span class="badge bg-primary">Current</span>
                    <span class="badge bg-secondary">Not Visited</span>
                </small>
            </div>
        </div>

    </div>
</div>

<!-- TIMER + SAVE LOGIC (UNCHANGED) -->
<script>
let remaining = {{ $remainingSeconds }};
let submitted = false;

const timerEl = document.getElementById('timer');

const timer = setInterval(() => {
    if (remaining <= 0 && !submitted) {
        submitted = true;
        clearInterval(timer);

        fetch("{{ route('assessment.submit', $assessment->id) }}", {
            method: "POST",
            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
        }).then(() => {
            window.location.href =
                "{{ route('assessment.results', $assessment->id) }}";
        });
        return;
    }

    const m = Math.floor(remaining / 60);
    const s = remaining % 60;
    timerEl.innerText = `⏳ ${m}:${s.toString().padStart(2,'0')}`;
    remaining--;
}, 1000);

function saveAnswer(qid, ans) {
    fetch("{{ route('assessment.save', $assessment->id) }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ question_id: qid, answer: ans })
    });
}
</script>

</body>
</html>
