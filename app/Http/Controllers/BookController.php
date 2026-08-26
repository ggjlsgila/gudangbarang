<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Menampilkan daftar buku (Tabel + Modal).
     */
    public function index(Request $request)
{
    $search = $request->input('search');

    $sort = $request->input('sort', 'id');
    $direction = strtolower($request->input('direction', 'desc'));

    $allowedSorts = ['judul_buku', 'stok'];

    if (!in_array($sort, $allowedSorts)) {
        $sort = 'id';
    }

    if (!in_array($direction, ['asc', 'desc'])) {
        $direction = 'desc';
    }

    $books = Book::when($search, function ($query, $search) {
        $query->where(function ($q) use ($search) {
            $q->where('kode_buku', 'like', "%{$search}%")
              ->orWhere('judul_buku', 'like', "%{$search}%");
        });
    })
    ->orderBy($sort, $direction)
    ->paginate(10)
    ->withQueryString();
    
if ($request->ajax()) {
            return view('books.index', compact('books', 'search'))->render();
        }
    return view('books.index', compact('books', 'search'));
}

    /**
     * Menyimpan buku baru dari Modal Tambah.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_buku' => 'required|string|max:255|unique:books,kode_buku',
            'judul_buku' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'kode_buku.required' => 'Kode buku wajib diisi.',
            'kode_buku.unique' => 'Kode buku sudah digunakan.',
            'judul_buku.required' => 'Nama atau judul buku wajib diisi.',
            'stok.required' => 'Stok wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka.',
            'stok.min' => 'Stok tidak boleh kurang dari 0.',
            'file.mimes' => 'Format file harus berupa PDF, JPG, JPEG, atau PNG.',
            'file.max' => 'Ukuran file maksimal 2 MB.',
        ]);

        if ($request->hasFile('file')) {
            $validated['file'] = $request->file('file')->store('books', 'public');
        }

        Book::create($validated);

        return redirect()
            ->route('books.index')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    /**
     * Memperbarui data buku dari Modal Edit.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'kode_buku' => 'required|string|max:255|unique:books,kode_buku,' . $book->id,
            'judul_buku' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'kode_buku.required' => 'Kode buku wajib diisi.',
            'kode_buku.unique' => 'Kode buku sudah digunakan.',
            'judul_buku.required' => 'Nama atau judul buku wajib diisi.',
            'stok.required' => 'Stok wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka.',
            'stok.min' => 'Stok tidak boleh kurang dari 0.',
            'file.mimes' => 'Format file harus berupa PDF, JPG, JPEG, atau PNG.',
            'file.max' => 'Ukuran file maksimal 2 MB.',
        ]);

        if ($request->hasFile('file')) {
            // Hapus file lama jika ada file baru yang diunggah
            if ($book->file && Storage::disk('public')->exists($book->file)) {
                Storage::disk('public')->delete($book->file);
            }

            $validated['file'] = $request->file('file')->store('books', 'public');
        }

        $book->update($validated);

        return redirect()
            ->route('books.index')
            ->with('success', 'Buku berhasil diperbarui.');
    }

    /**
     * Menghapus data buku dan filenya.
     */
    public function destroy(Book $book)
    {
        // Hapus file dari direktori storage jika ada
        if ($book->file && Storage::disk('public')->exists($book->file)) {
            Storage::disk('public')->delete($book->file);
        }

        $book->delete();

        return redirect()
            ->route('books.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}
