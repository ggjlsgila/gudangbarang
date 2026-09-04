<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BookFileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Halaman utama (Landing Page)
Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

// =========================
// MASTER DATA
// =========================

// Buku
Route::resource('books', BookController::class);
Route::resource('book-files', BookFileController::class)->only(['index', 'store', 'update', 'destroy']);

// Barang Lainnya
Route::resource('items', ItemController::class);

// =========================
// TRANSAKSI
// =========================
Route::resource('transactions', TransactionController::class);

// =========================
// PROFILE (Opsional, kalau masih mau dipakai)
// =========================
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });
});

require __DIR__.'/auth.php';
