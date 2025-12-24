<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Assessment Management</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }

        .navbar-brand {
            font-weight: 700;
        }

        .card {
            border-radius: 14px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }

        .stat-title {
            font-size: 0.9rem;
            color: #6b7280;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand" href="#">Assessment Admin</a>

    <div class="ms-auto">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-outline-light btn-sm">Logout</button>
        </form>
    </div>
</nav>

<!-- MAIN CONTENT -->
<div class="container my-5">

    <h2 class="mb-4">Admin Dashboard</h2>

    <!-- STATS ROW -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card p-4">
                <div class="stat-title">Total Users</div>
                <div class="stat-value">—</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4">
                <div class="stat-title">Resumes Uploaded</div>
                <div class="stat-value">—</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4">
                <div class="stat-title">Assessments Taken</div>
                <div class="stat-value">—</div>
            </div>
        </div>
    </div>

    <!-- PLACEHOLDER SECTIONS -->
    <div class="card p-4 mb-4">
        <h5>Recent Resume Uploads</h5>
        <p class="text-muted mb-0">
            Resume data will appear here after Day-2 integration.
        </p>
    </div>

    <div class="card p-4">
        <h5>Assessment Overview</h5>
        <p class="text-muted mb-0">
            Assessment statistics will be shown here after Day-3.
        </p>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
