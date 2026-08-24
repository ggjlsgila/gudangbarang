@extends('layouts.app')

@section('content')
    <div class="space-y-4 sm:space-y-6 antialiased">

        {{-- Header Section --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                    Data Transaksi
                </h1>
                <p class="text-xs sm:text-sm font-medium text-slate-500">
                    Kelola riwayat transaksi barang masuk dan keluar
                </p>
            </div>

            {{-- Tombol Tambah Transaksi --}}
            <button type="button" onclick="openTambahModal()"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs sm:text-sm font-bold text-white shadow-sm shadow-indigo-200 transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 w-full sm:w-auto cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Transaksi</span>
            </button>
        </div>

        {{-- Alert Notifikasi --}}
        @if (session('success'))
            <div
                class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs sm:text-sm font-semibold text-emerald-900 shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div
                class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs sm:text-sm font-semibold text-rose-900 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Container Utama Tabel & Filter --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">

            {{-- Search & Filter Form Transaksi --}}
            <div class="border-b border-slate-100 p-3 sm:p-4 bg-slate-50/50">
                <form method="GET" action="{{ route('transactions.index') }}" class="flex gap-2"
                    id="transactionSearchForm">

                    {{-- Pertahankan filter jenis_transaksi jika sedang aktif --}}
                    @if (request('jenis_transaksi'))
                        <input type="hidden" name="jenis_transaksi" value="{{ request('jenis_transaksi') }}">
                    @endif

                    {{-- Input Pencarian --}}
                    <input type="text" name="search" id="transactionSearch" value="{{ request('search') }}"
                        placeholder="Cari kode TRX, item, atau keterangan..." autocomplete="off"
                        class="w-full rounded-xl border-slate-300 text-xs sm:text-sm font-medium text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500">

                    {{-- Tombol Cari --}}
                    <button type="submit"
                        class="rounded-xl bg-indigo-600 px-4 py-2 text-xs sm:text-sm font-bold text-white hover:bg-indigo-700 transition shrink-0 cursor-pointer shadow-sm">
                        Cari
                    </button>

                    {{-- Tombol Reset --}}
                    <a href="{{ route('transactions.index') }}" id="btnReset"
                        class="{{ request('search') || request('jenis_transaksi') ? 'inline-flex' : 'hidden' }} items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-50 transition shrink-0">
                        Reset
                    </a>
                </form>
            </div>

            {{-- Tabel Data Transaksi --}}
            <div id="tableContainer">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm table-fixed">
                        <thead
                            class="bg-indigo-50/50 border-b border-indigo-100/60 text-slate-900 font-bold uppercase tracking-wider text-[10px] sm:text-xs">
                            <tr>
                                {{-- Kolom No: Pas untuk angka kecil --}}
                                <th class="px-2 py-3.5 sm:px-4 w-[8%] sm:w-[6%] text-center">NO</th>

                                {{-- Sembunyi di mobile, muncul di desktop --}}
                                <th class="hidden sm:table-cell px-3 py-3.5 sm:px-4 sm:w-[18%]">KODE TRX</th>

                                {{-- Nama Item: Lebar diperbesar sedikit agar teks tidak terlalu menumpuk --}}
                                <th class="px-3 py-3.5 sm:px-4 w-[36%] sm:w-[26%]">NAMA ITEM / BUKU</th>

                                {{-- Jenis (Masuk/Keluar) --}}
                                <th class="px-2 py-3.5 sm:px-4 w-[16%] sm:w-[12%] text-center">JENIS</th>

                                {{-- Jumlah --}}
                                <th class="px-2 py-3.5 sm:px-4 w-[14%] sm:w-[10%] text-center">JUMLAH</th>

                                {{-- Tanggal (Desktop only) --}}
                                <th class="hidden sm:table-cell px-3 py-3.5 sm:px-4 sm:w-[16%] text-center">TANGGAL</th>

                                {{-- Aksi: Diberikan ruang lebih longgar di mobile (w-[26%]) agar tombol icon tidak saling dempet --}}
                                <th class="px-2 py-3.5 sm:px-4 text-center w-[26%] sm:w-[12%]">AKSI</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($transactions as $index => $trx)
                                <tr class="transition hover:bg-indigo-50/30">
                                    <td
                                        class="whitespace-nowrap px-2 py-3.5 sm:px-4 text-center font-medium text-slate-400">
                                        {{ $transactions->firstItem() + $index }}
                                    </td>

                                    {{-- Sembunyi di mobile, muncul di desktop (sm:table-cell) --}}
                                    <td
                                        class="hidden sm:table-cell px-3 py-3.5 sm:px-4 font-mono font-semibold text-indigo-600 truncate">
                                        {{ $trx->kode_transaksi }}
                                    </td>

                                    <td class="px-3 py-3.5 sm:px-4 font-medium text-slate-700 truncate">
                                        @if ($trx->itemable)
                                            <span class="font-bold text-slate-900 block truncate">
                                                {{ $trx->itemable->nama_buku ?? ($trx->itemable->nama_barang ?? ($trx->itemable->nama_item ?? '-')) }}
                                            </span>
                                            <span class="font-mono text-[11px] text-slate-400 block">
                                                {{ $trx->itemable->kode_buku ?? ($trx->itemable->kode_barang ?? ($trx->itemable->kode_item ?? '')) }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 italic">Item telah dihapus</span>
                                        @endif
                                    </td>

                                    <td class="px-2 py-3.5 sm:px-4 text-center whitespace-nowrap">
                                        @if ($trx->jenis_transaksi === 'masuk')
                                            <span
                                                class="inline-block rounded-lg bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-wider text-emerald-700">
                                                Masuk
                                            </span>
                                        @else
                                            <span
                                                class="inline-block rounded-lg bg-amber-50 border border-amber-200 px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-wider text-amber-700">
                                                Keluar
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-2 py-3.5 sm:px-4 text-center font-bold text-slate-900 whitespace-nowrap">
                                        {{ number_format($trx->jumlah) }}
                                    </td>

                                    <td
                                        class="hidden sm:table-cell whitespace-nowrap px-3 py-3.5 sm:px-4 text-center font-medium text-slate-500">
                                        {{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d M Y') }}
                                    </td>

                                    <td class="whitespace-nowrap px-1 py-3.5 sm:px-4 text-center">
                                        <div class="inline-flex items-center justify-center gap-1.5">

                                            {{-- Detail Button (Icon Mata) --}}
                                            <button type="button" title="Detail Transaksi"
                                                onclick="openDetailModal('{{ $trx->kode_transaksi }}', '{{ addslashes($trx->itemable->nama_buku ?? ($trx->itemable->nama_barang ?? ($trx->itemable->nama_item ?? '-'))) }}', '{{ ucfirst($trx->jenis_transaksi) }}', '{{ $trx->jumlah }}', '{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d M Y') }}', '{{ addslashes($trx->keterangan ?? '-') }}')"
                                                class="inline-flex items-center justify-center rounded-lg border border-slate-200 p-1.5 text-slate-600 transition hover:border-indigo-600 hover:bg-indigo-50 hover:text-indigo-600 cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.573 16.49 16.638 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </button>

                                            {{-- Edit Button (Icon Pensil) --}}
                                            <button type="button" title="Edit Transaksi"
                                                onclick="openEditModal('{{ route('transactions.update', $trx) }}', '{{ $trx->jenis_transaksi }}', '{{ $trx->jumlah }}', '{{ $trx->tanggal_transaksi }}', '{{ addslashes($trx->keterangan ?? '') }}')"
                                                class="inline-flex items-center justify-center rounded-lg border border-slate-200 p-1.5 text-slate-600 transition hover:border-amber-500 hover:bg-amber-50 hover:text-amber-600 cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                            </button>

                                            {{-- Delete Button --}}
                                            <form method="POST" action="{{ route('transactions.destroy', $trx) }}"
                                                class="inline-flex m-0 p-0"
                                                onsubmit="return confirm('Hapus transaksi ini? Stok item akan disesuaikan kembali.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus Transaksi"
                                                    class="inline-flex items-center justify-center rounded-lg border border-slate-200 p-1.5 text-slate-600 transition hover:border-rose-500 hover:bg-rose-50 hover:text-rose-600 cursor-pointer">
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
                                        <p class="mt-2 text-xs sm:text-sm font-bold text-slate-800">Belum ada data
                                            transaksi</p>
                                        <p class="mt-0.5 text-xs font-medium text-slate-400">Silakan catat transaksi masuk
                                            atau keluar baru.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($transactions->hasPages())
                    <div class="border-t border-slate-100 px-4 py-3 bg-slate-50/50">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
            {{-- MODAL DETAIL TRANSAKSI --}}
            <div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl border border-slate-100 relative">

                        {{-- Modal Header --}}
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                            <h3 class="text-base font-bold text-slate-900">Detail Informasi Transaksi</h3>
                            <button type="button" onclick="closeDetailModal()"
                                class="text-slate-400 hover:text-slate-600 rounded-lg p-1 transition">
                                ✕
                            </button>
                        </div>

                        {{-- Modal Body --}}
                        <div class="space-y-4">
                            <div>
                                <label class="block text-[11px] font-bold tracking-wider text-slate-400 uppercase">KODE
                                    TRANSAKSI</label>
                                <p id="detailKode" class="text-sm font-bold text-indigo-600 font-mono mt-0.5"></p>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold tracking-wider text-slate-400 uppercase">NAMA
                                    ITEM /
                                    BUKU</label>
                                <p id="detailItem" class="text-sm font-bold text-slate-900 mt-0.5"></p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-[11px] font-bold tracking-wider text-slate-400 uppercase">JENIS
                                        TRANSAKSI</label>
                                    <p id="detailJenis" class="text-sm font-bold text-slate-900 uppercase mt-0.5"></p>
                                </div>

                                <div>
                                    <label
                                        class="block text-[11px] font-bold tracking-wider text-slate-400 uppercase">JUMLAH</label>
                                    <p id="detailJumlah" class="text-sm font-extrabold text-indigo-600 mt-0.5"></p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold tracking-wider text-slate-400 uppercase">TANGGAL
                                    TRANSAKSI</label>
                                <p id="detailTanggal" class="text-sm font-semibold text-slate-800 mt-0.5"></p>
                            </div>

                            <div>
                                <label
                                    class="block text-[11px] font-bold tracking-wider text-slate-400 uppercase">KETERANGAN</label>
                                <p id="detailKeterangan"
                                    class="text-xs font-medium text-slate-600 mt-0.5 whitespace-pre-line">
                                </p>
                            </div>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="mt-6 border-t border-slate-100 pt-4 text-right">
                            <button type="button" onclick="closeDetailModal()"
                                class="rounded-xl bg-slate-100 px-5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-200 transition">
                                Tutup
                            </button>
                        </div>

                    </div>
                </div>
            </div>
            {{-- MODAL EDIT TRANSAKSI --}}
            <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl border border-slate-100 relative">

                        {{-- Modal Header --}}
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                            <h3 class="text-base font-bold text-slate-900">Edit Data Transaksi</h3>
                            <button type="button" onclick="closeEditModal()"
                                class="text-slate-400 hover:text-slate-600 rounded-lg p-1 transition">
                                ✕
                            </button>
                        </div>

                        {{-- Form Edit --}}
                        <form id="editForm" method="POST" action="">
                            @csrf
                            @method('PUT')

                            <div class="space-y-4">
                                {{-- Jenis Transaksi --}}
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jenis
                                        Transaksi</label>
                                    <select id="editJenis" name="jenis_transaksi"
                                        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                        required>
                                        <option value="masuk">Masuk</option>
                                        <option value="keluar">Keluar</option>
                                    </select>
                                </div>

                                {{-- Jumlah --}}
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jumlah</label>
                                    <input type="number" id="editJumlah" name="jumlah" min="1"
                                        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                        required>
                                </div>

                                {{-- Tanggal --}}
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tanggal
                                        Transaksi</label>
                                    <input type="date" id="editTanggal" name="tanggal"
                                        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                        required>
                                </div>

                                {{-- Keterangan --}}
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Keterangan</label>
                                    <textarea id="editKeterangan" name="keterangan" rows="3"
                                        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                                </div>
                            </div>

                            {{-- Modal Footer --}}
                            <div class="mt-6 flex items-center justify-end gap-2 border-t border-slate-100 pt-4">
                                <button type="button" onclick="closeEditModal()"
                                    class="rounded-xl bg-slate-100 px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-200 transition">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700 transition shadow-md shadow-indigo-200">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            {{-- MODAL TAMBAH TRANSAKSI --}}
            <div id="tambahModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl border border-slate-100 relative">

                        {{-- Modal Header --}}
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                            <h3 class="text-base font-bold text-slate-900">Tambah Transaksi Baru</h3>
                            <button type="button" onclick="closeTambahModal()"
                                class="text-slate-400 hover:text-slate-600 rounded-lg p-1 transition">
                                ✕
                            </button>
                        </div>

                        {{-- Form Tambah --}}
                        <form action="{{ route('transactions.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="item_id" id="realItemId" value="">

                            <div class="space-y-4">
                                {{-- Pilih Kategori (Buku / Item) --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                        Kategori Barang
                                    </label>
                                    <select id="selectKategori" name="kategori" onchange="toggleItemOptions()"
                                        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                        required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <option value="buku">Buku</option>
                                        <option value="item">Barang Lainnya</option>
                                    </select>
                                </div>

                                {{-- Container Select Buku --}}
                                <div id="containerBuku" class="hidden">
                                    <label
                                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Pilih
                                        Buku</label>
                                    <select id="selectBuku" placeholder="Cari atau pilih buku..." autocomplete="off">
                                        <option value="">-- Cari / Pilih Buku --</option>
                                        @foreach ($books as $book)
                                            <option value="{{ $book->id }}">
                                                {{ $book->judul ?? ($book->judul_buku ?? $book->nama_buku) }} (Stok:
                                                {{ $book->stok }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Container Select Item --}}
                                <div id="containerItem" class="hidden">
                                    <label
                                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Pilih
                                        Barang</label>
                                    <select id="selectItem" placeholder="Cari atau pilih barang..." autocomplete="off">
                                        <option value="">-- Cari / Pilih Barang --</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->nama_item ?? ($item->nama_barang ?? $item->nama) }} (Stok:
                                                {{ $item->stok }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Jenis Transaksi --}}
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jenis
                                        Transaksi</label>
                                    <select name="jenis_transaksi"
                                        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                        required>
                                        <option value="masuk">Masuk</option>
                                        <option value="keluar">Keluar</option>
                                    </select>
                                </div>

                                {{-- Jumlah --}}
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jumlah</label>
                                    <input type="number" name="jumlah" min="1"
                                        placeholder="Masukkan jumlah unit"
                                        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                        required>
                                </div>

                                {{-- Tanggal Transaksi (SUDAH DIPERBAIKI: name="tanggal_transaksi") --}}
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tanggal
                                        Transaksi</label>
                                    <input type="date" name="tanggal_transaksi" value="{{ date('Y-m-d') }}"
                                        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                        required>
                                </div>

                                {{-- Keterangan --}}
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Keterangan</label>
                                    <textarea name="keterangan" rows="3" placeholder="Tambahkan catatan/keterangan jika ada..."
                                        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                                </div>
                            </div>

                            {{-- Modal Footer --}}
                            <div class="mt-6 flex items-center justify-end gap-2 border-t border-slate-100 pt-4">
                                <button type="button" onclick="closeTambahModal()"
                                    class="rounded-xl bg-slate-100 px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-200 transition">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700 transition shadow-md shadow-indigo-200">
                                    Simpan Transaksi
                                </button>
                            </div>
                        </form>
                    @endsection

                    {{-- ========================================== --}}
                    {{-- JAVASCRIPT HANDLER (TERMASUK LIVE SEARCH AJAX) --}}
                    {{-- ========================================== --}}
                    <script>
                        // Variable Global TomSelect
                        let tomBuku = null;
                        let tomItem = null;

                        // Modal Tambah
                        function openTambahModal() {
                            document.getElementById('tambahModal').classList.remove('hidden');
                        }

                        function closeTambahModal() {
                            document.getElementById('tambahModal').classList.add('hidden');
                        }

                        // Modal Detail (Mata)
                        function openDetailModal(kode, item, jenis, jumlah, tanggal, keterangan) {
                            document.getElementById('detailKode').innerText = kode;
                            document.getElementById('detailItem').innerText = item;
                            document.getElementById('detailJenis').innerText = jenis;
                            document.getElementById('detailJumlah').innerText = jumlah;
                            document.getElementById('detailTanggal').innerText = tanggal;
                            document.getElementById('detailKeterangan').innerText = keterangan;
                            document.getElementById('detailModal').classList.remove('hidden');
                        }

                        function closeDetailModal() {
                            document.getElementById('detailModal').classList.add('hidden');
                        }

                        // Modal Edit (Pensil)
                        function openEditModal(actionUrl, jenis, jumlah, tanggal, keterangan) {
                            document.getElementById('editForm').action = actionUrl;
                            document.getElementById('editJenis').value = jenis;
                            document.getElementById('editJumlah').value = jumlah;
                            document.getElementById('editTanggal').value = tanggal;
                            document.getElementById('editKeterangan').value = keterangan;
                            document.getElementById('editModal').classList.remove('hidden');
                        }

                        function closeEditModal() {
                            document.getElementById('editModal').classList.add('hidden');
                        }

                        // Switch Item/Buku di Modal Tambah (Sudah diperbaiki)
                        function toggleItemOptions() {
                            const kategori = document.getElementById('selectKategori').value;
                            const containerBuku = document.getElementById('containerBuku');
                            const containerItem = document.getElementById('containerItem');
                            const realItemId = document.getElementById('realItemId');

                            if (kategori === 'buku') {
                                containerBuku.classList.remove('hidden');
                                containerItem.classList.add('hidden');
                                // Ambil value dari TomSelect buku jika sudah terpilih
                                if (tomBuku) {
                                    realItemId.value = tomBuku.getValue();
                                }
                            } else if (kategori === 'item') {
                                containerItem.classList.remove('hidden');
                                containerBuku.classList.add('hidden');
                                // Ambil value dari TomSelect item jika sudah terpilih
                                if (tomItem) {
                                    realItemId.value = tomItem.getValue();
                                }
                            } else {
                                containerBuku.classList.add('hidden');
                                containerItem.classList.add('hidden');
                                realItemId.value = '';
                            }
                        }

                        // Inisialisasi Utama Aplikasi
                        document.addEventListener('DOMContentLoaded', function() {

                            // 1. Inisialisasi Tom Select
                            const selectBukuEl = document.getElementById('selectBuku');
                            const selectItemEl = document.getElementById('selectItem');

                            if (selectBukuEl) {
                                tomBuku = new TomSelect("#selectBuku", {
                                    dropdownParent: 'body',
                                    create: false,
                                    sortField: {
                                        field: "text",
                                        order: "asc"
                                    },
                                    onChange: function(value) {
                                        // Isi input hidden setiap kali user memilih item
                                        document.getElementById('realItemId').value = value;
                                    }
                                });
                            }

                            if (selectItemEl) {
                                tomItem = new TomSelect("#selectItem", {
                                    create: false,
                                    sortField: {
                                        field: "text",
                                        order: "asc"
                                    },
                                    onChange: function(value) {
                                        // Isi input hidden setiap kali user memilih item
                                        document.getElementById('realItemId').value = value;
                                    }
                                });
                            }

                            // 2. AJAX Live Search & Event Handling
                            const searchInput = document.getElementById('transactionSearch');
                            const searchForm = document.getElementById('transactionSearchForm');
                            const tableContainer = document.getElementById('tableContainer');
                            const btnReset = document.getElementById('btnReset');
                            let timer;

                            function fetchTransactions(url) {
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

                                        // Tampilkan atau sembunyikan tombol Reset
                                        const jenisFilter = document.querySelector('input[name="jenis_transaksi"]');
                                        const hasJenis = jenisFilter && jenisFilter.value;

                                        if ((searchInput && searchInput.value.trim() !== '') || hasJenis) {
                                            if (btnReset) {
                                                btnReset.classList.remove('hidden');
                                                btnReset.classList.add('inline-flex');
                                            }
                                        } else {
                                            if (btnReset) {
                                                btnReset.classList.add('hidden');
                                                btnReset.classList.remove('inline-flex');
                                            }
                                        }
                                    })
                                    .catch(error => console.error('Error loading data:', error));
                            }

                            // Live Search Input Handler
                            if (searchInput) {
                                searchInput.addEventListener('input', function() {
                                    clearTimeout(timer);
                                    timer = setTimeout(function() {
                                        const query = searchInput.value;
                                        const urlObj = new URL("{{ route('transactions.index') }}", window.location
                                            .origin);

                                        if (query) {
                                            urlObj.searchParams.set('search', query);
                                        }

                                        const jenisFilter = document.querySelector('input[name="jenis_transaksi"]');
                                        if (jenisFilter && jenisFilter.value) {
                                            urlObj.searchParams.set('jenis_transaksi', jenisFilter.value);
                                        }

                                        fetchTransactions(urlObj.toString());
                                        window.history.pushState(null, '', urlObj.toString());
                                    }, 300);
                                });
                            }

                            if (searchForm) {
                                searchForm.addEventListener('submit', function(e) {
                                    e.preventDefault();
                                    const query = searchInput.value;
                                    const urlObj = new URL("{{ route('transactions.index') }}", window.location.origin);

                                    if (query) {
                                        urlObj.searchParams.set('search', query);
                                    }

                                    const jenisFilter = document.querySelector('input[name="jenis_transaksi"]');
                                    if (jenisFilter && jenisFilter.value) {
                                        urlObj.searchParams.set('jenis_transaksi', jenisFilter.value);
                                    }

                                    fetchTransactions(urlObj.toString());
                                    window.history.pushState(null, '', urlObj.toString());
                                });
                            }

                            // Event Delegation untuk Pagination Link
                            if (tableContainer) {
                                tableContainer.addEventListener('click', function(e) {
                                    const link = e.target.closest('a');
                                    if (link && link.getAttribute('href') && link.getAttribute('href') !== '#') {
                                        if (link.closest('.border-t') || link.closest('nav')) {
                                            e.preventDefault();
                                            const targetUrl = link.getAttribute('href');
                                            fetchTransactions(targetUrl);
                                            window.history.pushState(null, '', targetUrl);
                                        }
                                    }
                                });
                            }
                        });
                    </script>
