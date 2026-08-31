@extends('layouts.app')

@section('content')
    <div class="space-y-4 sm:space-y-6 antialiased">

        {{-- Header --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                    Data Buku
                </h1>
                <p class="text-xs sm:text-sm font-medium text-slate-500">
                    Kelola data katalog dan stok buku inventaris
                </p>
            </div>

            {{-- Tombol Buka Modal Tambah --}}
            <button type="button" onclick="openTambahModal()"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs sm:text-sm font-bold text-white shadow-sm shadow-indigo-200 transition hover:bg-indigo-700 w-full sm:w-auto cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Buku</span>
            </button>
        </div>

        {{-- Pesan Sukses --}}
        @if (session('success'))
            <div
                class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs sm:text-sm font-semibold text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tabel Container --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">

            {{-- Search Form --}}
            <div class="border-b border-slate-100 p-3 sm:p-4">
                <form method="GET" action="{{ route('books.index') }}" class="flex gap-2" id="bookSearchForm">

                    <input type="text" name="search" id="bookSearch" value="{{ request('search') }}"
                        placeholder="Cari kode, ISBN, atau judul buku..." autocomplete="off"
                        class="w-full rounded-xl border-slate-300 text-xs sm:text-sm font-medium text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500">

                    <button type="submit"
                        class="rounded-xl bg-indigo-600 px-4 py-2 text-xs sm:text-sm font-bold text-white hover:bg-indigo-700 transition shrink-0">
                        Cari
                    </button>

                    <a href="{{ route('books.index') }}" id="btnReset"
                        class="{{ request('search') ? 'inline-flex' : 'hidden' }} items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-50 transition shrink-0">
                        Reset
                    </a>

                </form>
            </div>

            {{-- Target Container untuk Update Isi Tabel --}}
            <div id="tableContainer">
                {{-- Tambahkan pb-36 di sini agar ada ruang kosong ke bawah untuk menu dropdown --}}
                <div class="overflow-x-auto  ">
                    <table class="w-full text-left text-xs sm:text-sm table-fixed">
                        <!-- Header dan Body Tabel Anda tetap sama -->
                        <thead
                            class="bg-indigo-50/50 border-b border-indigo-100/60 text-slate-900 font-bold uppercase tracking-wider text-[10px] sm:text-xs">
                            <tr>
                                <th class="px-2 py-3 sm:px-4 w-[8%] text-center">NO</th>
                                <th class="hidden sm:table-cell px-3 py-3 sm:px-4 w-[15%]">KODE / ISBN</th>

                                {{-- Kolom Nama Buku dengan Sortir URL --}}
                                <th class="px-2 py-3 sm:px-4 w-[48%] sm:w-[33%]">
                                    <a href="{{ route('books.index', array_merge(request()->all(), ['sort' => 'judul_buku', 'direction' => request('direction') == 'asc' && request('sort') == 'judul_buku' ? 'desc' : 'asc'])) }}"
                                        class="group inline-flex items-center gap-1.5 hover:text-indigo-600 transition cursor-pointer">
                                        <span>judul BUKU</span>
                                        <span class="text-slate-400 group-hover:text-indigo-600">
                                            @if (request('sort') == 'judul_buku')
                                                {{ request('direction') == 'asc' ? '▲' : '▼' }}
                                            @else
                                                ⇅
                                            @endif
                                        </span>
                                    </a>
                                </th>

                                {{-- Kolom Stok dengan Sortir URL --}}
                                <th class="px-1.5 py-3 sm:px-4 w-[14%] sm:w-[8%] text-center">
                                    <a href="{{ route('books.index', array_merge(request()->all(), ['sort' => 'stok', 'direction' => request('direction') == 'asc' && request('sort') == 'stok' ? 'desc' : 'asc'])) }}"
                                        class="group inline-flex items-center justify-center gap-1 hover:text-indigo-600 transition cursor-pointer w-full">
                                        <span>STOK</span>
                                        <span class="text-slate-400 group-hover:text-indigo-600">
                                            @if (request('sort') == 'stok')
                                                {{ request('direction') == 'asc' ? '▲' : '▼' }}
                                            @else
                                                ⇅
                                            @endif
                                        </span>
                                    </a>
                                </th>

                                <th class="hidden sm:table-cell px-3 py-3 sm:px-4 w-[14%]">KETERANGAN</th>
                                <th class="hidden sm:table-cell px-2 py-3 sm:px-4 w-[10%] text-center">FILE / COVER</th>
                                <th class="px-1 py-3 sm:px-4 text-center w-[18%] sm:w-[14%]">AKSI</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($books as $index => $book)
                                <tr class="transition hover:bg-slate-50/80">
                                    <td
                                        class="whitespace-nowrap px-2 py-3.5 sm:px-4 text-center font-medium text-slate-500">
                                        {{ $books->firstItem() + $index }}
                                    </td>

                                    <td
                                        class="hidden sm:table-cell whitespace-nowrap px-3 py-3.5 sm:px-4 font-mono font-bold text-slate-900 truncate">
                                        {{ $book->kode_buku }}
                                    </td>

                                    <td class="px-2 py-3.5 sm:px-4 font-semibold text-slate-900 truncate">
                                        {{ $book->judul_buku }}
                                    </td>

                                    <td
                                        class="whitespace-nowrap px-1.5 py-3.5 sm:px-4 text-center font-extrabold text-slate-900">
                                        <span
                                            class="inline-block rounded-lg bg-slate-100 px-2 py-0.5 text-xs text-slate-800">
                                            {{ $book->stok }}
                                        </span>
                                    </td>

                                    <td
                                        class="hidden sm:table-cell px-3 py-3.5 sm:px-4 font-medium text-slate-500 truncate">
                                        {{ $book->keterangan ?? '-' }}
                                    </td>

                                    {{-- KOLOM FILE / COVER (Hidden di Mobile) --}}
                                    <td class="hidden sm:table-cell px-2 py-3.5 sm:px-4 text-center whitespace-nowrap">
                                        @if (!empty($book->file))
                                            <button type="button"
                                                onclick="openFilePreview(@js(asset('storage/' . $book->file)), @js(basename($book->file)))"
                                                class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-bold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                </svg>
                                                <span>Lihat</span>
                                            </button>
                                        @else
                                            <span class="text-xs text-slate-400 font-medium">-</span>
                                        @endif
                                    </td>
                                    {{-- KOLOM AKSI DENGAN DROPDOWN PINTAR OTOMATIS --}}
                                    <td class="whitespace-nowrap px-1 py-3.5 sm:px-4 text-center">
                                        <div class="relative inline-block text-left" x-data="{ open: false, dropUp: false, menuTop: 0, menuLeft: 0 }">
                                            <!-- Tombol Titik Tiga -->
                                            <button
                                                @click="
                    let rect = $el.getBoundingClientRect();
                    let spaceBelow = window.innerHeight - rect.bottom;
                    dropUp = spaceBelow < 220;
                    menuTop = dropUp ? rect.top - 140 : rect.bottom + 8;
                    menuLeft = Math.max(8, Math.min(rect.right - 128, window.innerWidth - 136));
                    open = !open;
                "
                                                @click.away="open = false" type="button" title="Menu Aksi"
                                                class="inline-flex items-center justify-center rounded-lg border border-slate-200 p-1.5 text-slate-600 transition hover:border-slate-300 hover:bg-slate-100 hover:text-indigo-600 cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                                </svg>
                                            </button>

                                            <!-- Menu Dropdown Pintar (Otomatis menyesuaikan posisi) -->
                                            <template x-teleport="body">
                                                <div x-show="open" x-transition
                                                    :style="`top: ${menuTop}px; left: ${menuLeft}px;`"
                                                    class="fixed z-[9999] w-32 rounded-xl border border-slate-200 bg-white py-1 text-left shadow-lg focus:outline-none"
                                                    style="display: none;">

                                                    {{-- Tombol Detail --}}
                                                    <button type="button" onclick="openDetailModal(this)"
                                                        data-kode="{{ $book->kode_buku }}"
                                                        data-judul="{{ $book->judul_buku }}"
                                                        data-stok="{{ $book->stok }}"
                                                        data-keterangan="{{ $book->keterangan ?? '-' }}"
                                                        data-file="{{ $book->file ? asset('storage/' . $book->file) : '' }}"
                                                        @click="open = false"
                                                        class="flex w-full items-center gap-2 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                            class="w-3.5 h-3.5 text-slate-400">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.573 16.49 16.638 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                        </svg>
                                                        <span>Detail</span>
                                                    </button>

                                                    {{-- Tombol Edit --}}
                                                    <button type="button"
                                                        onclick="openEditModal('{{ route('books.update', $book) }}', '{{ $book->kode_buku }}', '{{ addslashes($book->judul_buku) }}', '{{ $book->stok }}', '{{ addslashes($book->keterangan ?? '') }}')"
                                                        @click="open = false"
                                                        class="flex w-full items-center gap-2 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                            class="w-3.5 h-3.5 text-slate-400">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                        </svg>
                                                        <span>Edit</span>
                                                    </button>

                                                    {{-- Tombol Hapus --}}
                                                    <form method="POST" action="{{ route('books.destroy', $book) }}"
                                                        onsubmit="return confirm('Yakin ingin menghapus buku ini?')"
                                                        class="block m-0 p-0">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="flex w-full items-center gap-2 px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="2"
                                                                stroke="currentColor" class="w-3.5 h-3.5 text-rose-400">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                            </svg>
                                                            <span>Hapus</span>
                                                        </button>
                                                    </form>

                                                </div>
                                            </template>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-12 text-center">
                                        <div class="text-3xl">📚</div>
                                        <p class="mt-2 text-xs sm:text-sm font-bold text-slate-800">Belum ada data buku</p>
                                        <p class="mt-0.5 text-xs font-medium text-slate-400">Silakan tambahkan data buku
                                            terlebih dahulu.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($books->hasPages())
                    <div class="border-t border-slate-100 px-4 py-3">
                        {{ $books->withQueryString()->links() }}
                    </div>
                @endif
            </div>

        </div>

    </div>

    {{-- 1. MODAL DETAIL BUKU --}}
    <div id="detailModal" class="fixed inset-0 z-50 hidden p-4 items-center justify-center bg-black/50"
        onclick="closeDetailModal()">
        <div class="pointer-events-auto w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl transition-all border border-neutral-200"
            onclick="event.stopPropagation()">
            <div class="flex items-center justify-between border-b border-neutral-200 pb-3">
                <h3 class="text-base font-extrabold text-black">Detail Informasi Buku</h3>
                <button type="button" onclick="closeDetailModal()"
                    class="rounded-lg p-1 text-neutral-400 hover:bg-neutral-100 hover:text-black transition">✕</button>
            </div>
            <div class="mt-4 space-y-3.5 text-xs sm:text-sm">
                <div>
                    <span class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block">Kode Buku /
                        ISBN</span>
                    <span id="modalKode" class="font-mono font-bold text-black text-sm"></span>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block">Judul / Nama
                        Buku</span>
                    <p id="modalJudul" class="font-bold text-black leading-relaxed"></p>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block">Stok Gudang</span>
                    <span id="modalStok" class="font-extrabold text-indigo-600 text-sm"></span>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block">Keterangan</span>
                    <p id="modalKeterangan" class="font-bold text-black leading-relaxed"></p>
                </div>
                {{-- DOKUMEN / FILE ATTACHMENT --}}
                <div>
                    <span class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block mb-1">File / Cover
                        Buku</span>
                    <div id="modalFileContainer">
                        <span id="modalFileEmpty" class="text-xs text-neutral-400 font-medium hidden">- Tidak Ada File
                            -</span>
                        <a id="modalFileLink" href="#" target="_blank"
                            class="inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50/50 px-3 py-2 text-xs font-bold text-indigo-600 hover:bg-indigo-100 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            <span>Buka / Unduh File</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end border-t border-neutral-200 pt-3">
                <button type="button" onclick="closeDetailModal()"
                    class="rounded-xl border border-neutral-300 bg-white px-4 py-2 text-xs font-bold text-neutral-700 hover:bg-neutral-100 transition cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL PREVIEW FILE / COVER --}}
    <div id="filePreviewModal" class="fixed inset-0 z-[60] hidden p-4 items-center justify-center bg-black/60"
        onclick="closeFilePreview()">
        <div class="relative flex h-full max-h-[90vh] w-full max-w-5xl flex-col rounded-2xl bg-white shadow-2xl"
            onclick="event.stopPropagation()">
            <div class="flex shrink-0 items-center justify-between border-b border-neutral-200 px-4 py-3 sm:px-6">
                <h3 id="filePreviewTitle" class="truncate pr-4 text-sm font-extrabold text-neutral-900">Preview File</h3>
                <button type="button" onclick="closeFilePreview()" title="Tutup preview"
                    class="shrink-0 rounded-lg p-1.5 text-neutral-400 hover:bg-neutral-100 hover:text-neutral-900 transition">
                    <span class="text-xl leading-none">&times;</span>
                </button>
            </div>
            <div class="flex min-h-0 flex-1 items-center justify-center bg-neutral-100 p-3 sm:p-6">
                <img id="filePreviewImage" src="" alt="Preview cover buku"
                    class="hidden max-h-full max-w-full rounded-lg object-contain shadow-sm">
                <iframe id="filePreviewDocument" title="Preview dokumen buku"
                    class="hidden h-full w-full rounded-lg border border-neutral-200 bg-white"></iframe>
                <p id="filePreviewUnsupported" class="hidden text-center text-sm font-semibold text-neutral-500">
                    File ini tidak dapat ditampilkan sebagai preview.
                </p>
            </div>
            <div class="flex shrink-0 justify-end border-t border-neutral-200 px-4 py-3 sm:px-6">
                <a id="filePreviewOpen" href="#" target="_blank" rel="noopener"
                    class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700 transition">
                    Buka / Unduh File
                </a>
            </div>
        </div>
    </div>

    {{-- 2. MODAL TAMBAH BUKU --}}
    <div id="tambahModal" class="fixed inset-0 z-50 hidden p-4 items-center justify-center bg-black/50"
        onclick="closeTambahModal()">
        <div class="pointer-events-auto w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl transition-all border border-neutral-200 max-h-[90vh] overflow-y-auto"
            onclick="event.stopPropagation()">
            <div class="flex items-center justify-between border-b border-neutral-200 pb-3">
                <h3 class="text-base font-extrabold text-black">Tambah Data Buku</h3>
                <button type="button" onclick="closeTambahModal()"
                    class="rounded-lg p-1 text-neutral-400 hover:bg-neutral-100 hover:text-black transition">✕</button>
            </div>
            <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data"
                class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block mb-1">Kode Buku /
                        ISBN</label>
                    <input type="text" name="kode_buku" required
                        class="w-full rounded-xl border border-neutral-300 px-3.5 py-2 text-xs sm:text-sm text-black focus:border-indigo-600 focus:outline-none">
                </div>
                <div>
                    <label class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block mb-1">Judul
                        Buku</label>
                    <input type="text" name="judul_buku" required
                        class="w-full rounded-xl border border-neutral-300 px-3.5 py-2 text-xs sm:text-sm text-black focus:border-indigo-600 focus:outline-none">
                </div>
                <div>
                    <label class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block mb-1">Stok</label>
                    <input type="number" name="stok" required min="0"
                        class="w-full rounded-xl border border-neutral-300 px-3.5 py-2 text-xs sm:text-sm text-black focus:border-indigo-600 focus:outline-none">
                </div>
                <div>
                    <label
                        class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block mb-1">Keterangan</label>
                    <textarea name="keterangan" rows="3"
                        class="w-full rounded-xl border border-neutral-300 px-3.5 py-2 text-xs sm:text-sm text-black focus:border-indigo-600 focus:outline-none"></textarea>
                </div>
                <div>
                    <label class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block mb-1">Upload File
                        Cover / Dokumen</label>
                    <input type="file" name="file"
                        class="w-full text-xs text-neutral-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-neutral-100 file:text-neutral-700 hover:file:bg-neutral-200 cursor-pointer">
                </div>
                <div class="mt-6 flex justify-end gap-2 border-t border-neutral-200 pt-4">
                    <button type="button" onclick="closeTambahModal()"
                        class="rounded-xl border border-neutral-300 bg-white px-4 py-2 text-xs font-bold text-neutral-700 hover:bg-neutral-100">Batal</button>
                    <button type="submit"
                        class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700 shadow-sm shadow-indigo-200">Simpan
                        Data</button>
                </div>
            </form>
        </div>
    </div>

    {{-- {{-- 3. MODAL EDIT BUKU --}}
    <div id="editModal" class="fixed inset-0 z-50 hidden p-4 items-center justify-center bg-black/50"
        onclick="closeEditModal()">
        <div class="pointer-events-auto w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl transition-all border border-neutral-200 max-h-[90vh] overflow-y-auto"
            onclick="event.stopPropagation()">
            <div class="flex items-center justify-between border-b border-neutral-200 pb-3">
                <h3 class="text-base font-extrabold text-black">Edit Data Buku</h3>
                <button type="button" onclick="closeEditModal()"
                    class="rounded-lg p-1 text-neutral-400 hover:bg-neutral-100 hover:text-black transition">✕</button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block mb-1">Kode Buku /
                        ISBN</label>
                    <input type="text" name="kode_buku" id="editKode" required
                        class="w-full rounded-xl border border-neutral-300 px-3.5 py-2 text-xs sm:text-sm text-black focus:border-indigo-600 focus:outline-none">
                </div>
                <div>
                    <label class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block mb-1">Judul
                        Buku</label>
                    {{-- UBAH NAME MENJADI nama_buku AGAR SESUAI DENGAN CONTROLLER & DATABASE --}}
                    <input type="text" name="judul_buku" id="editJudul" required
                        class="w-full rounded-xl border border-neutral-300 px-3.5 py-2 text-xs sm:text-sm text-black focus:border-indigo-600 focus:outline-none">
                </div>
                <div>
                    <label class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block mb-1">Stok</label>
                    <input type="number" name="stok" id="editStok" required min="0"
                        class="w-full rounded-xl border border-neutral-300 px-3.5 py-2 text-xs sm:text-sm text-black focus:border-indigo-600 focus:outline-none">
                </div>
                <div>
                    <label
                        class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block mb-1">Keterangan</label>
                    <textarea name="keterangan" id="editKeterangan" rows="3"
                        class="w-full rounded-xl border border-neutral-300 px-3.5 py-2 text-xs sm:text-sm text-black focus:border-indigo-600 focus:outline-none"></textarea>
                </div>
                <div>
                    <label class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block mb-1">Ganti File
                        Cover (Opsional)</label>
                    <input type="file" name="file"
                        class="w-full text-xs text-neutral-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-neutral-100 file:text-neutral-700 hover:file:bg-neutral-200 cursor-pointer">
                </div>

                <div class="mt-6 flex justify-end gap-2 border-t border-neutral-200 pt-4">
                    <button type="button" onclick="closeEditModal()"
                        class="rounded-xl border border-neutral-300 bg-white px-4 py-2 text-xs font-bold text-neutral-700 hover:bg-neutral-100 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                        class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700 shadow-sm shadow-indigo-200 transition cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
<script>
    // --- MODAL TAMBAH ---
    function openTambahModal() {
        closeDetailModal();
        closeEditModal();
        closeFilePreview();
        const modal = document.getElementById('tambahModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.classList.remove('pointer-events-none');
        }
    }

    function closeTambahModal() {
        const modal = document.getElementById('tambahModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.classList.add('pointer-events-none');
        }
    }

    // --- MODAL DETAIL ---
    function openDetailModal(button) {
        const kode = button.dataset.kode;
        const judul = button.dataset.judul;
        const stok = button.dataset.stok;
        const keterangan = button.dataset.keterangan;
        const fileUrl = button.dataset.file;

        closeTambahModal();
        closeEditModal();
        closeFilePreview();
        document.getElementById('modalKode').innerText = kode;
        document.getElementById('modalJudul').innerText = judul;
        document.getElementById('modalStok').innerText = stok + ' Unit';
        document.getElementById('modalKeterangan').innerText = keterangan;

        const fileLink = document.getElementById('modalFileLink');
        const fileEmpty = document.getElementById('modalFileEmpty');

        if (fileUrl && fileUrl.trim() !== '') {
            fileLink.href = fileUrl;
            fileLink.classList.remove('hidden');
            fileLink.classList.add('inline-flex');
            fileEmpty.classList.add('hidden');
        } else {
            fileLink.classList.add('hidden');
            fileLink.classList.remove('inline-flex');
            fileEmpty.classList.remove('hidden');
        }

        const modal = document.getElementById('detailModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeDetailModal() {
        const modal = document.getElementById('detailModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function openFilePreview(fileUrl, fileName) {
        closeDetailModal();
        closeTambahModal();
        closeEditModal();
        const modal = document.getElementById('filePreviewModal');
        const image = document.getElementById('filePreviewImage');
        const documentViewer = document.getElementById('filePreviewDocument');
        const unsupported = document.getElementById('filePreviewUnsupported');
        const openLink = document.getElementById('filePreviewOpen');
        const title = document.getElementById('filePreviewTitle');
        const extension = fileName.split('.').pop().toLowerCase();

        image.classList.add('hidden');
        documentViewer.classList.add('hidden');
        unsupported.classList.add('hidden');
        image.removeAttribute('src');
        documentViewer.removeAttribute('src');
        openLink.href = fileUrl;
        title.textContent = fileName || 'Preview File';

        if (['jpg', 'jpeg', 'png'].includes(extension)) {
            image.src = fileUrl;
            image.classList.remove('hidden');
        } else if (extension === 'pdf') {
            documentViewer.src = fileUrl;
            documentViewer.classList.remove('hidden');
        } else {
            unsupported.classList.remove('hidden');
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeFilePreview() {
        const modal = document.getElementById('filePreviewModal');
        const image = document.getElementById('filePreviewImage');
        const documentViewer = document.getElementById('filePreviewDocument');

        modal.classList.add('hidden');
        modal.classList.remove('flex');
        image.removeAttribute('src');
        documentViewer.removeAttribute('src');
    }

    // --- MODAL EDIT ---
    function openEditModal(actionUrl, kode, judul, stok, keterangan) {
        closeTambahModal();
        closeDetailModal();
        closeFilePreview();
        document.getElementById('editForm').action = actionUrl;
        document.getElementById('editKode').value = kode;
        document.getElementById('editJudul').value = judul;
        document.getElementById('editStok').value = stok;
        document.getElementById('editKeterangan').value = keterangan;

        const modal = document.getElementById('editModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeEditModal() {
        const modal = document.getElementById('editModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key !== 'Escape') {
            return;
        }

        closeFilePreview();
        closeDetailModal();
        closeTambahModal();
        closeEditModal();
    });

    // --- AJAX LIVE SEARCH, PAGINATION & RESET ---
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('bookSearch');
        const searchForm = document.getElementById('bookSearchForm');
        const tableContainer = document.getElementById('tableContainer');
        const btnReset = document.getElementById('btnReset');

        let timer;

        function toggleResetButton() {
            if (btnReset) {
                if (searchInput && searchInput.value.trim() !== '') {
                    btnReset.classList.remove('hidden');
                    btnReset.classList.add('inline-flex');
                } else {
                    btnReset.classList.add('hidden');
                    btnReset.classList.remove('inline-flex');
                }
            }
        }

        toggleResetButton();

        function fetchBooks(url) {
            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTable = doc.getElementById('tableContainer');

                    if (newTable && tableContainer) {
                        tableContainer.innerHTML = newTable.innerHTML;
                    }

                    toggleResetButton();
                })
                .catch(error => {
                    console.error('Error loading data:', error);
                });
        }

        // Live Search saat mengetik
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(timer);

                timer = setTimeout(function() {
                    const query = searchInput.value.trim();
                    const url =
                        `{{ route('books.index') }}?search=${encodeURIComponent(query)}`;

                    fetchBooks(url);
                    window.history.pushState(null, '', url);
                }, 300);
            });
        }

        // Tombol Cari (Submit Form)
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                clearTimeout(timer);

                const query = searchInput.value.trim();
                const url = `{{ route('books.index') }}?search=${encodeURIComponent(query)}`;

                fetchBooks(url);
                window.history.pushState(null, '', url);
            });
        }

        // Tombol Reset
        if (btnReset) {
            btnReset.addEventListener('click', function(e) {
                e.preventDefault();
                if (searchInput) {
                    searchInput.value = '';
                }

                const url = `{{ route('books.index') }}`;
                fetchBooks(url);
                window.history.pushState(null, '', url);
            });
        }

        // Hanya AJAX untuk search/reset. Pagination dan sort dibiarkan normal agar URL Laravel bawaan tetap aman.
        document.addEventListener('click', function(e) {
            const targetLink = e.target.closest('#tableContainer a');

            if (!targetLink || !targetLink.href) {
                return;
            }

            const url = new URL(targetLink.href, window.location.href);
            const isPaginationLink = targetLink.closest('.pagination a') ||
                targetLink.getAttribute('rel') === 'next' ||
                targetLink.getAttribute('rel') === 'prev';
            const isSortLink = url.searchParams.has('sort');

            if (isPaginationLink || isSortLink) {
                return;
            }

            e.preventDefault();
            fetchBooks(url.href);
            window.history.pushState(null, '', url.href);

            try {
                const urlParams = new URLSearchParams(url.search);
                if (urlParams.has('search') && searchInput) {
                    searchInput.value = urlParams.get('search');
                    toggleResetButton();
                }
            } catch (err) {
                console.error('Error parsing AJAX URL:', err);
            }
        });
    });
</script>
