<?php
use App\Helpers\AuthApi;

if (!function_exists('authUser')) {
    function authUser(string $key = null)
    {
        $user = request()->get('auth_user');
        if (!$user) {
            return null;
        }

        return $key ? ($user[$key] ?? null) : $user;
    }
}

if (! function_exists('auth_api_user')) {
    function auth_api_user()
    {
        return AuthApi::user();
    }
}
