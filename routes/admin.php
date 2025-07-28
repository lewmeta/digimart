<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Use Route prefix for admin routes ::admin_prefix/<route_name>
Route::middleware('guest:admin')
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        Volt::route('login', 'admin.auth.login')
            ->name('login'); // Now this reads as admin.login 

        Volt::route('register', 'admin.auth.register')
            ->name('register'); // Now this reads as admin.register

        Volt::route('forgot-password', 'admin.auth.forgot-password')
            ->name('password.request'); // Now this reads as admin.password.request

        Volt::route('reset-password/{token}', 'admin.auth.reset-password')
            ->name('password.reset');
    });

Route::middleware('auth:admin')
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        Volt::route('verify-email', 'admin.auth.verify-email')
            ->name('verification.notice');

        Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Volt::route('confirm-password', 'admin.auth.confirm-password')
            ->name('password.confirm');

        Route::post('logout', App\Livewire\Admin\Actions\Logout::class)
            ->name('logout');
    });
