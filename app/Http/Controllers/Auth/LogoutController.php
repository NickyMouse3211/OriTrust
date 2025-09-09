<?php

namespace App\Http\Controllers\Auth;

use App\Services\UserApi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout()
    {
        UserApi::logout();
        Auth::logout();
        return redirect('/login');
    }
}
