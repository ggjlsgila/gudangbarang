@extends('layouts.app')

@section('content')
    <div class="space-y-4 sm:space-y-6 antialiased">

        {{-- Header --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                    Barang Lainnya
                </h1>
                <p class="text-xs sm:text-sm font-medium text-slate-500">
                    Kelola data dan stok barang inventaris lainnya
                </p>
            </div>

            {{-- Tombol Buka Modal Tambah --}}
            <button type="button" onclick="openTambahModal()"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs sm:text-sm font-bold text-white shadow-sm shadow-indigo-200 transition hover:bg-indigo-700 w-full sm:w-auto cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Barang</span>
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
                <form method="GET" action="{{ route('items.index') }}" class="flex gap-2" id="itemSearchForm">
                    <input type="text" name="search" id="itemSearch" value="{{ request('search') }}"
                        placeholder="Cari kode atau nama barang..." autocomplete="off"
                        class="w-full rounded-xl border-slate-300 text-xs sm:text-sm font-medium text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500">

                    <button type="submit"
                        class="rounded-xl bg-indigo-600 px-4 py-2 text-xs sm:text-sm font-bold text-white hover:bg-indigo-700 transition shrink-0">
                        Cari
                    </button>

                    <a href="{{ route('items.index') }}" id="btnReset"
                        class="{{ request('search') ? 'inline-flex' : 'hidden' }} items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-50 transition shrink-0">
                        Reset
                    </a>
                </form>
            </div>

            {{-- Target Container untuk Update Isi Tabel --}}
            <div id="tableContainer">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm table-fixed">
                        <thead
                            class="bg-slate-50 border-b border-slate-100 text-slate-600 font-bold uppercase tracking-wider text-[10px] sm:text-xs">
                            <tr>
                                <th class="px-2 py-3 sm:px-4 w-[6%] text-center">No</th>
                                <th class="hidden sm:table-cell px-3 py-3 sm:px-4 w-[16%]">Kode Barang</th>
                                <th class="px-2 py-3 sm:px-4 w-[40%] sm:w-[32%]">Nama Barang</th>
                                <th class="px-1.5 py-3 sm:px-4 w-[10%] text-center">Stok</th>
                                <th class="hidden sm:table-cell px-3 py-3 sm:px-4 w-[12%]">Keterangan</th>
                                <th class="hidden sm:table-cell px-3 py-3 sm:px-4 w-[10%] text-center">File</th>
                                <th class="px-1 py-3 sm:px-4 text-center w-[24%] sm:w-[14%]">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($items as $index => $item)
                                <tr class="transition hover:bg-slate-50/80">
                                    <td
                                        class="whitespace-nowrap px-2 py-3.5 sm:px-4 text-center font-medium text-slate-500">
                                        {{ $items->firstItem() + $index }}
                                    </td>

                                    <td
                                        class="hidden sm:table-cell whitespace-nowrap px-3 py-3.5 sm:px-4 font-mono font-bold text-slate-900 truncate">
                                        {{ $item->kode_barang }}
                                    </td>

                                    <td class="px-2 py-3.5 sm:px-4 font-semibold text-slate-900 truncate">
                                        {{ $item->nama_barang }}
                                    </td>

                                    <td
                                        class="whitespace-nowrap px-1.5 py-3.5 sm:px-4 text-center font-extrabold text-slate-900">
                                        <span
                                            class="inline-block rounded-lg bg-slate-100 px-2 py-0.5 text-xs text-slate-800">
                                            {{ $item->stok }}
                                        </span>
                                    </td>

                                    <td
                                        class="hidden sm:table-cell px-3 py-3.5 sm:px-4 font-medium text-slate-500 truncate">
                                        {{ $item->keterangan ?? '-' }}
                                    </td>

                                    {{-- Kolom File/Lampiran --}}
                                    <td class="hidden sm:table-cell px-3 py-3.5 sm:px-4 text-center whitespace-nowrap">
                                        @if ($item->file)
                                            <a href="{{ asset('storage/' . $item->file) }}" target="_blank"
                                                class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700 transition hover:bg-slate-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                </svg>
                                                <span>Lihat</span>
                                            </a>
                                        @else
                                            <span class="text-slate-400 font-medium">-</span>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-1 py-3.5 sm:px-4 text-center">
                                        <div class="inline-flex items-center justify-center gap-1.5">

                                            {{-- Tombol Detail --}}
                                            <button type="button" title="Detail Barang"
                                                onclick="openDetailModal('{{ $item->kode_barang }}', '{{ addslashes($item->nama_barang) }}', '{{ $item->stok }}', '{{ addslashes($item->keterangan ?? '-') }}', '{{ $item->file ? asset('storage/' . $item->file) : '' }}')"
                                                class="inline-flex items-center justify-center rounded-lg border border-slate-200 p-1.5 text-slate-600 transition hover:border-slate-300 hover:bg-slate-100 hover:text-indigo-600 cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.573 16.49 16.638 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </button>

                                            {{-- Tombol Edit Modal --}}
                                            <button type="button" title="Edit Barang"
                                                onclick="openEditModal('{{ route('items.update', $item) }}', '{{ $item->kode_barang }}', '{{ addslashes($item->nama_barang) }}', '{{ $item->stok }}', '{{ addslashes($item->keterangan ?? '') }}')"
                                                class="inline-flex items-center justify-center rounded-lg border border-slate-200 p-1.5 text-slate-600 transition hover:border-slate-300 hover:bg-slate-100 hover:text-indigo-600 cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                            </button>

                                            {{-- Tombol Hapus --}}
                                            <form method="POST" action="{{ route('items.destroy', $item) }}"
                                                class="inline-flex m-0 p-0"
                                                onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus Barang"
                                                    class="inline-flex items-center justify-center rounded-lg border border-rose-200 bg-rose-50 p-1.5 text-rose-600 transition hover:bg-rose-100 hover:text-rose-700 cursor-pointer">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                        class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-12 text-center">
                                        <div class="text-3xl">📦</div>
                                        <p class="mt-2 text-xs sm:text-sm font-bold text-slate-800">Belum ada data barang
                                        </p>
                                        <p class="mt-0.5 text-xs font-medium text-slate-400">Silakan tambahkan barang
                                            terlebih dahulu.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($items->hasPages())
                    <div class="border-t border-slate-100 px-4 py-3">
                        {{ $items->links() }}
                    </div>
                @endif
            </div>

        </div>

    </div>

    {{-- 1. MODAL DETAIL --}}
    <div id="detailModal" class="fixed inset-0 z-50 hidden p-4 pointer-events-none items-center justify-center">
        <div
            class="pointer-events-auto w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl transition-all border border-neutral-200">
            <div class="flex items-center justify-between border-b border-neutral-200 pb-3">
                <h3 class="text-base font-extrabold text-black">Detail Informasi Barang</h3>
                <button type="button" onclick="closeDetailModal()"
                    class="rounded-lg p-1 text-neutral-400 hover:bg-neutral-100 hover:text-black transition">✕</button>
            </div>
            <div class="mt-4 space-y-3.5 text-xs sm:text-sm">
                <div>
                    <span class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block">Kode Barang</span>
                    <span id="modalKode" class="font-mono font-bold text-black text-sm"></span>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block">Nama Barang</span>
                    <p id="modalNama" class="font-bold text-black leading-relaxed"></p>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block">Stok Gudang</span>
                    <span id="modalStok" class="font-extrabold text-indigo-600 text-sm"></span>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block">Keterangan</span>
                    <p id="modalKeterangan" class="font-bold text-black leading-relaxed"></p>
                </div>
                {{-- Field File di Modal --}}
                <div>
                    <span class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block mb-1">File /
                        Lampiran</span>
                    <div id="modalFileContainer"></div>
                </div>
            </div>
            <div class="mt-6 flex justify-end border-t border-neutral-200 pt-3">
                <button type="button" onclick="closeDetailModal()"
                    class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white transition hover:bg-indigo-700 shadow-sm shadow-indigo-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- 2. MODAL TAMBAH --}}
    <div id="tambahModal" class="fixed inset-0 z-50 hidden p-4 pointer-events-none items-center justify-center">
        <div
            class="pointer-events-auto w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl transition-all border border-neutral-200 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-neutral-200 pb-3">
                <h3 class="text-base font-extrabold text-black">Tambah Data Barang</h3>
                <button type="button" onclick="closeTambahModal()"
                    class="rounded-lg p-1 text-neutral-400 hover:bg-neutral-100 hover:text-black transition">✕</button>
            </div>
            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data"
                class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block mb-1">Kode
                        Barang</label>
                    <input type="text" name="kode_barang" required
                        class="w-full rounded-xl border border-neutral-300 px-3.5 py-2 text-xs sm:text-sm text-black focus:border-indigo-600 focus:outline-none">
                </div>
                <div>
                    <label class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block mb-1">Nama
                        Barang</label>
                    <input type="text" name="nama_barang" required
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
                    <label class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block mb-1">Upload File /
                        Lampiran</label>
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

    {{-- 3. MODAL EDIT --}}
    <div id="editModal" class="fixed inset-0 z-50 hidden p-4 pointer-events-none items-center justify-center">
        <div
            class="pointer-events-auto w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl transition-all border border-neutral-200 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-neutral-200 pb-3">
                <h3 class="text-base font-extrabold text-black">Edit Data Barang</h3>
                <button type="button" onclick="closeEditModal()"
                    class="rounded-lg p-1 text-neutral-400 hover:bg-neutral-100 hover:text-black transition">✕</button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block mb-1">Kode
                        Barang</label>
                    <input type="text" name="kode_barang" id="editKode" required
                        class="w-full rounded-xl border border-neutral-300 px-3.5 py-2 text-xs sm:text-sm text-black focus:border-indigo-600 focus:outline-none">
                </div>
                <div>
                    <label class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block mb-1">Nama
                        Barang</label>
                    <input type="text" name="nama_barang" id="editNama" required
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
                    <label class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block mb-1">Ganti File /
                        Lampiran (Opsional)</label>
                    <input type="file" name="file"
                        class="w-full text-xs text-neutral-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-neutral-100 file:text-neutral-700 hover:file:bg-neutral-200 cursor-pointer">
                </div>
                <div class="mt-6 flex justify-end gap-2 border-t border-neutral-200 pt-4">
                    <button type="button" onclick="closeEditModal()"
                        class="rounded-xl border border-neutral-300 bg-white px-4 py-2 text-xs font-bold text-neutral-700 hover:bg-neutral-100">Batal</button>
                    <button type="submit"
                        class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700 shadow-sm shadow-indigo-200">Update
                        Data</button>
                </div>
            </form>
        </div>
    </div>
