<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->get('auth_user');
        
        if (!$user || empty($user['roles'])) {
            abort(403, 'Unauthorized');
        }

        // cek apakah user punya salah satu role
        foreach ($roles as $role) {
            if (in_array($role, $user['roles'])) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized');
    }
}
