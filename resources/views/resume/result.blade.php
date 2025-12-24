<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resume Match Result</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            min-height: 100vh;
        }

        .result-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 2.5rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .percentage-circle {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: #2563eb;
            color: #fff;
            font-size: 2.2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
        }

        .skill-chip {
            background: #f1f5f9;
            border-radius: 30px;
            padding: 0.5rem 1rem;
            margin: 0.3rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .eligible {
            color: #16a34a;
            font-weight: 600;
        }

        .not-eligible {
            color: #dc2626;
            font-weight: 600;
        }
    </style>
</head>

<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="result-card text-center">

                <h2 class="fw-bold mb-3">Resume Analysis</h2>

                <!-- Overall Percentage -->
                <div class="percentage-circle mb-3">
                    {{ $bestSkill['percentage'] }}%
                </div>

                <h4 class="mb-2">
                    Best Match:
                    <span class="text-primary">{{ ucfirst($bestSkill['skill_name']) }}</span>
                </h4>

                <!-- Eligibility -->
                <p class="{{ $isEligible ? 'eligible' : 'not-eligible' }}">
                    {{ $isEligible ? 'Eligible for Assessment' : 'Not Eligible for Assessment' }}
                </p>

                <hr>

                <!-- Skills Summary -->
                <h5 class="fw-semibold mb-3">Detected Skill Matches</h5>

                <div class="d-flex flex-wrap justify-content-center">
                    @foreach ($results as $index => $row)
                        <span class="skill-chip">
                            {{ ucfirst($row['skill_name']) }}
                            <button
                                class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#keywordsModal{{ $index }}">
                                keywords
                            </button>
                        </span>
                    @endforeach
                </div>
                @if ($isEligible)
                <a href="{{ route('assessment.start') }}"
                class="btn btn-primary btn-lg px-4 mt-4">
                Start Assessment
                </a>
                @else
               <p class="text-muted mt-4">
               Improve your resume with more relevant keywords to qualify.
               </p>
              <a href="{{ route('resume.upload') }}" class="btn btn-outline-secondary mt-2">
               Upload Another Resume
              </a>
              @endif
            </div>
        </div>
    </div>
</div>

<!-- Keyword Modals -->
@foreach ($results as $index => $row)
<div class="modal fade" id="keywordsModal{{ $index }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    {{ ucfirst($row['skill_name']) }} – Matched Keywords
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                @if(count($row['matched_keywords']))
                    @foreach ($row['matched_keywords'] as $kw)
                        <span class="badge bg-success me-1 mb-1">
                            {{ $kw }}
                        </span>
                    @endforeach
                @else
                    <p class="text-muted">No keywords matched</p>
                @endif
            </div>

        </div>
    </div>
</div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
