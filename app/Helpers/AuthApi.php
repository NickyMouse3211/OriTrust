<?php

namespace App\Helpers;

class AuthApi
{
    public static function user()
    {
        return request()->get('auth_user');
    }

    public static function getPermission()
    {
        $roles = request()->get('auth_user')['roles'] ?? [];
        $appCode = config('app.app_id');

        $permissions = collect($roles)
            ->firstWhere('apps_code', $appCode)['permissions'] ?? [];

        return $permissions;
    }
}
