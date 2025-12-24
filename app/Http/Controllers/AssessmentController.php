<?php

namespace App\Http\Controllers;

class AssessmentController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    // Admin only
    public function index()
    {
        return view('dashboard');
    }

    // User only
    public function uploadresume()
    {
        return view('resumeupload');
    }
}
