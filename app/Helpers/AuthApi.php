<?php

namespace App\Helpers;

class AuthApi
{
    public static function user()
    {
        return request()->get('auth_user');
    }

    public static function getPermission($specificPermission = null)
    {
        $roles = request()->get('auth_user')['roles'] ?? [];
        $appCode = config('app.app_id');

        // $permissions = collect($roles)->firstWhere('apps_code', $appCode)['permissions'] ?? [];
        $permissions = request()->get('auth_user')['permissions'] ?? [];

        if ($specificPermission) {
            return in_array($specificPermission, $permissions);
        }

        return $permissions;
    }
}
