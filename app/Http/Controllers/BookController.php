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
        ], [
            'kode_buku.required' => 'Kode buku wajib diisi.',
            'kode_buku.unique' => 'Kode buku sudah digunakan.',
            'judul_buku.required' => 'Nama atau judul buku wajib diisi.',
            'stok.required' => 'Stok wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka.',
            'stok.min' => 'Stok tidak boleh kurang dari 0.',
        ]);

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
        ], [
            'kode_buku.required' => 'Kode buku wajib diisi.',
            'kode_buku.unique' => 'Kode buku sudah digunakan.',
            'judul_buku.required' => 'Nama atau judul buku wajib diisi.',
            'stok.required' => 'Stok wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka.',
            'stok.min' => 'Stok tidak boleh kurang dari 0.',
        ]);

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
        $book->delete();

        return redirect()
            ->route('books.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}
