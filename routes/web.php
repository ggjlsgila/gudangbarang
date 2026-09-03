<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// Halaman utama (Landing Page)
Route::get('/', function () {
    return view('welcome');
});

// Dashboard langsung bisa diakses tanpa middleware auth & verified
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

// Semua fitur Master Data & Transaksi dibebaskan dari middleware auth
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
Route::resource('transactions', TransactionController::class);

// =========================
// PROFILE (Opsional, kalau masih mau dipakai)
// =========================
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
