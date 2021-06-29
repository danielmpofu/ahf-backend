<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseProgress extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'course_id',
        'resource_id',
        'resource_type',
        'user_id',
        'points',
    ];

    public function courseResource(){
        return $this->belongsTo(CourseResource::class,'');
    }
}
