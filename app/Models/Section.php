<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'slideshow_id' , 'course_id' , 'created_by' , 'title' , 'description' , 'number'
    ];

}
