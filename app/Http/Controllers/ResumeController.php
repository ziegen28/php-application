<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Resume;
use App\Models\Assessment;

class ResumeController extends Controller
{
    /* ===============================
       STORE & ANALYZE RESUME
    =============================== */
    public function store(Request $request)
    {
        $user = auth()->user();

        /* ❌ LOCK AFTER ASSESSMENT COMPLETION */
        if (
            Assessment::where('user_id', $user->id)
                ->where('status', 'completed')
                ->exists()
        ) {
            return redirect()
                ->route('user.dashboard')
                ->with('error', 'Assessment already completed. Resume upload locked.');
        }

        /* ✅ VALIDATE RESUME */
        $request->validate([
            'resume' => 'required|mimes:pdf|max:2048'
        ]);

        /* ✅ DELETE OLD RESUME FILE */
        $existing = Resume::where('user_id', $user->id)->first();
        if ($existing && $existing->file_path) {
            Storage::delete($existing->file_path);
        }

        /* ✅ STORE NEW FILE */
        $path = $request->file('resume')->store('resumes');
        $fileContents = Storage::get($path);

        /* ✅ CALL FASTAPI */
        $response = Http::timeout(20)
            ->attach('file', $fileContents, 'resume.pdf')
            ->post(config('services.fastapi.url') . '/parse-resume');

        if (!$response->successful()) {
            return back()->with('error', 'Resume parsing failed.');
        }

        $result = $response->json();

        if (
            ($result['status'] ?? '') !== 'valid' ||
            empty($result['keywords'])
        ) {
            return back()->with('error', 'Invalid or unreadable resume.');
        }

        /* ✅ SAVE RESUME RECORD */
        $resume = Resume::updateOrCreate(
            ['user_id' => $user->id],
            [
                'file_path'      => $path,
                'extracted_text' => $result['text'] ?? '',
                'status'         => 'valid'
            ]
        );

        /* ===============================
           SKILL MATCHING (STABLE & SAFE)
        =============================== */
        $resumeKeywords = array_unique(
            array_map('strtolower', $result['keywords'])
        );

        $skills = DB::table('skills')->get();
        $finalResults = [];

        foreach ($skills as $skill) {

            $skillKeywords = DB::table('skill_keywords')
                ->where('skill_id', $skill->id)
                ->pluck('keyword')
                ->map(fn ($k) => strtolower($k))
                ->toArray();

            $matchedKeywords = array_values(
                array_intersect($skillKeywords, $resumeKeywords)
            );

            if (empty($matchedKeywords)) {
                continue;
            }

            $total = count($skillKeywords);
            $matched = count($matchedKeywords);

            $percentage = $total > 0
                ? round(($matched / $total) * 100, 2)
                : 0;

            $finalResults[] = [
                'skill_id'         => $skill->id,
                'skill_name'       => $skill->name,
                'matched'          => $matched,
                'total'            => $total,
                'percentage'       => $percentage,
                'matched_keywords' => $matchedKeywords, // ✅ ALWAYS PRESENT
            ];
        }

        if (empty($finalResults)) {
            return back()->with('error', 'No skills matched.');
        }

        /* ✅ SORT BY BEST MATCH */
        usort($finalResults, fn ($a, $b) => $b['percentage'] <=> $a['percentage']);
        $bestSkill = $finalResults[0];

        /* ✅ SAVE BEST SKILL */
        $resume->update([
            'skill_id'         => $bestSkill['skill_id'],
            'match_percentage' => $bestSkill['percentage']
        ]);

        /* ✅ STORE SESSION RESULT */
        session([
            'resume_results' => $finalResults
        ]);

        return redirect()->route('resume.result');
    }

    /* ===============================
       SHOW RESUME RESULT
    =============================== */
    public function showResult()
    {
        $user = auth()->user();

        Resume::where('user_id', $user->id)->firstOrFail();

        $results = session('resume_results');

        if (!$results || !is_array($results)) {
            return redirect()->route('resume.upload');
        }

        /* ✅ NORMALIZE (EXTRA SAFETY) */
        $normalizedResults = [];

        foreach ($results as $row) {
            $normalizedResults[] = [
                'skill_id'         => $row['skill_id'] ?? null,
                'skill_name'       => $row['skill_name'] ?? 'Unknown',
                'matched'          => $row['matched'] ?? 0,
                'total'            => $row['total'] ?? 0,
                'percentage'       => $row['percentage'] ?? 0,
                'matched_keywords' => $row['matched_keywords'] ?? [],
            ];
        }

        $bestSkill = collect($normalizedResults)
            ->sortByDesc('percentage')
            ->first();

        $isEligible = $bestSkill && $bestSkill['percentage'] >= 40;

        return view('resume.result', [
            'bestSkill'  => $bestSkill,
            'results'    => $normalizedResults,
            'isEligible' => $isEligible
        ]);
    }
}
