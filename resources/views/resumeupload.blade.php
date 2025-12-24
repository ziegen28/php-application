<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Management System | Resume Upload</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            transition: background 0.3s ease;
        }

        .upload-container {
            min-height: 100vh;
        }

        .upload-card {
            background: #ffffff;
            width: 100%;
            max-width: 460px;
            padding: 3rem 2.5rem;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            position: relative;
            animation: fadeInUp 0.6s ease-in-out;
        }

        .upload-title {
            font-weight: 700;
            color: #1f2937;
        }

        .upload-subtitle {
            color: #6b7280;
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }

        .file-input {
            border: 2px dashed #d1d5db;
            padding: 1.5rem;
            border-radius: 12px;
            cursor: pointer;
            transition: border-color 0.2s ease;
        }

        .file-input:hover {
            border-color: #2563eb;
        }

        .btn-upload {
            background: #2563eb;
            color: #ffffff;
            font-weight: 600;
            padding: 0.75rem;
            border-radius: 10px;
            border: none;
            transition: background 0.2s ease;
        }

        .btn-upload:hover {
            background: #1d4ed8;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .footer-text {
            margin-top: 2rem;
            font-size: 0.8rem;
            color: #9ca3af;
        }

        /* Light Mode */
        body.light-mode {
            background: #f3f4f6;
        }

        body.light-mode .upload-card {
            background: #ffffff;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
<div class="container upload-container d-flex justify-content-center align-items-center">
    <div class="upload-card text-center">

        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}" class="logout-btn">
            @csrf
            <button class="btn btn-outline-secondary btn-sm">
                Logout
            </button>
        </form>

        <!-- Dark / Light Toggle -->
        <div class="form-check form-switch position-absolute top-0 end-0 m-3">
            <input class="form-check-input" type="checkbox" id="themeToggle">
        </div>

        <!-- Logo -->
        <div class="mb-3">
            <img src="{{ asset('images/logo.png') }}" alt="Company Logo" width="80">
        </div>

        <!-- Title -->
        <h2 class="upload-title">Resume Upload</h2>
        <p class="upload-subtitle">
            Upload your resume in PDF format for assessment
        </p>

        <!-- Upload Form -->
        <form method="POST" action="{{ route('resume.store') }}" enctype="multipart/form-data">
            @csrf

            <label class="file-input w-100 mb-3">
                <input type="file" name="resume" accept="application/pdf" hidden required>
                <strong>Click to select PDF</strong><br>
                <small class="text-muted">Only PDF files are allowed</small>
            </label>

            <button type="submit" class="btn btn-upload w-100">
                Upload Resume
            </button>

            @if(session('error'))
                <div class="alert alert-danger mt-3">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @endif
        </form>

        <!-- Footer -->
        <div class="footer-text">
            © {{ date('Y') }} Assessment Platform. All rights reserved by Ziegen LLC.
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- Theme Toggle -->
<script>
    const toggle = document.getElementById('themeToggle');
    toggle.addEventListener('change', function () {
        document.body.classList.toggle('light-mode');
    });
</script>

</body>
</html>
