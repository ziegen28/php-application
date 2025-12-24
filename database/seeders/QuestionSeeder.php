<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [

            // =========================
            // WEB & PROGRAMMING BASICS
            // =========================
            [
                'question' => 'What does HTTP stand for?',
                'a' => 'Hyper Text Transfer Protocol',
                'b' => 'High Transfer Text Process',
                'c' => 'Hyperlink Transfer Program',
                'd' => 'Host Text Transmission Protocol',
                'correct' => 'a',
            ],
            [
                'question' => 'Which language runs in the browser?',
                'a' => 'Python',
                'b' => 'Java',
                'c' => 'JavaScript',
                'd' => 'C++',
                'correct' => 'c',
            ],
            [
                'question' => 'What does MVC stand for?',
                'a' => 'Model View Controller',
                'b' => 'Main View Control',
                'c' => 'Model Version Controller',
                'd' => 'Module View Class',
                'correct' => 'a',
            ],
            [
                'question' => 'Which HTML tag is used for hyperlinks?',
                'a' => '<link>',
                'b' => '<a>',
                'c' => '<href>',
                'd' => '<url>',
                'correct' => 'b',
            ],
            [
                'question' => 'Which CSS property controls text size?',
                'a' => 'font-style',
                'b' => 'text-size',
                'c' => 'font-size',
                'd' => 'text-style',
                'correct' => 'c',
            ],

            // =========================
            // PHP & LARAVEL
            // =========================
            [
                'question' => 'Which command creates a Laravel controller?',
                'a' => 'php artisan make:controller',
                'b' => 'php artisan create:controller',
                'c' => 'php artisan new controller',
                'd' => 'php artisan controller:make',
                'correct' => 'a',
            ],
            [
                'question' => 'Which file contains Laravel routes?',
                'a' => 'routes/api.php',
                'b' => 'routes/web.php',
                'c' => 'routes/routes.php',
                'd' => 'routes/http.php',
                'correct' => 'b',
            ],
            [
                'question' => 'Which ORM does Laravel use?',
                'a' => 'Doctrine',
                'b' => 'Eloquent',
                'c' => 'Hibernate',
                'd' => 'ActiveRecord',
                'correct' => 'b',
            ],
            [
                'question' => 'Which method retrieves all records in Eloquent?',
                'a' => 'fetch()',
                'b' => 'getAll()',
                'c' => 'all()',
                'd' => 'select()',
                'correct' => 'c',
            ],
            [
                'question' => 'What is Blade in Laravel?',
                'a' => 'Database engine',
                'b' => 'Template engine',
                'c' => 'Routing system',
                'd' => 'Queue driver',
                'correct' => 'b',
            ],

            // =========================
            // DATABASE & SQL
            // =========================
            [
                'question' => 'Which SQL clause filters records?',
                'a' => 'ORDER BY',
                'b' => 'GROUP BY',
                'c' => 'WHERE',
                'd' => 'SELECT',
                'correct' => 'c',
            ],
            [
                'question' => 'What does PRIMARY KEY ensure?',
                'a' => 'Null values',
                'b' => 'Unique identification',
                'c' => 'Foreign relation',
                'd' => 'Sorting',
                'correct' => 'b',
            ],
            [
                'question' => 'Which join returns all matching rows?',
                'a' => 'LEFT JOIN',
                'b' => 'RIGHT JOIN',
                'c' => 'INNER JOIN',
                'd' => 'OUTER JOIN',
                'correct' => 'c',
            ],
            [
                'question' => 'Which index improves query performance?',
                'a' => 'PRIMARY',
                'b' => 'UNIQUE',
                'c' => 'INDEX',
                'd' => 'All of the above',
                'correct' => 'd',
            ],

            // =========================
            // API & BACKEND
            // =========================
            [
                'question' => 'Which HTTP method is used to create data?',
                'a' => 'GET',
                'b' => 'POST',
                'c' => 'PUT',
                'd' => 'DELETE',
                'correct' => 'b',
            ],
            [
                'question' => 'What does REST stand for?',
                'a' => 'Remote Execution Service Transfer',
                'b' => 'Representational State Transfer',
                'c' => 'Rapid Endpoint Service Tool',
                'd' => 'Request State Transfer',
                'correct' => 'b',
            ],
            [
                'question' => 'Which status code means success?',
                'a' => '404',
                'b' => '500',
                'c' => '200',
                'd' => '401',
                'correct' => 'c',
            ],

            // =========================
            // SECURITY
            // =========================
            [
                'question' => 'What protects against CSRF?',
                'a' => 'Encryption',
                'b' => 'Token',
                'c' => 'Firewall',
                'd' => 'Hashing',
                'correct' => 'b',
            ],
            [
                'question' => 'Which hashing algorithm is secure?',
                'a' => 'MD5',
                'b' => 'SHA1',
                'c' => 'bcrypt',
                'd' => 'CRC32',
                'correct' => 'c',
            ],

            // =========================
            // ADD MORE (up to 50+)
            // =========================
        ];

        foreach ($questions as $q) {
            Question::create([
                'question' => $q['question'],
                'option_a' => $q['a'],
                'option_b' => $q['b'],
                'option_c' => $q['c'],
                'option_d' => $q['d'],
                'correct_option' => $q['correct'],
            ]);
        }
    }
}
