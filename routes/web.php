<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\Auth\MicrosoftController;

// Home
Route::get('/', function () {
    return redirect('/login');
});

// Login (blocked if already logged in)
Route::get('/login', [AssessmentController::class, 'showLoginForm'])
    ->middleware('guest')
    ->name('login');

// Microsoft SSO
Route::get('/login/microsoft', [MicrosoftController::class, 'redirectToProvider'])
    ->name('login.microsoft');

Route::get('/login/microsoft/callback', [MicrosoftController::class, 'handleProviderCallback']);

// Protected routes
Route::middleware('auth')->group(function () {

    // Admin dashboard
    Route::get('/dashboard', [AssessmentController::class, 'index'])
        ->middleware('role:admin')
        ->name('dashboard');

    // User resume upload
    Route::get('/resume/upload', [AssessmentController::class, 'uploadresume'])
        ->middleware('role:user')
        ->name('resume.upload');

    Route::post('/resume/upload', [ResumeController::class, 'store'])
        ->middleware('role:user')
        ->name('resume.store');

    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect( 'https://login.microsoftonline.com/common/oauth2/v2.0/logout'
    . '?post_logout_redirect_uri=' . urlencode(url('/login')));
    })->name('logout');
});

// Route::get('/assessment/ready/{skill}', function ($skill) {
//     return view('assessment.ready', compact('skill'));
// })->name('assessment.ready');



Route::get('/assessment', function (Request $request) {
    $skill = $request->query('skill');
    return view('assessment.start', compact('skill'));
})->name('assessment.ready');
