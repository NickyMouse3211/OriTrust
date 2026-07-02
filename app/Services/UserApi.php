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
     * Base HTTP client.
     */
    protected static function client()
    {
        return Http::withHeaders([
            'Application-ID' => config('services.user_api.application_id'),
        ]);
    }

    /**
     * Request with auto-refresh token.
     */
    public static function request($method, $uri, $data = [])
    {
        $token = Session::get('api_token');

        $response = self::client()
            ->withToken($token)
            ->{$method}(self::baseUrl().$uri, $data);

        // Token expired -> try refresh
        if ($response->status() === 401 && $token) {
            $refresh = self::refreshToken();

            if ($refresh) {
                $newToken = Session::get('api_token');

                $response = self::client()
                    ->withToken($newToken)
                    ->{$method}(self::baseUrl().$uri, $data);
            }
        }

        return $response;
    }

    /**
     * POST request.
     */
    public static function post($uri, $data = [], $token = null)
    {
        $request = self::client();

        if ($token) {
            $request = $request->withToken($token);
        }

        return $request->post(self::baseUrl().$uri, $data);
    }

    /**
     * GET request.
     */
    public static function get($uri, $params = [], $token = null)
    {
        $request = self::client();

        if ($token) {
            $request = $request->withToken($token);
        }

        return $request->get(self::baseUrl().$uri, $params);
    }

    /**
     * PUT request.
     */
    public static function put($uri, $data = [], $token = null)
    {
        $request = self::client();
        if ($token) {
            $request = $request->withToken($token);
        }

        return $request->put(self::baseUrl().$uri, $data);
    }

    /**
     * DELETE request.
     */
    public static function delete($uri, $data = [], $token = null)
    {
        return self::client()
            ->delete(self::baseUrl().$uri, $data);
    }

    /**
     * Refresh API token.
     */
    protected static function refreshToken()
    {
        $oldToken = Session::get('api_token');

        if (! $oldToken) {
            return false;
        }

        $response = self::client()
            ->withToken($oldToken)
            ->post(self::baseUrl().'/refresh');

        if ($response->ok()) {
            $data = $response->json();

            Session::put('api_token', $data['token']);
            Session::put('user', $data['user']);

            return true;
        }

        return false;
    }

    /**
     * Logout from API.
     */
    public static function logout()
    {
        $token = Session::get('api_token');

        if ($token) {
            self::client()
                ->withToken($token)
                ->post(self::baseUrl().'/logout');
        }

        Session::forget([
            'api_token',
            'user',
        ]);
    }
}
