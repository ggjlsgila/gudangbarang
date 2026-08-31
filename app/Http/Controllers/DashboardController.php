<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tahunGrafik = (int) $request->input('tahun_grafik', now()->year);

        if ($tahunGrafik < 2000 || $tahunGrafik > 2100) {
            $tahunGrafik = now()->year;
        }

        $bulanGrafik = $request->input('bulan_grafik');
        $bulanGrafik = is_numeric($bulanGrafik) && (int) $bulanGrafik >= 1 && (int) $bulanGrafik <= 12
            ? (int) $bulanGrafik
            : null;

        $totalBuku = Book::count();
        $totalBarang = Item::count();
        $totalStokBuku = Book::sum('stok');

        $totalBukuMasuk = Transaction::where('jenis_transaksi', 'masuk')
            ->where('itemable_type', Book::class)
            ->whereYear('tanggal_transaksi', $tahunGrafik)
            ->when($bulanGrafik, fn ($query) => $query->whereMonth('tanggal_transaksi', $bulanGrafik))
            ->sum('jumlah');

        $totalBarangMasuk = Transaction::where('jenis_transaksi', 'masuk')
            ->where('itemable_type', Item::class)
            ->whereYear('tanggal_transaksi', $tahunGrafik)
            ->when($bulanGrafik, fn ($query) => $query->whereMonth('tanggal_transaksi', $bulanGrafik))
            ->sum('jumlah');

        $totalBukuKeluar = Transaction::where('jenis_transaksi', 'keluar')
            ->where('itemable_type', Book::class)
            ->whereYear('tanggal_transaksi', $tahunGrafik)
            ->when($bulanGrafik, fn ($query) => $query->whereMonth('tanggal_transaksi', $bulanGrafik))
            ->sum('jumlah');

        $totalBarangKeluar = Transaction::where('jenis_transaksi', 'keluar')
            ->where('itemable_type', Item::class)
            ->whereYear('tanggal_transaksi', $tahunGrafik)
            ->when($bulanGrafik, fn ($query) => $query->whereMonth('tanggal_transaksi', $bulanGrafik))
            ->sum('jumlah');

        $totalMasuk = $totalBukuMasuk + $totalBarangMasuk;
        $totalKeluar = $totalBukuKeluar + $totalBarangKeluar;

        $stokMenipis = Book::where('stok', '<=', 5)->get();

        $latestTransactions = Transaction::with('itemable')
            ->latest()
            ->take(3)
            ->get();

        // Ringkas transaksi per bulan untuk tahun yang dipilih.
        $ringkasanGrafik = Transaction::query()
            ->selectRaw('MONTH(tanggal_transaksi) as bulan, itemable_type, jenis_transaksi, SUM(jumlah) as total')
            ->whereYear('tanggal_transaksi', $tahunGrafik)
            ->when($bulanGrafik, fn ($query) => $query->whereMonth('tanggal_transaksi', $bulanGrafik))
            ->groupByRaw('MONTH(tanggal_transaksi), itemable_type, jenis_transaksi')
            ->get();

        $grafik = [
            'bukuMasuk' => array_fill(0, 12, 0),
            'bukuKeluar' => array_fill(0, 12, 0),
            'barangMasuk' => array_fill(0, 12, 0),
            'barangKeluar' => array_fill(0, 12, 0),
        ];

        foreach ($ringkasanGrafik as $baris) {
            $indexBulan = (int) $baris->bulan - 1;
            $prefix = $baris->itemable_type === Book::class ? 'buku' : 'barang';
            $jenis = $baris->jenis_transaksi === 'masuk' ? 'Masuk' : 'Keluar';
            $grafik[$prefix . $jenis][$indexBulan] = (int) $baris->total;
        }

        $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $labelGrafik = $bulanGrafik ? [$namaBulan[$bulanGrafik - 1]] : $namaBulan;
        $labelBulanGrafik = $bulanGrafik ? $namaBulan[$bulanGrafik - 1] : 'Semua Bulan';
        $dataGrafik = $bulanGrafik
            ? array_map(fn ($data) => [$data[$bulanGrafik - 1]], $grafik)
            : $grafik;

        $chartValues = [$totalBukuMasuk, $totalBukuKeluar, $totalBarangMasuk, $totalBarangKeluar];
        $chartHasData = array_sum($chartValues) > 0;
        $chartData = $chartHasData
            ? [
                'labels' => ['Buku Masuk', 'Buku Keluar', 'Barang Lainnya Masuk', 'Barang Lainnya Keluar'],
                'datasets' => [[
                    'data' => $chartValues,
                    'backgroundColor' => [
                        'rgba(16, 185, 129, 0.85)',
                        'rgba(239, 68, 68, 0.85)',
                        'rgba(59, 130, 246, 0.85)',
                        'rgba(249, 115, 22, 0.85)',
                    ],
                    'borderColor' => '#ffffff',
                    'borderWidth' => 3,
                ]],
            ]
            : [
                'labels' => ['Belum ada transaksi'],
                'datasets' => [[
                    'data' => [1],
                    'backgroundColor' => ['#ffffff'],
                    'borderColor' => ['#0f172a'],
                    'borderWidth' => 2,
                ]],
            ];

        // Kirim semua variabel ke view
        return view('dashboard', compact(
            'totalBuku',
            'totalBarang',
            'totalStokBuku',
            'totalBukuMasuk',
            'totalBarangMasuk',
            'totalMasuk',
            'totalBukuKeluar',
            'totalBarangKeluar',
            'totalKeluar',
            'stokMenipis',
            'latestTransactions',
            'tahunGrafik',
            'bulanGrafik',
            'labelGrafik',
            'labelBulanGrafik',
            'dataGrafik',
            'chartHasData',
            'chartData'
        ));
    }
}
