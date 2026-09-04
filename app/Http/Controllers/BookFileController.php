<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookFileController extends Controller
{
    public function index(Request $request)
    {
        $bookFiles = BookFile::with('book')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');
                $query->where('original_name', 'like', "%{$search}%")
                    ->orWhereHas('book', function ($bookQuery) use ($search) {
                        $bookQuery->where('judul_buku', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $books = Book::orderBy('judul_buku')->get(['id', 'judul_buku']);

        return view('book-files.index', compact('bookFiles', 'books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => ['required', 'exists:books,id'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png,webp', 'max:20480'],
        ], [
            'book_id.required' => 'Buku wajib dipilih.',
            'book_id.exists' => 'Buku yang dipilih tidak ditemukan.',
            'file.required' => 'File wajib dipilih.',
            'file.mimes' => 'File harus berupa PDF, Word, atau gambar sampul.',
            'file.max' => 'Ukuran file maksimal 20 MB.',
        ]);

        $file = $request->file('file');
        $path = $file->store('book-files', 'public');

        BookFile::create([
            'book_id' => $validated['book_id'],
            'original_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
        ]);

        return redirect()->route('book-files.index')->with('success', 'File buku berhasil ditambahkan.');
    }

    public function update(Request $request, BookFile $bookFile)
    {
        $validated = $request->validate([
            'book_id' => ['required', 'exists:books,id'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png,webp', 'max:20480'],
        ], [
            'book_id.required' => 'Buku wajib dipilih.',
            'book_id.exists' => 'Buku yang dipilih tidak ditemukan.',
            'file.mimes' => 'File harus berupa PDF, Word, atau gambar sampul.',
            'file.max' => 'Ukuran file maksimal 20 MB.',
        ]);

        $bookFile->book_id = $validated['book_id'];

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($bookFile->file_path);
            $file = $request->file('file');
            $bookFile->original_name = $file->getClientOriginalName();
            $bookFile->file_path = $file->store('book-files', 'public');
            $bookFile->file_size = $file->getSize();
        }

        $bookFile->save();

        return redirect()->route('book-files.index')->with('success', 'File buku berhasil diperbarui.');
    }

    public function destroy(BookFile $bookFile)
    {
        Storage::disk('public')->delete($bookFile->file_path);
        $bookFile->delete();

        return redirect()->route('book-files.index')->with('success', 'File buku berhasil dihapus.');
    }
}
