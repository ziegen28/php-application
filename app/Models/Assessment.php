<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = [
        'user_id',
        'questions_json',
        'answers_json',
        'start_time',
        'end_time',
        'status',
        'correct_answers',
        'percentage',
    ];

    protected $casts = [
        'questions_json' => 'array',
        'answers_json' => 'array',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
}
