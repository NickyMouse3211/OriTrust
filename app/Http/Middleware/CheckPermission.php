<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        // Ambil user yang sudah di-inject oleh middleware AuthApi
        $userAll = $request->get('auth_user')['roles'];
        $appCode = env('APP_ID');
        $user = collect($userAll)->firstWhere('apps_code', $appCode);

        if (! $user || empty($user)) {
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
