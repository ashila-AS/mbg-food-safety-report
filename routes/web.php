<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route(auth()->check() ? 'dashboard' : 'login');
});

Route::middleware(['guest', 'no-cache'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware(['auth', 'no-cache'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
});

Route::middleware(['auth', 'no-cache', 'role:pelapor'])->group(function () {
    Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/buat', [ReportController::class, 'create'])->name('laporan.create');
    Route::post('/laporan', [ReportController::class, 'store'])->name('laporan.store');
});

Route::middleware(['auth', 'no-cache'])->group(function () {
    Route::get('/laporan/{laporan}', [ReportController::class, 'show'])
        ->whereNumber('laporan')
        ->name('laporan.show');
    Route::get('/laporan/{laporan}/bukti', [ReportController::class, 'evidence'])
        ->whereNumber('laporan')
        ->name('laporan.bukti');
});

Route::middleware(['auth', 'no-cache', 'role:pemantau,koordinator'])->group(function () {
    Route::get('/tinjauan', [ReviewController::class, 'index'])->name('tinjauan.index');
});