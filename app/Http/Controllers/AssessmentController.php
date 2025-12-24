<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Assessment;
use App\Models\Question;

class AssessmentController extends Controller
{
    /* ===============================
       BASIC PAGES
    =============================== */

    public function showLoginForm()
    {
        return view('login');
    }

    public function index()
    {
        return view('dashboard');
    }

    public function uploadresume()
    {
        return view('resumeupload');
    }

    /* ===============================
       ASSESSMENT ENGINE
    =============================== */

    // START OR RESUME ASSESSMENT
    public function startAssessment()
    {
        $user = Auth::user();

        // Expire old active attempts
        Assessment::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('end_time', '<', now())
            ->update(['status' => 'expired']);

        // Resume existing active attempt
        $active = Assessment::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if ($active) {
            return redirect()->route('assessment.take', $active->id);
        }

        // Create new assessment
        $questionIds = Question::inRandomOrder()
            ->limit(20)
            ->pluck('id')
            ->toArray();

        $now = Carbon::now();

        $assessment = Assessment::create([
            'user_id'        => $user->id,
            'questions_json'=> $questionIds,
            'answers_json'  => [],
            'start_time'    => $now,
            'end_time'      => $now->copy()->addMinutes(20),
            'status'        => 'active',
        ]);

        return redirect()->route('assessment.take', $assessment->id);
    }

    // SHOW QUESTION (ONE AT A TIME)
    public function takeAssessment(Request $request, $id)
    {
        $assessment = Assessment::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // If already finished → result
        if ($assessment->status !== 'active') {
            return redirect()->route('assessment.results', $assessment->id);
        }

        // Remaining time (SERVER SIDE)
        $remainingSeconds = now()->diffInSeconds($assessment->end_time, false);

        if ($remainingSeconds <= 0) {
            return $this->submitAssessment($assessment->id);
        }

        $index = max(0, (int) $request->query('q', 0));
        $questions = $assessment->questions_json;

        if ($index >= count($questions)) {
            return redirect()->route('assessment.results', $assessment->id);
        }

        $question = Question::findOrFail($questions[$index]);

        return view('assessment.start', [
            'assessment'       => $assessment,
            'question'         => $question,
            'index'            => $index,
            'total'            => count($questions),
            'remainingSeconds'=> $remainingSeconds,
        ]);
    }

    // SAVE ANSWER (ON NEXT CLICK)
    public function saveAnswer(Request $request, $id)
    {
        $request->validate([
            'question_id' => 'required',
            'answer'      => 'required|in:a,b,c,d',
        ]);

        $assessment = Assessment::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($assessment->status !== 'active') {
            return redirect()->route('assessment.results', $assessment->id);
        }

        $answers = $assessment->answers_json ?? [];
        $answers[$request->question_id] = $request->answer;

        $assessment->update(['answers_json' => $answers]);

        return redirect()->route('assessment.take', [
            $assessment->id,
            'q' => $request->next_q
        ]);
    }

    // FINAL SUBMIT
    public function submitAssessment($id)
    {
        $assessment = Assessment::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($assessment->status !== 'active') {
            return redirect()->route('assessment.results', $assessment->id);
        }

        $answers = $assessment->answers_json ?? [];
        $questions = Question::whereIn('id', $assessment->questions_json)->get();

        $correct = 0;

        foreach ($questions as $q) {
            if (($answers[$q->id] ?? null) === $q->correct_option) {
                $correct++;
            }
        }

        $total = $questions->count();
        $percentage = $total > 0 ? round(($correct / $total) * 100, 2) : 0;

        $assessment->update([
            'correct_answers' => $correct,
            'percentage'      => $percentage,
            'status'          => 'completed',
        ]);

        return redirect()->route('assessment.results', $assessment->id);
    }

    // RESULT PAGE
    public function assessmentResult($id)
    {
        $assessment = Assessment::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('assessment.results', compact('assessment'));
    }
}
