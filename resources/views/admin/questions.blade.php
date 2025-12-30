@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Upload Questions (CSV)</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST"
          action="{{ route('admin.questions.upload') }}"
          enctype="multipart/form-data">
        @csrf

        <input type="file"
               name="csv_file"
               class="form-control mb-3"
               accept=".csv"
               required>

        <button class="btn btn-primary">
            Upload Questions
        </button>
    </form>

    <small class="text-muted d-block mt-3">
        CSV format: question, option_a, option_b, option_c, option_d, correct_option
    </small>
</div>
@endsection
