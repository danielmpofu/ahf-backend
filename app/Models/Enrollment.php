<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{

    use HasFactory;

    use SoftDeletes;


    protected $dates = ['deleted_at'];

    protected $fillable = ['user_id', 'course_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'id', 'course_id');
    }
}
