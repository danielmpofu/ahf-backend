<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory;

    use SoftDeletes;

    //<--- use the softdelete traits

    protected $dates = ['deleted_at'];

    //duration in weeks
    //course rating
    //tutor name in dto
    //num quizzes
    //num-lectures
    //max retakes
    //pass percentage
    //faqs - different table
    protected $fillable = [
        'title',
        'description',
        'level',
        'duration',
        'entry_requirements',
        'optional',
        'level',
        'content',
        'cover_image'
    ];

    protected $hidden = ['instructor', 'course_resources', 'users', 'comments'];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(Enrollment::class, 'course_id', 'id');
    }

    public function courseResources()
    {
        return $this->hasMany(CourseResource::class, 'course_id', 'id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'entity_id', 'id');
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class, 'course_id', 'id');
    }


}
