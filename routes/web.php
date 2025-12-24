<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\Auth\MicrosoftController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect('/login'));

Route::get('/login', [AssessmentController::class, 'showLoginForm'])
    ->middleware('guest')
    ->name('login');

/* Microsoft SSO */
Route::get('/login/microsoft', [MicrosoftController::class, 'redirectToProvider'])
    ->name('login.microsoft');

Route::get('/login/microsoft/callback', [MicrosoftController::class, 'handleProviderCallback']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [AssessmentController::class, 'index'])
        ->middleware('role:admin')
        ->name('dashboard');

    Route::get('/resume/upload', [AssessmentController::class, 'uploadresume'])
        ->middleware('role:user')
        ->name('resume.upload');

    Route::post('/resume/upload', [ResumeController::class, 'store'])
        ->middleware('role:user')
        ->name('resume.store');

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect(
            'https://login.microsoftonline.com/common/oauth2/v2.0/logout'
            . '?post_logout_redirect_uri=' . urlencode(url('/login'))
        );
    })->name('logout');
});

/*
|--------------------------------------------------------------------------
| Assessment Routes (USER)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','role:user'])->group(function () {

    /* ✅ START ASSESSMENT (GET — IMPORTANT FIX) */
    Route::get('/assessment/start',
        [AssessmentController::class, 'startAssessment']
    )->name('assessment.start');

    /* Take assessment (one question at a time) */
    Route::get('/assessment/{id}',
        [AssessmentController::class, 'takeAssessment']
    )->name('assessment.take');

    /* Save answer (Next button) */
    Route::post('/assessment/{id}/save',
        [AssessmentController::class, 'saveAnswer']
    )->name('assessment.save');

    /* Submit assessment */
    Route::post('/assessment/{id}/submit',
        [AssessmentController::class, 'submitAssessment']
    )->name('assessment.submit');

    /* Result page */
    Route::get('/assessment/{id}/result',
        [AssessmentController::class, 'assessmentResult']
    )->name('assessment.results');
});
