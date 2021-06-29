<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnswerSheets extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'attempt_id',
        'course_id',
        'exercise_id',
        'question_id',
        'question',
        'answer',
        'correct',
        'provided_answer',
        'answer_explanation',
        'choice_one',
        'choice_two',
        'choice_three',
        'choice_four',
        'attachment_url'
    ];


}
