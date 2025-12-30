<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Resume | {{ config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-primary: #2563eb;
            --brand-dark: #0f172a;
            --brand-danger: #ef4444;
            --bg-body: #f8fafc;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--brand-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            user-select: none;
        }

        /* Fixed Navigation */
        .header-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            padding: 0.8rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* Upload Container */
        .upload-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .upload-card {
            background: #ffffff;
            width: 100%;
            max-width: 500px;
            padding: 3rem;
            border-radius: 24px;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
            border: 1px solid var(--border-color);
            text-align: center;
        }

        .upload-title {
            font-weight: 800;
            font-size: 1.75rem;
            color: var(--brand-dark);
            letter-spacing: -0.02em;
        }

        /* File Drop Zone */
        .file-drop-zone {
            border: 2px dashed #cbd5e1;
            padding: 3.5rem 2rem;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: block;
            background: #fcfdfe;
            margin: 2rem 0;
        }

        .file-drop-zone:hover {
            border-color: var(--brand-primary);
            background: rgba(37, 99, 235, 0.03);
            transform: translateY(-2px);
        }

        .file-icon-circle {
            width: 64px;
            height: 64px;
            background: rgba(37, 99, 235, 0.1);
            color: var(--brand-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            font-size: 1.5rem;
        }

        /* Buttons */
        .btn-upload {
            background: var(--brand-primary);
            color: #ffffff;
            font-weight: 700;
            padding: 1rem;
            border-radius: 12px;
            border: none;
            width: 100%;
            transition: all 0.2s ease;
        }

        .btn-upload:hover {
            background: #1d4ed8;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }

        .btn-logout-red {
            background-color: var(--brand-danger);
            color: white !important;
            border-radius: 8px;
            padding: 8px 18px;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Selected File Status */
        #fileInfo {
            display: none;
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
            padding: 0.75rem;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .instruction-text {
            color: #64748b;
            font-size: 0.95rem;
            line-height: 1.6;
        }
    </style>
</head>

<body>

<nav class="header-nav">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ asset('images/sq1logo.jpg') }}" height="32" class="rounded">
            <span class="fw-bold small tracking-tight">SECURE PORTAL</span>
        </div>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-logout-red border-0 shadow-sm">Logout</button>
        </form>
    </div>
</nav>

<div class="upload-wrapper">
    <div class="upload-card">
        <header>
            <h2 class="upload-title mb-2">Resume Upload</h2>
            <p class="instruction-text">Please upload your professional CV in PDF format. Our AI will analyze your profile to tailor the assessment.</p>
        </header>

        <form method="POST" action="{{ route('resume.store') }}" enctype="multipart/form-data">
            @csrf

            <label class="file-drop-zone" for="resumeInput">
                <input type="file" name="resume" accept="application/pdf" hidden required id="resumeInput">
                <div id="fileStatusUI">
                    <div class="file-icon-circle">
                        📄
                    </div>
                    <strong id="fileLabel" class="d-block fs-5 text-dark mb-1">Select Resume File</strong>
                    <span class="text-muted small">Supports PDF only (Max 5MB)</span>
                </div>
            </label>

            <div id="fileInfo">
                <span class="me-2">✓</span> <span id="fileName"></span>
            </div>

            <button type="submit" class="btn btn-upload shadow-sm" id="submitBtn">
                Proceed to Analysis
            </button>

            @if(session('error'))
                <div class="alert alert-danger mt-4 py-2 small border-0 shadow-sm">
                    {{ session('error') }}
                </div>
            @endif
        </form>

        <div class="mt-5 pt-3 border-top opacity-50 small fw-medium">
            &copy; {{ date('Y') }} Secure Assessment Management System <br>
            Ensure your document is clear and text-searchable.
        </div>
    </div>
</div>

<script>
    const resumeInput = document.getElementById('resumeInput');
    const fileNameDisplay = document.getElementById('fileName');
    const fileInfoDiv = document.getElementById('fileInfo');
    const fileLabel = document.getElementById('fileLabel');
    const submitBtn = document.getElementById('submitBtn');

    resumeInput.addEventListener('change', function () {
        if (this.files.length > 0) {
            fileNameDisplay.textContent = this.files[0].name;
            fileInfoDiv.style.display = "block";
            fileLabel.textContent = "Change Selected File";
            
            // Highlight drop zone
            document.querySelector('.file-drop-zone').style.borderColor = 'var(--brand-primary)';
            document.querySelector('.file-drop-zone').style.background = 'rgba(37, 99, 235, 0.02)';
        }
    });

    // Optional: Visual loading state on click
    submitBtn.addEventListener('click', function() {
        if(resumeInput.files.length > 0) {
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Analyzing Profile...';
        }
    });
</script>

</body>
</html>