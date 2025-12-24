<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resume Match Result</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f9fafb, #eef2ff);
        }

        .result-header {
            padding: 3rem 0;
            text-align: center;
        }

        .skill-badge {
            background: #2563eb;
            color: white;
            padding: 0.5rem 1.25rem;
            border-radius: 999px;
            font-weight: 600;
            font-size: 1rem;
            display: inline-block;
        }

        .percentage-text {
            font-size: 3rem;
            font-weight: 700;
            color: #16a34a;
        }

        .progress {
            height: 14px;
            border-radius: 10px;
        }

        .progress-bar {
            background: linear-gradient(90deg, #22c55e, #16a34a);
        }

        .chip {
            display: inline-block;
            background: #e0e7ff;
            color: #1e40af;
            padding: 0.4rem 0.75rem;
            border-radius: 999px;
            font-size: 0.85rem;
            margin: 0.2rem;
            font-weight: 500;
        }

        .start-btn {
            padding: 0.9rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 12px;
        }

        .table thead {
            background: #f1f5f9;
        }
    </style>
</head>

<body>

<!-- HEADER -->
<section class="result-header">
    <h1 class="fw-bold mb-2">Resume Analysis Result</h1>
    <p class="text-muted">Your resume was analyzed against our skill database</p>

    <div class="mt-3">
        <span class="skill-badge">
            Best Match: {{ ucfirst($bestSkill['skill_name']) }}
        </span>
    </div>

    <div class="mt-4 percentage-text">
        {{ $bestSkill['percentage'] }}%
    </div>

    <div class="container mt-3" style="max-width: 500px;">
        <div class="progress">
            <div
                class="progress-bar"
                style="width: {{ $bestSkill['percentage'] }}%">
            </div>
        </div>
    </div>
</section>

<!-- CONTENT -->
<section class="container mb-5">

    <!-- MATCHED KEYWORDS -->
    <div class="mb-5">
        <h4 class="fw-semibold mb-3">Matched Keywords</h4>

        @foreach ($bestSkill['matched_keywords'] as $keyword)
            <span class="chip">{{ $keyword }}</span>
        @endforeach
    </div>

    <!-- ALL SKILLS TABLE -->
    <div class="mb-5">
        <h4 class="fw-semibold mb-3">Skill Match Breakdown</h4>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Skill</th>
                        <th>Matched</th>
                        <th>Total</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($results as $row)
                        <tr>
                            <td class="fw-semibold">
                                {{ ucfirst($row['skill_name']) }}
                            </td>
                            <td>{{ $row['matched'] }}</td>
                            <td>{{ $row['total'] }}</td>
                            <td>
                                <span class="fw-semibold">
                                    {{ $row['percentage'] }}%
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- CTA -->
    <div class="text-center">
        <form action="{{ route('assessment.ready') }}" method="GET">
            <input type="hidden" name="skill" value="{{ $bestSkill['skill_name'] }}">

            <button class="btn btn-success start-btn shadow">
                Start {{ ucfirst($bestSkill['skill_name']) }} Assessment
            </button>
        </form>
    </div>

</section>

</body>
</html>
