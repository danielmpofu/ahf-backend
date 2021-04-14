<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    use SoftDeletes;

    //<--- use the softdelete traits

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'user_type',
        // 'address', 'country', 'gender', 'verified', 'active', 'dob', 'admin', 'bio',
        'email',
        'username',
        // 'last_login',
        'password'
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'instructor_id', 'id');
    }

    public function courseResources()
    {
        return $this->hasMany(CourseResource::class, 'created_by', 'id');
    }

    protected $hidden = ['password', 'remember_token',];

    protected $casts = ['email_verified_at' => 'datetime',];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

}
