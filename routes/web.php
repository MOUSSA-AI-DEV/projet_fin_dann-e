<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminUserController;
use \App\Http\Controllers\Admin\DashboardController;
use \App\Http\Controllers\Admin\MarqueController;
use \App\Http\Controllers\Admin\CategoryController;
use \App\Http\Controllers\Admin\PieceController;
use \App\Http\Controllers\Admin\ReferenceController;
use \App\Http\Controllers\Admin\VoitureController;
use \App\Http\Controllers\Admin\ReferenceVoitureController;     
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
        Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');

        Route::prefix('admin')->name('admin.')->group(function () {
            Route::resource('users', AdminUserController::class);
            Route::patch('users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
            
            Route::resource('marques',MarqueController::class);
            Route::resource('categories', CategoryController::class);
            Route::resource('pieces', PieceController::class);
            Route::resource('references', ReferenceController::class);
            Route::resource('voitures', VoitureController::class);
            Route::post('references/{reference}/voitures', [ReferenceVoitureController::class, 'attachVoiture'])->name('references.voitures.attach');
            Route::delete('references/{reference}/voitures/{voiture}', [ReferenceVoitureController::class, 'detachVoiture'])->name('references.voitures.detach');
        });
    });
});