@endsection

<script>
    // Functions Modal Detail
    function openDetailModal(kode, nama, stok, keterangan, fileUrl) {
        document.getElementById('modalKode').innerText = kode;
        document.getElementById('modalNama').innerText = nama;
        document.getElementById('modalStok').innerText = stok + ' Unit';
        document.getElementById('modalKeterangan').innerText = keterangan;

        const fileContainer = document.getElementById('modalFileContainer');
        if (fileUrl) {
            fileContainer.innerHTML = `
                <a href="${fileUrl}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 hover:underline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Buka / Unduh Lampiran
                </a>`;
        } else {
            fileContainer.innerHTML = `<span class="font-bold text-neutral-400">- Tidak ada file -</span>`;
        }

        const modal = document.getElementById('detailModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDetailModal() {
        const modal = document.getElementById('detailModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Functions Modal Tambah
    function openTambahModal() {
        const modal = document.getElementById('tambahModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeTambahModal() {
        const modal = document.getElementById('tambahModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Functions Modal Edit
    function openEditModal(actionUrl, kode, nama, stok, keterangan) {
        document.getElementById('editForm').action = actionUrl;
        document.getElementById('editKode').value = kode;
        document.getElementById('editNama').value = nama;
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

    // AJAX Live Search
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('itemSearch');
        const searchForm = document.getElementById('itemSearchForm');
        const tableContainer = document.getElementById('tableContainer');
        const btnReset = document.getElementById('btnReset');
        let timer;

        function fetchItems(url) {
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

                    if (searchInput.value.trim() !== '') {
                        btnReset.classList.remove('hidden');
                        btnReset.classList.add('inline-flex');
                    } else {
                        btnReset.classList.add('hidden');
                        btnReset.classList.remove('inline-flex');
                    }
                })
                .catch(error => console.error('Error loading data:', error));
        }

        searchInput.addEventListener('input', function() {
            clearTimeout(timer);
            timer = setTimeout(function() {
                const query = searchInput.value;
                const url = `{{ route('items.index') }}?search=${encodeURIComponent(query)}`;
                fetchItems(url);
                window.history.pushState(null, '', url);
            }, 300);
        });

        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const query = searchInput.value;
            const url = `{{ route('items.index') }}?search=${encodeURIComponent(query)}`;
            fetchItems(url);
            window.history.pushState(null, '', url);
        });
    });
</script>
