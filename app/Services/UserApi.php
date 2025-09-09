<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class UserApi
{
    protected static function baseUrl()
    {
        return config('services.user_api.url');
    }

    /**
     * Request dengan auto-refresh token.
     */
    public static function request($method, $uri, $data = [])
    {
        $token = Session::get('api_token');

        $response = Http::withToken($token)->{$method}(self::baseUrl() . $uri, $data);

        // Kalau token expired → coba refresh
        if ($response->status() === 401 && $token) {
            $refresh = self::refreshToken();

            if ($refresh) {
                $newToken = Session::get('api_token');
                $response = Http::withToken($newToken)->{$method}(self::baseUrl() . $uri, $data);
            }
        }

        return $response;
    }

    /**
     * POST request sederhana (tanpa auto-refresh, biasanya untuk login).
     */
    public static function post($uri, $data = [])
    {
        return Http::post(self::baseUrl() . $uri, $data);
    }

    /**
     * GET request sederhana (tanpa auto-refresh).
     */
    public static function get($uri, $params = [])
    {
        return Http::get(self::baseUrl() . $uri, $params);
    }

    /**
     * Refresh token API user.
     */
    protected static function refreshToken()
    {
        $oldToken = Session::get('api_token');

        if (!$oldToken) {
            return false;
        }

        $refresh = Http::withToken($oldToken)->post(self::baseUrl() . '/refresh');

        if ($refresh->ok()) {
            $data = $refresh->json();
            Session::put('api_token', $data['token']);
            Session::put('user', $data['user']);
            return true;
        }

        return false;
    }

    /**
     * Logout dari API user.
     */
    public static function logout()
    {
        $token = Session::get('api_token');

        if ($token) {
            Http::withToken($token)->post(self::baseUrl() . '/logout');
        }

        Session::forget(['api_token', 'user']);
    }
}
