<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\LoginForm;
use App\Services\UserApi;
use App\Http\Controllers\CustomApiController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Auth\Activation;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', LoginForm::class)
    ->name('login')
    ->middleware('auth.api.redirect');

Route::get('/activation', Activation::class)
    ->name('activation');

Route::get('/getApiToken', [CustomApiController::class, 'getToken'])
    ->name('getApiToken')
    ->middleware('auth.api.redirect');

Route::middleware(['auth.api'])->group(function () {
    Route::get('/dashboard', function () {
        $me = UserApi::request('get', '/me')->json();
        return view('dashboard', compact('me'));
    })->middleware('permission:dashboard-list')->name('dashboard'); // proteksi role tetap bisa dipakai

    Route::prefix('profile')
    ->name('profile.')
    ->group(function () {
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/', 'index')->name('index');
        });
    });

    Route::get('/test', function () {
        dd('test');
    })->middleware('permission:dashboard-listo');
});

Route::any('/logout', [\App\Http\Controllers\Auth\LogoutController::class, 'logout'])
    ->name('logout');


Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

Route::get('/page-404', function () {
    return response()->view('errors.404', [], 404);
})->name('page.404');
