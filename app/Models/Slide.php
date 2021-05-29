<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slide extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['image_url', 'audio_url', 'title', 'description', 'position', 'slideshow_id', 'section_id', 'course_id', 'created_by'];
}
