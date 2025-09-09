<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\LoginForm;
use App\Services\UserApi;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', LoginForm::class)
    ->name('login')
    ->middleware('auth.api.redirect');

Route::middleware(['auth.api'])->group(function () {
    Route::get('/dashboard', function () {
        $me = UserApi::request('get', '/me')->json();
        return view('dashboard', compact('me'));
    })->middleware('permission:dashboard-list')->name('dashboard'); // proteksi role tetap bisa dipakai

    Route::get('/test', function () {
        dd('test');
    })->middleware('permission:dashboard-listo');
});

Route::post('/logout', [\App\Http\Controllers\Auth\LogoutController::class, 'logout'])
    ->name('logout');
