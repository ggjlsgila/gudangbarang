<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Menampilkan daftar barang (Tabel + Modal).
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $items = Item::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_barang', 'like', "%{$search}%")
                      ->orWhere('nama_barang', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('items.index', compact('items', 'search'));
    }

    /**
     * Menyimpan barang baru dari Modal Tambah.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:255|unique:items,kode_barang',
            'nama_barang' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'kode_barang.required' => 'Kode barang wajib diisi.',
            'kode_barang.unique' => 'Kode barang sudah digunakan.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'stok.required' => 'Stok wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka.',
            'stok.min' => 'Stok tidak boleh kurang dari 0.',
            'file.mimes' => 'Format file harus berupa PDF, JPG, JPEG, atau PNG.',
            'file.max' => 'Ukuran file maksimal 2 MB.',
        ]);

        if ($request->hasFile('file')) {
            $validated['file'] = $request->file('file')->store('items', 'public');
        }

        Item::create($validated);

        return redirect()
            ->route('items.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Memperbarui data barang dari Modal Edit.
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:255|unique:items,kode_barang,' . $item->id,
            'nama_barang' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'kode_barang.required' => 'Kode barang wajib diisi.',
            'kode_barang.unique' => 'Kode barang sudah digunakan.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'stok.required' => 'Stok wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka.',
            'stok.min' => 'Stok tidak boleh kurang dari 0.',
            'file.mimes' => 'Format file harus berupa PDF, JPG, JPEG, atau PNG.',
            'file.max' => 'Ukuran file maksimal 2 MB.',
        ]);

        if ($request->hasFile('file')) {
            // Hapus file lama jika ada file baru yang diunggah
            if ($item->file && Storage::disk('public')->exists($item->file)) {
                Storage::disk('public')->delete($item->file);
            }

            $validated['file'] = $request->file('file')->store('items', 'public');
        }

        $item->update($validated);

        return redirect()
            ->route('items.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Menghapus data barang dan filenya.
     */
    public function destroy(Item $item)
    {
        // Hapus file dari direktori storage jika ada
        if ($item->file && Storage::disk('public')->exists($item->file)) {
            Storage::disk('public')->delete($item->file);
        }

        $item->delete();

        return redirect()
            ->route('items.index')
            ->with('success', 'Barang berhasil dihapus.');
    }
}
