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
    Route::get('/laporan/{laporan}/edit', [ReportController::class, 'edit'])
        ->whereNumber('laporan')
        ->name('laporan.edit');
    Route::put('/laporan/{laporan}', [ReportController::class, 'update'])
        ->whereNumber('laporan')
        ->name('laporan.update');
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

Route::middleware(['auth', 'no-cache', 'role:pemantau'])->group(function () {
    Route::post('/laporan/{laporan}/mulai-tinjau', [ReviewController::class, 'start'])
        ->whereNumber('laporan')
        ->name('laporan.mulai-tinjau');
    Route::post('/laporan/{laporan}/catatan', [ReviewController::class, 'addNote'])
        ->whereNumber('laporan')
        ->name('laporan.catatan');
    Route::post('/laporan/{laporan}/kembalikan', [ReviewController::class, 'sendBack'])
        ->whereNumber('laporan')
        ->name('laporan.kembalikan');
    Route::post('/laporan/{laporan}/tandai', [ReviewController::class, 'flag'])
        ->whereNumber('laporan')
        ->name('laporan.tandai');
});