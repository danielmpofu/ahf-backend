<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExerciseAttempts extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [ 'student_id', 'course_id', 'exercise_id', 'score', 'questions', 'pass_mark'];
}
