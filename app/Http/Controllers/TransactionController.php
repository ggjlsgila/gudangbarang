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
    $sort = $request->input('sort', 'created_at');
    $direction = strtolower($request->input('direction', 'desc'));
    $allowedSorts = ['tanggal_transaksi'];

    if (!in_array($sort, $allowedSorts)) {
        $sort = 'created_at';
    }

    if (!in_array($direction, ['asc', 'desc'])) {
        $direction = 'desc';
    }

    $jenisTransaksi = $request->input('jenis_transaksi');
    $query = Transaction::query()->with('itemable')->orderBy($sort, $direction);

    if (in_array($jenisTransaksi, ['masuk', 'keluar'])) {
        $query->where('jenis_transaksi', $jenisTransaksi);
    }

    // Logika pencarian
    if ($request->filled('search')) {
        $search = $request->input('search');
        $monthNames = [
            'januari' => 1, 'january' => 1,
            'februari' => 2, 'february' => 2,
            'maret' => 3, 'march' => 3,
            'april' => 4,
            'mei' => 5, 'may' => 5,
            'juni' => 6, 'june' => 6,
            'juli' => 7, 'july' => 7,
            'agustus' => 8, 'augustus' => 8, 'august' => 8,
            'september' => 9,
            'oktober' => 10, 'october' => 10,
            'november' => 11,
            'desember' => 12, 'december' => 12,
        ];
        $month = null;
        $year = null;
        $normalizedSearch = strtolower(trim($search));

        if (preg_match('/^(\d{4})-(\d{1,2})$/', $normalizedSearch, $matches)) {
            $year = (int) $matches[1];
            $month = (int) $matches[2];
        } elseif (preg_match('/^(\d{1,2})[\/-](\d{4})$/', $normalizedSearch, $matches)) {
            $month = (int) $matches[1];
            $year = (int) $matches[2];
        } elseif (preg_match('/^([a-z]+)(?:\s+(\d{4}))?$/', $normalizedSearch, $matches)
            && isset($monthNames[$matches[1]])) {
            $month = $monthNames[$matches[1]];
            $year = isset($matches[2]) ? (int) $matches[2] : null;
        } elseif (preg_match('/^(\d{4})$/', $normalizedSearch, $matches)) {
            $year = (int) $matches[1];
        }

        $validDateSearch = ($month === null || $month >= 1 && $month <= 12)
            && ($year === null || $year >= 1000 && $year <= 9999);

        $query->where(function ($query) use ($search, $month, $year, $validDateSearch) {
            $query->where('kode_transaksi', 'like', "%{$search}%")
                ->orWhere('keterangan', 'like', "%{$search}%")
                ->orWhereHasMorph('itemable', [Book::class, Item::class], function ($itemQuery, $type) use ($search) {
                    if ($type === Book::class) {
                        $itemQuery->where('kode_buku', 'like', "%{$search}%")
                            ->orWhere('judul_buku', 'like', "%{$search}%");
                    } else {
                        $itemQuery->where('kode_barang', 'like', "%{$search}%")
                            ->orWhere('nama_barang', 'like', "%{$search}%");
                    }
                });

            if ($validDateSearch && ($month !== null || $year !== null)) {
                $query->orWhere(function ($dateQuery) use ($month, $year) {
                    if ($month !== null) {
                        $dateQuery->whereMonth('tanggal_transaksi', $month);
                    }

                    if ($year !== null) {
                        $dateQuery->whereYear('tanggal_transaksi', $year);
                    }
                });
            }
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
