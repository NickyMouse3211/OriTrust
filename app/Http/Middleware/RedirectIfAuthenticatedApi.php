<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAuthenticatedApi
{
    public function handle(Request $request, Closure $next)
    {
        // Kalau ada token → user sudah login
        if (session('api_token')) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
