<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRoleOrPermission
{
    public function handle(Request $request, Closure $next, ...$values)
    {
        $user = $request->get('auth_user');

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $roles = $user['roles'] ?? [];
        $permissions = $user['permissions'] ?? [];

        foreach ($values as $value) {
            if (in_array($value, $roles) || in_array($value, $permissions)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized');
    }
}
