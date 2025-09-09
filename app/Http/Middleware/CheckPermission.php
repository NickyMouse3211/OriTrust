<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        // Ambil user yang sudah di-inject oleh middleware AuthApi
        $user = $request->get('auth_user');

        if (!$user || empty($user['permissions'])) {
            abort(403, 'Unauthorized');
        }

        foreach ($permissions as $perm) {
            if (in_array($perm, $user['permissions'])) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized');
    }
}
