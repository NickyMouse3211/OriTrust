<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomApiController extends Controller
{
    public function getToken(Request $request)
    {
        $token = $request->query('token'); // ambil nilai dari query string
        session(['api_token' => $token]);
        
        return redirect()->intended('/dashboard');
    }
}
