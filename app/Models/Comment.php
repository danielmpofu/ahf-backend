<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{

    use HasFactory, SoftDeletes;

    protected $fillable = ['message', 'user_id', 'entity_id','entity_type'];

    protected $casts = ['entity_id'=>'integer'];

    protected $dates = ['deleted_at'];

    public function course()
    {
        return $this->belongsTo(Course::class, 'entity_id', 'id');
    }

    public function courseResource()
    {
        return $this->belongsTo(CourseResource::class, 'entity_id', 'id');
    }

}
