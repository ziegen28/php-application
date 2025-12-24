<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assessment Ready</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4 text-center">
        <h2 class="mb-3">Assessment Ready</h2>

        <p class="text-muted">
            You are eligible for the <strong>{{ ucfirst($skill) }}</strong> assessment.
        </p>

        <p class="fw-bold text-success">
            Click below when you are ready to begin.
        </p>

        <form action="#" method="POST">
            @csrf
            <button class="btn btn-primary btn-lg mt-3">
                Start Assessment
            </button>
        </form>
    </div>
</div>

</body>
</html>
