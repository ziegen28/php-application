<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use App\Models\Assessment;

class UserController extends Controller
{
    public function userDashboard()
{
    $user = auth()->user();

    $resume = Resume::where('user_id', $user->id)->first();

    // ✅ Get active assessment
    $assessment = Assessment::where('user_id', $user->id)
        ->where('status', 'active')
        ->first();

    // ⏱️ AUTO-EXPIRE IF TIME OVER
    if ($assessment && now()->greaterThan($assessment->end_time)) {
        $assessment->update(['status' => 'expired']);
        $assessment = null;
    }

    // ✅ If no active, get latest completed
    if (!$assessment) {
        $assessment = Assessment::where('user_id', $user->id)
            ->where('status', 'completed')
            ->latest()
            ->first();
    }

    return view('user.dashboard', compact(
        'user',
        'resume',
        'assessment'
    ));
}
    
}
