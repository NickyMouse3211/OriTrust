<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class ApiWithUserToken
{
    public function handle(Request $request, Closure $next)
    {
        Http::macro('userApi', function () {
            $token = session('api_token');

            return Http::withToken($token)
                ->baseUrl(config('services.user_api.url'))
                ->withOptions([
                    'on_stats' => function ($transferStats) {
                        // Bisa dipakai debug request/response
                    },
                ]);
        });

        return $next($request);
    }
}
