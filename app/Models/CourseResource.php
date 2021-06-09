<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseResource extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = ['title',
        'description',
        'contribution',
        'points',
        'created_by',
        'path',
        'file_type',
        'file_extension',
        'file_size',
        'course_id'];

    public function resourceViews(){
        return $this->hasMany(ResourceViews::class,'resource_id','id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'entity_id', 'id');
    }

    public function courseProgress()
    {
        return $this->hasMany(CourseProgress::class, 'resource_id', 'id');
    }


}
