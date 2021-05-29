<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInvitation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var string
     */

    public static $inv_system = 'SYSTEM';
    public static $inv_course = 'COURSE';
    public static $inv_resource = 'RESOURCE';

    protected $fillable = [
        'key',
        'user_id',
        'invited_by',
        'first_name',
        'last_name',
        'email',
        'email_status',
        'status',
        'expiry',
        'invited_to',
        'revoked',
        'message',
        'user_role',
        'invited_by'
    ];

    public static function generateKey()
    {
        return substr(str_shuffle(md5(date('D, d M Y H:i:s'))),1,8);
    }

}
