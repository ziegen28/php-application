<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\MicrosoftController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect('/login'));

Route::get('/login', [AssessmentController::class, 'showLoginForm'])
    ->middleware('guest')
    ->name('login');

/*
|--------------------------------------------------------------------------
| MICROSOFT SSO
|--------------------------------------------------------------------------
*/
Route::get('/login/microsoft',
    [MicrosoftController::class, 'redirectToProvider']
)->name('login.microsoft');

Route::get('/login/microsoft/callback',
    [MicrosoftController::class, 'handleProviderCallback']
);

/*
|--------------------------------------------------------------------------
| AUTH COMMON
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

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
| USER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->group(function () {

    /* USER DASHBOARD */
    Route::get('/user/dashboard',
        [UserController::class, 'userDashboard']
    )->name('user.dashboard');

    /*
    |--------------------------------------------------------------------------
    | RESUME FLOW
    |--------------------------------------------------------------------------
    */
    Route::get('/resume/upload',
        [AssessmentController::class, 'uploadresume']
    )->name('resume.upload');

    Route::post('/resume/upload',
        [ResumeController::class, 'store']
    )->name('resume.store');

    Route::get('/resume/result',
        [ResumeController::class, 'showResult']
    )->name('resume.result');

    /*
    |--------------------------------------------------------------------------
    | ASSESSMENT FLOW
    |--------------------------------------------------------------------------
    */

    Route::get('/assessment/instructions',
        [AssessmentController::class, 'instructions']
    )->name('assessment.instructions');

    Route::get('/assessment/start',
        [AssessmentController::class, 'startAssessment']
    )->name('assessment.start');

    /*
     ✅ IMPORTANT ORDER
     Result route MUST be above /assessment/{id}
    */
    Route::get('/assessment/{id}/result',
        [AssessmentController::class, 'assessmentResult']
    )->name('assessment.results');

    Route::post('/assessment/{id}/submit',
        [AssessmentController::class, 'submitAssessment']
    )->name('assessment.submit');

    Route::post('/assessment/{id}/save',
        [AssessmentController::class, 'saveAnswer']
    )->name('assessment.save');

    /*
     🔥 VIOLATION LOGGING (REQUIRED)
    */
    Route::post('/assessment/{id}/violation',
        [AssessmentController::class, 'logViolation']
    )->name('assessment.violation');

    Route::get('/assessment/{id}',
        [AssessmentController::class, 'takeAssessment']
    )->name('assessment.take');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {

    /* DEFAULT REDIRECT */
    Route::get('/dashboard',
        fn () => redirect()->route('admin.dashboard')
    )->name('dashboard');

    /* ADMIN DASHBOARD */
    Route::get('/admin/dashboard',
        [AdminController::class, 'dashboard']
    )->name('admin.dashboard');

    /* RESUME DOWNLOAD */
    Route::get('/admin/resume/{user}/download',
        [AdminController::class, 'downloadResume']
    )->name('admin.resume.download');

    /* INVITES */
    Route::post('/admin/invite-single',
        [AdminController::class, 'inviteSingle']
    )->name('admin.invite.single');

    Route::post('/admin/invite-bulk',
        [AdminController::class, 'inviteBulk']
    )->name('admin.invite.bulk');

    /* QUESTIONS CSV */
    Route::post('/admin/upload-questions',
        [AdminController::class, 'uploadQuestions']
    )->name('admin.upload.questions');

    /* REPORTS */
    Route::get('/admin/report/{user}',
        [AdminController::class, 'viewReport']
    )->name('admin.report.view');

    Route::get('/admin/report/{user}/download',
        [AdminController::class, 'downloadReport']
    )->name('admin.report.download');

    Route::get('/admin/report/{user}/pdf',
        [AdminController::class, 'downloadPdf']
    )->name('admin.report.pdf');

    Route::get('/admin/reports/pdf/bulk',
        [AdminController::class, 'bulkPdfExport']
    )->name('admin.report.bulk.pdf');
});
