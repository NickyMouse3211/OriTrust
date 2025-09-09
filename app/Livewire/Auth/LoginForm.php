<?php
namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\UserApi;

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
            $this->errorMessage = "Email atau password salah";
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
