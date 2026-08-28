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

        $namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $labelGrafik = $bulanGrafik ? [$namaBulan[$bulanGrafik - 1]] : $namaBulan;
        $dataGrafik = $bulanGrafik
            ? array_map(fn ($data) => [$data[$bulanGrafik - 1]], $grafik)
            : $grafik;

        // Kirim semua variabel ke view
        return view('dashboard', compact(
            'totalBuku',
            'totalBarang',
            'totalStokBuku',
            'stokMenipis',
            'latestTransactions',
            'tahunGrafik',
            'bulanGrafik',
            'labelGrafik',
            'dataGrafik'
        ));
    }
}
