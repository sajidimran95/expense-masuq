<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.store');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', fn () => redirect()->route('admin.dashboard'))->name('home');
        Route::get('dashboard', DashboardController::class)->name('dashboard');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });
});
