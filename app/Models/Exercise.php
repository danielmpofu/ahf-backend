<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exercise extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'student_id',
        'created_by',
        'title',
        'attempts',
        'pass_mark',
        'duration',
        'start_time',
        'end_time',
        'description',
        'unlocked',
        'contribution',
        'final_test',
        'requirements'
    ];

    public function course(){
        return $this->belongsTo(Course::class,'course_id','id');
    }

    public function mcqs(){
        return $this->hasMany(Mcq::class,'exercise_id','id');
    }



}
