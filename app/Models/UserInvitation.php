<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class invitation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var string
     */

    public static $inv_system = 'SYSTEM';
    public  static  $inv_course = 'COURSE';
    public  static  $inv_resource = 'RESOURCE';
}
