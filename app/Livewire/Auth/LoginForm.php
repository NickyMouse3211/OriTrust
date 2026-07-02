<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Services\UserApi;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class LoginForm extends Component
{
    public $email;

    public $password;

    public $errorMessage;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:3',
        ]);
        // Kirim ke API Aplikasi User
        $response = UserApi::post('/login', [
            'email' => $this->email,
            'password' => $this->password,
        ]);
        if ($response->failed()) {
            $this->addError('login', 'Email atau password salah');

            return;
        }

        $data = $response->json();

        // 🚫 Jangan simpan user penuh di session
        // ✅ Simpan hanya token
        session(['api_token' => $data['token']]);

        return redirect()->intended('/dashboard');
    }

    public function render()
    {
        return view('livewire.auth.login-form');
    }
}
