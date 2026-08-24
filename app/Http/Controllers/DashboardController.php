<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBuku = Book::count();
        $totalBarang = Item::count();
        $totalStokBuku = Book::sum('stok');

        $stokMenipis = Book::where('stok', '<=', 5)->get();

        $latestTransactions = Transaction::with('itemable')
            ->latest()
            ->take(3)
            ->get();

        // ==========================================
        // QUERY UNTUK GRAFIK / DIAGRAM
        // ==========================================

        // Transaksi Buku (Masuk & Keluar)
        $bukuMasuk = Transaction::where('jenis_transaksi', 'masuk')
            ->where('itemable_type', Book::class)
            ->sum('jumlah');

        $bukuKeluar = Transaction::where('jenis_transaksi', 'keluar')
            ->where('itemable_type', Book::class)
            ->sum('jumlah');

        // Transaksi Barang Lainnya (Masuk & Keluar)
        $barangMasuk = Transaction::where('jenis_transaksi', 'masuk')
            ->where('itemable_type', Item::class)
            ->sum('jumlah');

        $barangKeluar = Transaction::where('jenis_transaksi', 'keluar')
            ->where('itemable_type', Item::class)
            ->sum('jumlah');

        // Kirim semua variabel ke view
        return view('dashboard', compact(
            'totalBuku',
            'totalBarang',
            'totalStokBuku',
            'stokMenipis',
            'latestTransactions',
            'bukuMasuk',
            'bukuKeluar',
            'barangMasuk',
            'barangKeluar'
        ));
    }
}
