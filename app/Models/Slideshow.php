<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slideshow extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['created_by', 'course_id', 'title', 'description', 'cover_pic'];

}
