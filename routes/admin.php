<?php

use App\Http\Controllers\AdminAuth\AuthenticatedSessionController;
use App\Http\Controllers\AdminAuth\RegisteredAdminController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Middleware\CheckGuest;
use App\Http\Middleware\CheckIfAdminIsHeadAdmin;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get('register', [RegisteredAdminController::class, 'create'])
                ->name('admin.register')->middleware(CheckIfAdminIsHeadAdmin::class) ;

    Route::post('register', [RegisteredAdminController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('admin.login');

    Route::get('get', [AuthenticatedSessionController::class, 'getAdmin']);
                
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
                
});

Route::prefix('admin')->middleware(CheckGuest::class)->group(function () {
    Route::put('password', [PasswordController::class, 'updateAdminPassword'])->name('admin.password.update');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('admin.logout');
});

