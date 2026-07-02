<?php

namespace App\Helpers;

use App\Services\UserApi;

class Helper
{
    public static function parsing_alert($message)
    {
        $string = '';
        if (is_array($message)) {
            foreach ($message as $key => $value) {
                $string .= ucfirst($value).'<br>';
            }
        } else {
            $string = ucfirst($message);
        }

        return $string;
    }

    public static function getRoles($userCode = null): array
    {
        $token = session('api_token');

        if (! $token) {
            return [];
        }

        $response = UserApi::post('/getRoles', ['user' => $userCode], $token);

        if ($response->failed()) {
            return [];
        }
        $roles = data_get($response->json(), 'data', []);

        return is_array($roles) ? $roles : [];
    }

    public static function setRoles($userCode = null, $roles = []): array
    {
        $token = session('api_token');

        if (! $token) {
            return [];
        }
        $response = UserApi::post('/setRoles', ['executor' => AuthApi::user()['user_code'], 'user' => $userCode, 'roles' => $roles], $token);

        if ($response->failed()) {
            return [];
        }

        $roles = data_get($response->json(), 'data', []);

        return is_array($roles) ? $roles : [];
    }

    public static function registerUser($name = '', $email = '', $password = '', $roles = []): array
    {
        $token = session('api_token');

        if (! $token) {
            return [];
        }
        $response = UserApi::post('/register', ['executor' => AuthApi::user()['user_code'], 'name' => $name, 'email' => $email, 'password' => $password, 'roles' => $roles], $token);

        $data = $response->json();

        return $data;
    }

    public static function staticValue($arrayname, $variable)
    {
        $data['maximum']['upload_size'] = 10;

        return @$data[$arrayname][$variable] != '' ? $data[$arrayname][$variable] : false;
    }
}
