<?php

use App\Http\Controllers\CustomApiController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagement\RoleController;
use App\Http\Controllers\UserManagement\UserController;
use App\Livewire\Auth\Activation;
use App\Livewire\Auth\LoginForm;
use Illuminate\Support\Facades\Route;

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

    Route::prefix('dashboard')
        ->name('dashboard.')
        ->group(function () {
            Route::controller(DashboardController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/activation', 'activation')->name('activation');
            });
        });

    Route::prefix('profile')
        ->name('profile.')
        ->group(function () {
            Route::controller(ProfileController::class)->group(function () {
                Route::get('/', 'index')->name('index');
            });
        });

    Route::prefix('user-management')
        ->name('user_management.')
        ->group(function () {

            Route::prefix('roles')
                ->name('roles.')
                ->group(function () {
                    Route::controller(RoleController::class)->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/create', 'create')->name('create');
                        Route::get('/get-data', 'getData')->name('get_data');
                        Route::get('/edit/{id}', 'edit')->name('edit');
                        Route::get('/destroy/{id}', 'destroy')->name('destroy');

                        Route::post('/store', 'store')->name('store');
                        Route::post('/update', 'update')->name('update');
                    });
                });

            Route::prefix('users')
                ->name('users.')
                ->group(function () {
                    Route::controller(UserController::class)->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/create', 'create')->name('create');
                        Route::get('/get-data', 'getData')->name('get_data');
                        Route::get('/edit/{id}', 'edit')->name('edit');
                        Route::get('/destroy/{id}', 'destroy')->name('destroy');
                        Route::get('/all-destroy', 'allDestroy')->name('all_destroy');

                        Route::post('/activate', 'activate')->name('activate');
                        Route::post('/store', 'store')->name('store');
                        Route::post('/update', 'update')->name('update');

                        Route::get('/restore/{id}', 'restore')->name('restore');
                        Route::get('/force-delete/{id}', 'forceDelete')->name('force_delete');
                    });
                });
        });

    Route::prefix('global')
        ->name('global.')
        ->group(function () {
            Route::controller(GlobalController::class)
                ->prefix('select2')
                ->name('select2.')
                ->group(function () {
                    Route::get('/basic/{table?}', 'basicSelect2')->name('basic');
                });
        });

    Route::get('/storages/{path}', [GlobalController::class, 'show'])
        ->where('path', '.*');
});

Route::any('/logout', [\App\Http\Controllers\Auth\LogoutController::class, 'logout'])
    ->name('logout');

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

Route::get('/page-404', function () {
    return response()->view('errors.404', [], 404);
})->name('page.404');
