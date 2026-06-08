<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;

#[Layout('components.layouts.auth')]
class Activation extends Component
{
    public string $key = '';

    /**
     * Handle an incoming registration request.
     */
    public function activation(): void
    {
        $validated = $this->validate([
            'key' => ['required', 'string'],
        ]);

        try{
            $key = $validated['key'];

            $response = Http::post(config('services.user_api.url') . '/activate', [
                'app_name' => env('APP_NAME'),
                'initial_id' => env('APP_INSTANCE_ID'),
                'activation_code' => $key,
            ]);

            $data = $response->json();
            if (isset($data['status']) && $data['status'] === 'success') {
                $this->setEnvValue('APP_ACTIVATED_CODE', $data['data']['activated_code']);
                config(['app.activated_code' => $data['data']['activated_code']]);
                $this->setEnvValue('APP_ACTIVATED_DATE', $data['data']['activation_date']);
                config(['app.activated_date' => $data['data']['activation_date']]);
                $this->redirect(route('login'));
            } else {
                throw ValidationException::withMessages([
                    'activation' => $data['message'] ?? 'Activation failed. Please check your details and try again.',
                ]);
            }

        } catch (\Exception $e) {
            dd($e->getMessage());
            throw ValidationException::withMessages([
                'activation' => 'Activation failed. Please check your details and try again.',
            ]);
        }
    }

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
