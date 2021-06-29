<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mcq extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'created_by',
        'course_id',
        'exercise_id',
        'question',
        'answer',
        'provided_answer',
        'answer_explanation',
        'choice_one',
        'choice_two',
        'choice_three',
        'choice_four',
        'attachment_url'
    ];

    public  function exercise(){
        return $this->belongsTo(Exercise::class,'exercise_id','id');
    }
}
