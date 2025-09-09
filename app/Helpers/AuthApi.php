<?php

namespace App\Helpers;

class AuthApi
{
    public static function user()
    {
        return request()->get('auth_user');
    }
}
