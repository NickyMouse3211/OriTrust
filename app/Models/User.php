<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

    protected $fillable = [
        'name',
        'email',
        'user_code',
        'phone',
        'avatar',
        'gender',
        'birth_date',
        'address',
        'is_active',
        'password',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'remember_token',
    ];
}
