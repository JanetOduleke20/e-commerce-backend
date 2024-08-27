<?php

use App\Http\Controllers\AdminAuth\AuthenticatedSessionController;
use App\Http\Controllers\AdminAuth\RegisteredAdminController;
use App\Http\Middleware\CheckGuest;
use App\Http\Middleware\CheckIfAdminIsHeadAdmin;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get('register', [RegisteredAdminController::class, 'create'])
                ->name('admin.register')->middleware(CheckIfAdminIsHeadAdmin::class) ;

    Route::post('register', [RegisteredAdminController::class, 'store'])->middleware(CheckIfAdminIsHeadAdmin::class) ;

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('admin.login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

});

Route::prefix('admin')->middleware(CheckGuest::class)->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('admin.logout');
});

