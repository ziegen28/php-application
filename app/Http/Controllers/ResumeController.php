<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Resume;

class ResumeController extends Controller
{
    public function store(Request $request)
    {
        /**
         * ----------------------------------------------------
         * 1. VALIDATE RESUME
         * ----------------------------------------------------
         */
        $request->validate([
            'resume' => 'required|mimes:pdf|max:2048'
        ]);

        /**
         * ----------------------------------------------------
         * 2. STORE RESUME FILE
         * ----------------------------------------------------
         */
        $path = $request->file('resume')->store('resumes');

        if (!Storage::exists($path)) {
            return back()->with('error', 'Uploaded file not found.');
        }

        $fileContents = Storage::get($path);

        /**
         * ----------------------------------------------------
         * 3. CALL FASTAPI MICROSERVICE
         * ----------------------------------------------------
         */
        try {
            $response = Http::timeout(20)
                ->attach('file', $fileContents, 'resume.pdf')
                ->post(config('services.fastapi.url') . '/parse-resume');
        } catch (\Exception $e) {
            return back()->with('error', 'Resume parser service is unreachable.');
        }

        if (!$response->successful()) {
            return back()->with('error', 'Resume parsing failed.');
        }

        $result = $response->json();

        if (
            !isset($result['status']) ||
            $result['status'] !== 'valid' ||
            empty($result['keywords'])
        ) {
            return back()->with('error', 'Invalid or unreadable resume.');
        }

        /**
         * ----------------------------------------------------
         * 4. SAVE RESUME RECORD
         * ----------------------------------------------------
         */
        $resume = Resume::create([
            'user_id' => auth()->id(),
            'file_path' => $path,
            'extracted_text' => $result['text'],
            'status' => 'valid'
        ]);

        /**
         * ----------------------------------------------------
         * 5. MATCH SKILLS (2NF STRUCTURE)
         * ----------------------------------------------------
         */
        $matches = DB::table('skill_keywords')
            ->join('skills', 'skills.id', '=', 'skill_keywords.skill_id')
            ->whereIn('skill_keywords.keyword', $result['keywords'])
            ->select(
                'skills.id as skill_id',
                'skills.name as skill_name',
                DB::raw('COUNT(skill_keywords.id) as matched_count')
            )
            ->groupBy('skills.id', 'skills.name')
            ->get();

        /**
         * ----------------------------------------------------
         * 6. CALCULATE MATCH PERCENTAGE
         * ----------------------------------------------------
         */
        $finalResults = []; // ✅ FIX 1: initialize array

        foreach ($matches as $match) {

            $matchedKeywords = DB::table('skill_keywords')
                ->where('skill_id', $match->skill_id)
                ->whereIn('keyword', $result['keywords'])
                ->pluck('keyword')
                ->toArray();

            $totalKeywords = DB::table('skill_keywords')
                ->where('skill_id', $match->skill_id)
                ->count();

            $percentage = $totalKeywords > 0
                ? round(($match->matched_count / $totalKeywords) * 100, 2)
                : 0;

            $finalResults[] = [
                'skill_id' => $match->skill_id,
                'skill_name' => $match->skill_name,
                'matched' => $match->matched_count,
                'total' => $totalKeywords,
                'percentage' => $percentage,
                'matched_keywords' => $matchedKeywords
            ];
        }

        /**
         * ----------------------------------------------------
         * 7. PICK BEST SKILL
         * ----------------------------------------------------
         */
        usort($finalResults, fn ($a, $b) =>
            $b['percentage'] <=> $a['percentage']
        );

        $bestSkill = $finalResults[0] ?? null;

        if (!$bestSkill) {
            return back()->with('error', 'No skills matched.');
        }

        /**
         * ----------------------------------------------------
         * 8. ELIGIBILITY CHECK (NO REDIRECT)
         * ----------------------------------------------------
         */
        $isEligible = $bestSkill['percentage'] >= 40;

        /**
         * ----------------------------------------------------
         * 9. SAVE MATCH RESULT
         * ----------------------------------------------------
         */
        $resume->update([
            'skill_id' => $bestSkill['skill_id'],
            'match_percentage' => $bestSkill['percentage']
        ]);

        /**
         * ----------------------------------------------------
         * 10. SHOW RESULT PAGE (ALWAYS)
         * ----------------------------------------------------
         */
        return view('resume.result', [
            'resume' => $resume,
            'bestSkill' => $bestSkill,
            'results' => $finalResults,
            'isEligible' => $isEligible
        ]);
    }
}
