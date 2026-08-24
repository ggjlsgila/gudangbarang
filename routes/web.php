<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    // =========================
    // MASTER DATA
    // =========================

    // Buku
    Route::resource('books', BookController::class);

    // Barang Lainnya
    Route::resource('items', ItemController::class);

    // =========================
    // TRANSAKSI
    // =========================

    // Opsi 1: Menggunakan Route Resource (Rekomendasi)
    Route::resource('transactions', TransactionController::class);

    // =========================
    // PROFILE
    // =========================
Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');

    // Route untuk memproses penyimpanan perubahan pengaturan
    Route::patch('/settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
