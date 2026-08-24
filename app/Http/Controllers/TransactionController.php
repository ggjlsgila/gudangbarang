<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Book;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- Ditambahkan agar DB::transaction tidak error

class TransactionController extends Controller
{
public function index(Request $request)
{
    // Query dasar transaksi
    $query = Transaction::query()->with('itemable')->latest();

    // Logika pencarian
    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('kode_transaksi', 'like', "%{$search}%")
              ->orWhere('keterangan', 'like', "%{$search}%");
        });
    }

    $transactions = $query->paginate(10)->withQueryString();

    // Ambil data untuk modal tambah (buku & item)
    $books = Book::all();
    $items = Item::all();

    // --- BAGIAN AJAX YANG DIPERBAIKI ---
    if ($request->ajax()) {
        // Kirim semua variabel agar modal tidak error saat HTML-nya dirender ulang
        return view('transactions.index', compact('transactions', 'books', 'items'))->render();
    }

    return view('transactions.index', compact('transactions', 'books', 'items'));
}

    /**
     * Menyimpan data transaksi baru dan mengupdate stok
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_transaksi'   => 'required|in:masuk,keluar',
            'kategori'          => 'required|in:buku,item',
            'item_id'           => 'required|integer',
            'jumlah'            => 'required|integer|min:1',
            'tanggal_transaksi' => 'required|date',
            'keterangan'        => 'nullable|string',
        ]);

        $itemClass = $request->kategori === 'buku' ? Book::class : Item::class;
        $item = $itemClass::findOrFail($request->item_id);

        if ($request->jenis_transaksi === 'keluar' && $item->stok < $request->jumlah) {
            return back()->withInput()->with('error', 'Stok tidak mencukupi! Stok saat ini: ' . $item->stok);
        }

        DB::transaction(function () use ($request, $itemClass, $item) {
            $prefix = $request->jenis_transaksi === 'masuk' ? 'TRX-IN' : 'TRX-OUT';
            $kodeTransaksi = $prefix . '-' . date('YmdHis') . '-' . rand(100, 999);

            Transaction::create([
                'kode_transaksi'    => $kodeTransaksi,
                'jenis_transaksi'   => $request->jenis_transaksi,
                'itemable_type'     => $itemClass,
                'itemable_id'       => $request->item_id,
                'jumlah'            => $request->jumlah,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'keterangan'        => $request->keterangan,
            ]);

            if ($request->jenis_transaksi === 'masuk') {
                $item->increment('stok', $request->jumlah);
            } else {
                $item->decrement('stok', $request->jumlah);
            }
        });

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dicatat!');
    }

    /**
     * Mengubah data transaksi & mengkalkulasi ulang selisih stok
     */
    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'jenis_transaksi'   => 'required|in:masuk,keluar',
            'jumlah'            => 'required|integer|min:1',
            'tanggal_transaksi' => 'required|date',
            'keterangan'        => 'nullable|string',
        ]);

        $item = $transaction->itemable;

        try {
            DB::transaction(function () use ($request, $transaction, $item) {
                // Revert stok sebelum transaksi diubah
                if ($item) {
                    if ($transaction->jenis_transaksi === 'masuk') {
                        $item->decrement('stok', $transaction->jumlah);
                    } else {
                        $item->increment('stok', $transaction->jumlah);
                    }

                    $item->refresh();

                    if ($request->jenis_transaksi === 'keluar' && $item->stok < $request->jumlah) {
                        throw new \Exception('Stok tidak mencukupi! Stok tersedia: ' . $item->stok);
                    }

                    // Terapkan stok berdasarkan perubahan baru
                    if ($request->jenis_transaksi === 'masuk') {
                        $item->increment('stok', $request->jumlah);
                    } else {
                        $item->decrement('stok', $request->jumlah);
                    }
                }

                $transaction->update([
                    'jenis_transaksi'   => $request->jenis_transaksi,
                    'jumlah'            => $request->jumlah,
                    'tanggal_transaksi' => $request->tanggal_transaksi,
                    'keterangan'        => $request->keterangan,
                ]);
            });
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui!');
    }

    /**
     * Menghapus transaksi & mengembalikan stok ke kondisi semula
     */
    public function destroy(Transaction $transaction)
    {
        DB::transaction(function () use ($transaction) {
            $item = $transaction->itemable;

            if ($item) {
                if ($transaction->jenis_transaksi === 'masuk') {
                    $item->decrement('stok', $transaction->jumlah);
                } else {
                    $item->increment('stok', $transaction->jumlah);
                }
            }

            $transaction->delete();
        });

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus dan stok disesuaikan!');
    }

    /**
     * Menampilkan detail transaksi
     */
public function show(Transaction $transaction)
{
    $transaction->load('itemable');

    if (request()->wantsJson()) {
        return response()->json($transaction);
    }

    return redirect()->route('transactions.index');
}
}
