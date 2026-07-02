<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RedirectIfAuthenticatedApi
{
    public function handle(Request $request, Closure $next)
    {
        if (! env('APP_ACTIVATED_CODE')) {
            $response = Http::post(config('services.user_api.url').'/storeInitialID', [
                'app_name' => env('APP_NAME'),
                'initial_id' => env('APP_INSTANCE_ID'),
            ]);

            return redirect()->route('activation');
        }
        // Kalau ada token → user sudah login
        if (session('api_token')) {
            return redirect()->route('dashboard.index');
        }

        return $next($request);
    }
}
