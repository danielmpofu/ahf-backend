<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResourceViews extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'course_id', 'resource_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function courseResource()
    {
        return $this->belongsTo(CourseResource::class, 'resource_id', 'id');
    }
}
