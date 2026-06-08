<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // 👉 Role directive
        Blade::if('role', function ($role) {
            $roles = authUser('roles') ?? [];
            return in_array($role, $roles);
        });

        // 👉 Permission directive
        Blade::if('permission', function ($perm) {
            $permissions = authUser('permissions') ?? [];
            return in_array($perm, $permissions);
        });

        // 👉 Role or Permission directive
        Blade::if('roleOrPermission', function ($value) {
            $roles = authUser('roles') ?? [];
            $permissions = authUser('permissions') ?? [];
            return in_array($value, $roles) || in_array($value, $permissions);
        });

        // 👉 Pastikan APP_INSTANCE_ID ada di .env
        if (! env('APP_INSTANCE_ID')) {
            $instanceId = (string) Str::uuid();
            $this->setEnvValue('APP_INSTANCE_ID', $instanceId);
            config(['app.instance_id' => $instanceId]);
            $this->setEnvValue('APP_NAME', 'Originality');
            config(['app.name' => 'Originality']);
        } else {
            config(['app.instance_id' => env('APP_INSTANCE_ID')]);
        }
        
    }

    /**
     * Helper untuk update atau tambah key di .env
     */
    private function setEnvValue(string $key, string $value): void
    {
        $path = base_path('.env');
        if (! file_exists($path)) {
            return;
        }

        $env = file_get_contents($path);
        $escaped = preg_quote($key, '/');

        if (preg_match("/^{$escaped}=.*/m", $env)) {
            $env = preg_replace("/^{$escaped}=.*/m", "{$key}=\"{$value}\"", $env);
        } else {
            $env .= "\n{$key}=\"{$value}\"";
        }

        file_put_contents($path, $env);
    }
}