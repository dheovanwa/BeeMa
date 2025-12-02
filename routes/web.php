<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Guest routes (not authenticated)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dosen routes - only accessible by users with role 'dosen'
    Route::middleware('role:dosen')->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/dashboard', [DosenController::class, 'dashboard'])->name('dashboard');
    });
    
    // Mahasiswa routes - only accessible by users with role 'mahasiswa'
    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('dashboard');
    });
});
