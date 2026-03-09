<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', function () {
            return 'Panel Administrateur';
        })->name('admin.dashboard');

        Route::prefix('admin')->name('admin.')->group(function () {
            Route::resource('users', AdminUserController::class);
            Route::patch('users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
        });
    });
});

