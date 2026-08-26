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
                                <th class="px-2 py-3 sm:px-4 w-[6%] text-center">NO</th>
                                <th class="hidden sm:table-cell px-3 py-3 sm:px-4 w-[18%]">KODE / ISBN</th>

                                {{-- Kolom Nama Buku dengan Sortir URL --}}
                                <th class="px-2 py-3 sm:px-4 w-[40%] sm:w-[30%]">
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
                                <th class="px-1.5 py-3 sm:px-4 w-[10%] sm:w-[8%] text-center">
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
                                <th class="px-1 py-3 sm:px-4 text-center w-[24%] sm:w-[14%]">AKSI</th>
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
                                            <a href="{{ asset('storage/' . $book->file) }}" target="_blank"
                                                class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-bold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                </svg>
                                                <span>Lihat</span>
                                            </a>
                                        @else
                                            <span class="text-xs text-slate-400 font-medium">-</span>
                                        @endif
                                    </td>

                                    {{-- KOLOM AKSI DENGAN DROPDOWN TITIK TIGA (ALPINE.JS) --}}
                                    {{-- KOLOM AKSI DENGAN DROPDOWN TITIK TIGA (ALPINE.JS) --}}
                                    <td class="whitespace-nowrap px-1 py-3.5 sm:px-4 text-center">
                                        <div class="relative inline-block text-left" x-data="{ open: false }">
                                            <!-- Tombol Titik Tiga -->
                                            <button @click="open = !open" @click.away="open = false" type="button"
                                                title="Menu Aksi"
                                                class="inline-flex items-center justify-center rounded-lg border border-slate-200 p-1.5 text-slate-600 transition hover:border-slate-300 hover:bg-slate-100 hover:text-indigo-600 cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                                </svg>
                                            </button>

                                            <!-- Menu Dropdown Container -->
                                            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                                x-transition:enter-start="transform opacity-0 scale-95"
                                                x-transition:enter-end="transform opacity-100 scale-100"
                                                x-transition:leave="transition ease-in duration-75"
                                                x-transition:leave-start="transform opacity-100 scale-100"
                                                x-transition:leave-end="transform opacity-0 scale-95"
                                                class="absolute right-0 z-50 w-36 mb-1 origin-top-right rounded-xl bg-white border border-slate-200 shadow-lg py-1 text-left focus:outline-none"
                                                style="display: none;">
                                            </div>
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
    <div id="detailModal" class="fixed inset-0 z-50 hidden p-4 pointer-events-none items-center justify-center">
        <div
            class="pointer-events-auto w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl transition-all border border-neutral-200">
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

    {{-- 2. MODAL TAMBAH BUKU --}}
    <div id="tambahModal" class="fixed inset-0 z-50 hidden p-4 pointer-events-none items-center justify-center">
        <div
            class="pointer-events-auto w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl transition-all border border-neutral-200 max-h-[90vh] overflow-y-auto">
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
    <div id="editModal" class="fixed inset-0 z-50 hidden p-4 pointer-events-none items-center justify-center">
        <div
            class="pointer-events-auto w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl transition-all border border-neutral-200 max-h-[90vh] overflow-y-auto">
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
    // Fungsi Buka Modal Detail
    function openDetailModal(button) {
        const kode = button.dataset.kode;
        const judul = button.dataset.judul;
        const stok = button.dataset.stok;
        const keterangan = button.dataset.keterangan;
        const fileUrl = button.dataset.file;

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
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // Fungsi Tutup Modal Detail
    function closeDetailModal() {
        const modal = document.getElementById('detailModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Functions Modal Edit
    function openEditModal(actionUrl, kode, judul, stok, keterangan) {
        document.getElementById('editForm').action = actionUrl;
        document.getElementById('editKode').value = kode;
        document.getElementById('editJudul').value = judul;
        document.getElementById('editStok').value = stok;
        document.getElementById('editKeterangan').value = keterangan;

        const modal = document.getElementById('editModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditModal() {
        const modal = document.getElementById('editModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }


    // AJAX LIVE SEARCH & PAGINATION
    document.addEventListener('DOMContentLoaded', function() {

        const searchInput = document.getElementById('bookSearch');
        const searchForm = document.getElementById('bookSearchForm');
        const tableContainer = document.getElementById('tableContainer');
        const btnReset = document.getElementById('btnReset');

        let timer;

        // Fungsi mengecek status tombol reset berdasarkan input
        function toggleResetButton() {
            if (searchInput && searchInput.value.trim() !== '') {
                btnReset.classList.remove('hidden');
                btnReset.classList.add('inline-flex');
            } else {
                btnReset.classList.add('hidden');
                btnReset.classList.remove('inline-flex');
            }
        }

        // Cek status tombol reset saat halaman pertama kali dimuat
        toggleResetButton();

        // Fungsi mengambil data tanpa reload halaman
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

                    if (newTable) {
                        tableContainer.innerHTML = newTable.innerHTML;
                    }

                    toggleResetButton();
                })
                .catch(error => {
                    console.error('Error loading data:', error);
                });
        }


        // LIVE SEARCH SAAT MENGETIK
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
                searchInput.value = '';

                const url = `{{ route('books.index') }}`;
                fetchBooks(url);

                window.history.pushState(null, '', url);
            });
        }


        // === TAMBAHAN: Penanganan AJAX untuk Pagination / Link di dalam Tabel ===
        document.addEventListener('click', function(e) {
            const targetLink = e.target.closest('#tableContainer a');

            // Jika yang diklik adalah link pagination atau sorting di dalam tabel container
            if (targetLink && targetLink.href) {
                e.preventDefault();
                const url = targetLink.href;

                fetchBooks(url);
                window.history.pushState(null, '', url);

                // Sinkronkan nilai input search jika ada parameter search di URL pagination
                const urlParams = new URLSearchParams(new URL(url).search);
                if (urlParams.has('search') && searchInput) {
                    searchInput.value = urlParams.get('search');
                    toggleResetButton();
                }
            }
        });

    });
</script>
