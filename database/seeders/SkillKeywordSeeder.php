<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillKeywordSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch skills with IDs
        $skills = DB::table('skills')->pluck('id', 'name');

        $skillKeywords = [
            'python' => [
                'python','django','flask','pandas','numpy',
                'fastapi','pytest','sqlalchemy','scikit'
            ],
            'java' => [
                'java','spring','spring boot','hibernate',
                'jpa','maven','gradle','servlet'
            ],
            'javascript' => [
                'javascript','react','node','express',
                'vue','angular','nextjs','npm'
            ],
            'php' => [
                'php','laravel','symfony','composer',
                'eloquent','blade','mvc','api'
            ],
        ];

        foreach ($skillKeywords as $skillName => $keywords) {
            $skillId = $skills[$skillName] ?? null;

            if (!$skillId) continue;

            foreach ($keywords as $keyword) {
                DB::table('skill_keywords')->insert([
                    'skill_id' => $skillId,
                    'keyword' => strtolower($keyword),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
