<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Http;

class AuthApi
{
    /**
     * Base API client.
     */
    protected function apiClient()
    {
        return Http::withHeaders([
            'Application-ID' => config('services.user_api.application_id'),
        ]);
    }

    public function handle($request, Closure $next)
    {
        $token = session('api_token');

        if (!$token) {
            return redirect()->route('login');
        }

        $user = session('user');
        $lastSync = session('user_last_sync');

        $interval = config('services.user_api.sync_interval', 5);

        // Refresh user data if not available or sync interval exceeded
        if (
            !$user ||
            !$lastSync ||
            now()->diffInMinutes($lastSync) >= $interval
        ) {
            $response = $this->apiClient()
                ->withToken($token)
                ->get(config('services.user_api.url') . '/me');

            if ($response->failed()) {
                session()->forget([
                    'api_token',
                    'user',
                    'user_last_sync',
                ]);

                return redirect()
                    ->route('login')
                    ->withErrors([
                        'email' => 'Sesi login sudah berakhir. Silakan login ulang.',
                    ]);
            }

            $user = $response->json();

            session([
                'user' => $user,
                'user_last_sync' => now(),
            ]);
        }

        // Inject authenticated user into request
        $request->merge([
            'auth_user' => $user,
        ]);

        return $next($request);
    }
}