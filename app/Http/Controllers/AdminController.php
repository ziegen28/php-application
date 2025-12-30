<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Assessment;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;
use App\Models\Resume;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /* ================= ADMIN DASHBOARD ================= */
   public function dashboard(Request $request)
{
    $tab = $request->get('tab', 'dashboard');

    $users = User::where('users.role', 'user')
        ->leftJoin('assessments', 'users.id', '=', 'assessments.user_id')
        ->leftJoin('resumes', 'users.id', '=', 'resumes.user_id')
        ->select(
            'users.id',
            'users.name',
            'users.email',
            'resumes.id as resume_uploaded',
            'assessments.id as assessment_id',
            'assessments.status as assessment_status',
            'assessments.questions_json',
            'assessments.percentage',
            DB::raw('COALESCE(assessments.violations,0) as violations_count')
        )
        ->get()
        ->map(function ($u) {

            // Question count
            $decoded = json_decode($u->questions_json ?? '[]', true);
            $u->question_count = is_array($decoded) ? count($decoded) : 0;

            // Assessment status
            if (!$u->assessment_id) {
                $u->status_label = 'Invited';
            } elseif ($u->assessment_status === 'active') {
                $u->status_label = 'In Progress';
            } else {
                $u->status_label = 'Completed';
            }

            // Resume status
            $u->resume_label = $u->resume_uploaded ? 'Uploaded' : 'Not Uploaded';

            return $u;
        });

    return view('admin.dashboard', compact('users', 'tab'));
}


    /* ================= SINGLE INVITE ================= */
    public function inviteSingle(Request $request)
    {
        $request->validate(
            ['email' => 'required|email|unique:users,email'],
            ['email.unique' => 'This email is already invited or registered.']
        );

        User::create([
            'name'  => 'Invited User',
            'email' => $request->email,
            'role'  => 'user',
        ]);

        return back()->with('success', 'Invitation sent successfully.')
                     ->with('tab', 'invite');
    }

    /* ================= BULK INVITE ================= */
    public function inviteBulk(Request $request)
    {
        $request->validate(['csv_file' => 'required|mimes:csv,txt']);

        $rows = array_map('str_getcsv', file($request->file('csv_file')));
        $errors = [];

        if (count($rows) <= 1) {
            return back()->withErrors(['CSV file is empty'])->with('tab', 'invite');
        }

        foreach ($rows as $i => $row) {
            if ($i === 0) continue;

            $email = trim($row[0] ?? '');

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Row " . ($i + 1) . ": Invalid email format";
                continue;
            }

            if (User::where('email', $email)->exists()) {
                $errors[] = "Row " . ($i + 1) . ": Email already exists";
                continue;
            }

            User::create([
                'name'  => 'Invited User',
                'email' => $email,
                'role'  => 'user'
            ]);
        }

        return empty($errors)
            ? back()->with('success', 'Bulk invitations completed.')
                    ->with('tab', 'invite')
            : back()->withErrors($errors)
                    ->with('tab', 'invite');
    }

    /* ================= QUESTIONS CSV ================= */
    public function uploadQuestions(Request $request)
    {
        $request->validate(['csv_file' => 'required|mimes:csv,txt']);

        $rows = array_map('str_getcsv', file($request->file('csv_file')));
        $errors = [];

        if (count($rows) <= 1) {
            return back()->withErrors(['CSV contains no questions'])
                         ->with('tab', 'questions');
        }

        $expected = ['question','option_a','option_b','option_c','option_d','correct_option'];
        $header = array_map('strtolower', array_map('trim', $rows[0]));

        if ($header !== $expected) {
            return back()->withErrors(['Invalid CSV header format'])
                         ->with('tab', 'questions');
        }

        DB::beginTransaction();

        foreach ($rows as $i => $row) {
            if ($i === 0) continue;

            if (count($row) < 6) {
                $errors[] = "Row " . ($i + 1) . ": Missing columns";
                continue;
            }

            $correct = strtolower(trim($row[5]));

            if (!in_array($correct, ['a','b','c','d'])) {
                $errors[] = "Row " . ($i + 1) . ": correct_option must be a/b/c/d";
                continue;
            }

            Question::create([
                'question'       => trim($row[0]),
                'option_a'       => trim($row[1]),
                'option_b'       => trim($row[2]),
                'option_c'       => trim($row[3]),
                'option_d'       => trim($row[4]),
                'correct_option' => $correct
            ]);
        }

        if (!empty($errors)) {
            DB::rollBack();
            return back()->withErrors($errors)
                         ->with('tab', 'questions');
        }

        DB::commit();

        return back()->with('success', 'Questions uploaded successfully.')
                     ->with('tab', 'questions');
    }

    /* ================= VIEW REPORT ================= */
    public function viewReport(User $user)
    {
        $assessment = Assessment::where('user_id', $user->id)
            ->where('status', 'completed')
            ->firstOrFail();

        return view('assessment.results', [
            'assessment' => $assessment,
            'candidate'  => $user,
            'isAdmin'    => true
        ]);
    }

    /* ================= SINGLE PDF ================= */
    public function downloadPdf(User $user)
    {
        $assessment = Assessment::where('user_id', $user->id)
            ->where('status', 'completed')
            ->firstOrFail();

        $pdf = Pdf::loadView('reports.assessment-pdf', [
            'assessment' => $assessment,
            'candidate'  => $user
        ])->setPaper('a4');

        return $pdf->download('Assessment_' . $user->id . '.pdf');
    }

    /* ================= BULK ZIP EXPORT ================= */
    public function bulkPdfExport()
    {
        $assessments = Assessment::with('user')
            ->where('status', 'completed')
            ->get();

        if ($assessments->isEmpty()) {
            return back()->withErrors(['No completed assessments found']);
        }

        $zipPath = storage_path('app/reports_' . time() . '.zip');
        $zip = new ZipArchive;
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($assessments as $a) {
            if (!$a->user) continue;

            $pdf = Pdf::loadView('reports.assessment-pdf', [
                'assessment' => $a,
                'candidate'  => $a->user
            ]);

            $zip->addFromString(
                $a->user->email . '_report.pdf',
                $pdf->output()
            );
        }

        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    /* ================= RESUME DOWNLOAD ================= */
    public function downloadResume(User $user)
    {
        $resume = Resume::where('user_id', $user->id)->firstOrFail();

        return Storage::download(
            $resume->file_path,
            'Resume_' . str_replace(' ', '_', $user->name) . '.pdf'
        );
    }
}
