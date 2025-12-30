<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Assessment;
use App\Models\Question;

class AssessmentController extends Controller
{
    /* ================= BASIC PAGES ================= */

    public function showLoginForm()
    {
        return view('login');
    }

    public function uploadresume()
    {
        if (Assessment::where('user_id', Auth::id())->where('status', 'completed')->exists()) {
            return redirect()->route('user.dashboard');
        }

        return view('resumeupload');
    }

    public function instructions()
    {
        if (Assessment::where('user_id', Auth::id())->where('status', 'completed')->exists()) {
            return redirect()->route('user.dashboard');
        }

        return view('assessment.instructions');
    }

    /* ================= ASSESSMENT ENGINE ================= */

    public function startAssessment()
    {
        $user = Auth::user();

        if (Assessment::where('user_id', $user->id)->where('status', 'completed')->exists()) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Assessment already completed.');
        }

        Assessment::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('end_time', '<', now())
            ->update(['status' => 'expired']);

        $active = Assessment::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if ($active) {
            return redirect()->route('assessment.take', $active->id);
        }

        $questionIds = Question::inRandomOrder()->limit(20)->pluck('id')->toArray();
        $now = Carbon::now();

        $assessment = Assessment::create([
            'user_id'         => $user->id,
            'questions_json' => $questionIds,
            'answers_json'   => [],
            'start_time'     => $now,
            'end_time'       => $now->copy()->addMinutes(20),
            'status'         => 'active',
            'violations'     => 0
        ]);

        return redirect()->route('assessment.take', $assessment->id);
    }

    public function takeAssessment(Request $request, $id)
    {
        $assessment = Assessment::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($assessment->status !== 'active') {
            return redirect()->route('assessment.results', $assessment->id);
        }

        $remainingSeconds = now()->diffInSeconds($assessment->end_time, false);

        if ($remainingSeconds <= 0) {
            return $this->submitAssessment($assessment->id);
        }

        $index = max(0, (int) $request->query('q', 0));
        $questions = $assessment->questions_json;

        if ($index >= count($questions)) {
            return redirect()->route('assessment.results', $assessment->id);
        }

        return response()
            ->view('assessment.start', [
                'assessment'       => $assessment,
                'question'         => Question::findOrFail($questions[$index]),
                'index'            => $index,
                'remainingSeconds' => $remainingSeconds,
                'total'            => count($questions)
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function saveAnswer(Request $request, $id)
    {
        $assessment = Assessment::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->firstOrFail();

        $answers = $assessment->answers_json ?? [];
        $answers[$request->question_id] = $request->answer;

        $assessment->update(['answers_json' => $answers]);

        return response()->noContent();
    }

    public function submitAssessment($id)
    {
        $assessment = Assessment::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->firstOrFail();

        $answers = $assessment->answers_json ?? [];
        $questions = Question::whereIn('id', $assessment->questions_json)->get();

        $correct = 0;
        foreach ($questions as $q) {
            if (($answers[$q->id] ?? null) === $q->correct_option) {
                $correct++;
            }
        }

        $assessment->update([
            'correct_answers' => $correct,
            'percentage'      => round(($correct / max($questions->count(), 1)) * 100, 2),
            'status'          => 'completed',
        ]);

        return redirect()->route('assessment.results', $assessment->id);
    }

    public function assessmentResult($id)
    {
        return view('assessment.results', [
            'assessment' => Assessment::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail()
        ]);
    }

    /* 🔥 FINAL VIOLATION LOGGER */
    public function logViolation(Request $request, $id)
    {
        $assessment = Assessment::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->firstOrFail();

        $assessment->increment('violations');

        return response()->json([
            'message'    => 'Violation recorded',
            'violations' => $assessment->violations
        ]);
    }
}
