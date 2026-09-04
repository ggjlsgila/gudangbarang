@extends('layouts.app')

@section('content')
    <div class="space-y-4 sm:space-y-6 antialiased">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">File Buku</h1>
                <p class="text-xs sm:text-sm font-medium text-slate-500">Kelola file yang terkait dengan data buku</p>
            </div>
            <button type="button" onclick="openBookFileModal()"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs sm:text-sm font-bold text-white shadow-sm shadow-indigo-200 transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 w-full sm:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah File</span>
            </button>
        </div>

        @if (session('success'))
            <div
                class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs sm:text-sm font-semibold text-emerald-900 shadow-sm">
                {{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div
                class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs sm:text-sm font-semibold text-rose-900 shadow-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
            <div class="border-b border-slate-100 bg-slate-50/50 p-3 sm:p-4">
                <form method="GET" action="{{ route('book-files.index') }}" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari judul buku atau nama file..." autocomplete="off"
                        class="w-full rounded-xl border-slate-300 text-xs sm:text-sm font-medium text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500">
                    <button type="submit"
                        class="shrink-0 rounded-xl bg-indigo-600 px-4 py-2 text-xs sm:text-sm font-bold text-white shadow-sm transition hover:bg-indigo-700">Cari</button>
                    @if (request('search'))
                        <a href="{{ route('book-files.index') }}"
                            class="inline-flex shrink-0 items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-xs sm:text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Reset</a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full table-fixed text-left text-xs sm:text-sm">
                    <thead
                        class="border-b border-indigo-100/60 bg-indigo-50/50 text-[10px] font-bold uppercase tracking-wider text-slate-900 sm:text-xs">
                        <tr>
                            <th class="w-[12%] px-3 py-3.5 text-center sm:w-[8%] sm:px-4">No</th>
                            <th class="w-[52%] px-3 py-3.5 sm:w-[55%] sm:px-4">Judul Buku</th>
                            <th class="w-[26%] px-3 py-3.5 sm:w-[27%] sm:px-4">File</th>
                            <th class="w-[10%] px-2 py-3.5 text-center sm:w-[10%] sm:px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($bookFiles as $index => $bookFile)
                            <tr class="transition hover:bg-indigo-50/30">
                                <td class="px-3 py-3.5 text-center font-medium text-slate-400 sm:px-4">
                                    {{ $bookFiles->firstItem() + $index }}</td>
                                <td class="truncate px-3 py-3.5 font-bold text-slate-900 sm:px-4">
                                    {{ $bookFile->book->judul_buku ?? 'Buku telah dihapus' }}</td>
                                <td class="truncate px-3 py-3.5 font-medium text-slate-700 sm:px-4"
                                    title="{{ $bookFile->original_name }}">{{ $bookFile->original_name }}</td>
                                <td class="whitespace-nowrap px-1 py-3.5 text-center sm:px-4">
                                    <div class="relative inline-block text-left">
                                        <button type="button" title="Menu Aksi" onclick="toggleBookFileMenu(event, this)"
                                            class="inline-flex cursor-pointer items-center justify-center rounded-lg border border-slate-200 p-1.5 text-slate-600 transition hover:border-slate-300 hover:bg-slate-100 hover:text-indigo-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor" class="h-4 w-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                            </svg>
                                        </button>
                                        <div
                                            class="book-file-menu fixed z-[9999] hidden w-32 rounded-xl border border-slate-200 bg-white py-1 text-left shadow-lg">
                                            <a href="{{ Storage::url($bookFile->file_path) }}" target="_blank"
                                                onclick="closeBookFileMenus()"
                                                class="block px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 hover:text-indigo-600">Lihat</a>
                                            <a href="{{ Storage::url($bookFile->file_path) }}"
                                                download="{{ $bookFile->original_name }}" onclick="closeBookFileMenus()"
                                                class="block px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 hover:text-indigo-600">Download</a>
                                            <button type="button"
                                                onclick="closeBookFileMenus(); openEditBookFileModal('{{ route('book-files.update', $bookFile) }}', '{{ $bookFile->book_id }}')"
                                                class="block w-full px-3 py-2 text-left text-xs font-semibold text-slate-700 transition hover:bg-slate-50 hover:text-indigo-600">Edit</button>
                                            <form method="POST" action="{{ route('book-files.destroy', $bookFile) }}"
                                                onsubmit="return confirm('Hapus file ini?')" class="m-0 block p-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="block w-full px-3 py-2 text-left text-xs font-semibold text-rose-600 transition hover:bg-rose-50">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-12 text-center">
                                    <div class="text-3xl">📄</div>
                                    <p class="mt-2 text-xs font-bold text-slate-800 sm:text-sm">Belum ada file buku</p>
                                    <p class="mt-0.5 text-xs font-medium text-slate-400">Tambahkan file dan hubungkan dengan
                                        buku.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($bookFiles->hasPages())
                <div class="border-t border-slate-100 bg-slate-50/50 px-4 py-3">{{ $bookFiles->links() }}</div>
            @endif
        </div>
    </div>

    <div id="bookFileModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50" onclick="closeBookFileModal()">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative w-full max-w-md rounded-2xl border border-slate-100 bg-white p-6 shadow-2xl"
                onclick="event.stopPropagation()">
                <div class="mb-4 flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 id="bookFileModalTitle" class="text-base font-bold text-slate-900">Tambah File Buku</h3>
                    <button type="button" onclick="closeBookFileModal()"
                        class="rounded-lg p-1 text-slate-400 transition hover:text-slate-600">✕</button>
                </div>
                <form id="bookFileForm" action="{{ route('book-files.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input id="bookFileMethod" type="hidden" name="_method" value="POST">
                    <div class="space-y-4">
                        <div>
                            <label for="book_id"
                                class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Pilih
                                Buku</label>
                            <select id="book_id" name="book_id" required placeholder="Cari atau pilih buku..."
                                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                <option value="">-- Cari / Pilih Buku --</option>
                                @foreach ($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->judul_buku }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="file"
                                class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Pilih
                                File</label>
                            <input id="file" type="file" name="file"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp"
                                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-indigo-700" />
                            <p class="mt-1 text-[11px] font-medium text-slate-400">PDF, Word, atau gambar sampul. Maksimal
                                20 MB.</p>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-2 border-t border-slate-100 pt-4">
                        <button type="button" onclick="closeBookFileModal()"
                            class="rounded-xl bg-slate-100 px-4 py-2 text-xs font-bold text-slate-700 transition hover:bg-slate-200">Batal</button>
                        <button type="submit"
                            class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white shadow-md shadow-indigo-200 transition hover:bg-indigo-700">Simpan
                            File</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function closeBookFileMenus() {
            document.querySelectorAll('.book-file-menu').forEach(function(menu) {
                menu.classList.add('hidden');
            });
        }

        function toggleBookFileMenu(event, button) {
            event.stopPropagation();

            const menu = button.nextElementSibling;
            const isOpen = !menu.classList.contains('hidden');
            closeBookFileMenus();

            if (isOpen) {
                return;
            }

            const rect = button.getBoundingClientRect();
            const menuHeight = 180;
            const top = window.innerHeight - rect.bottom < menuHeight ?
                rect.top - menuHeight :
                rect.bottom + 8;
            const left = Math.max(8, Math.min(rect.right - 128, window.innerWidth - 136));

            menu.style.top = `${top}px`;
            menu.style.left = `${left}px`;
            menu.classList.remove('hidden');
        }

        document.addEventListener('click', closeBookFileMenus);

        let bookFileSelect = null;

        document.addEventListener('DOMContentLoaded', function() {
            bookFileSelect = new TomSelect('#book_id', {
                create: false,
                allowEmptyOption: true,
                searchField: ['text'],
                maxOptions: 50,
                closeAfterSelect: true,
                onInitialize: function() {
                    this.clear(true);
                }
            });
        });

        function openBookFileModal() {
            document.getElementById('bookFileModalTitle').textContent = 'Tambah File Buku';
            document.getElementById('bookFileForm').action = @js(route('book-files.store'));
            document.getElementById('bookFileMethod').value = 'POST';
            if (bookFileSelect) {
                bookFileSelect.clear(true);
            }
            document.getElementById('file').required = true;
            document.getElementById('bookFileModal').classList.remove('hidden');
        }

        function openEditBookFileModal(action, bookId) {
            document.getElementById('bookFileModalTitle').textContent = 'Edit File Buku';
            document.getElementById('bookFileForm').action = action;
            document.getElementById('bookFileMethod').value = 'PUT';
            if (bookFileSelect) {
                bookFileSelect.setValue(bookId, true);
            } else {
                document.getElementById('book_id').value = bookId;
            }
            document.getElementById('file').required = false;
            document.getElementById('bookFileModal').classList.remove('hidden');
        }

        function closeBookFileModal() {
            document.getElementById('bookFileModal').classList.add('hidden');
        }
    </script>
@endsection
