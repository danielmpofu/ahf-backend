<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
{
    use HasFactory;
    protected $fillable = ['description','title'];
    use SoftDeletes; //<--- use the softdelete traits

    protected $dates = ['deleted_at'];
    public function courses(){
        return $this->hasMany(Course::class,'level','id');
    }

}
