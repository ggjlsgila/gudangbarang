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
                                                {{ $trx->itemable->judul_buku ?? ($trx->itemable->nama_buku ?? ($trx->itemable->nama_barang ?? ($trx->itemable->nama_item ?? '-'))) }}
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
                                        <div class="relative inline-block text-left" x-data="{ open: false, dropUp: false, menuTop: 0, menuLeft: 0 }">
                                            <button type="button" title="Menu Aksi"
                                                @click="
                                                    let rect = $el.getBoundingClientRect();
                                                    let spaceBelow = window.innerHeight - rect.bottom;
                                                    dropUp = spaceBelow < 220;
                                                    menuTop = dropUp ? rect.top - 140 : rect.bottom + 8;
                                                    menuLeft = Math.max(8, Math.min(rect.right - 128, window.innerWidth - 136));
                                                    open = !open;
                                                "
                                                @click.away="open = false"
                                                class="inline-flex items-center justify-center rounded-lg border border-slate-200 p-1.5 text-slate-600 transition hover:border-slate-300 hover:bg-slate-100 hover:text-indigo-600 cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75 0 0 1 0 1.5Z" />
                                                </svg>
                                            </button>

                                            <template x-teleport="body">
                                                <div x-show="open" x-transition
                                                    :style="`top: ${menuTop}px; left: ${menuLeft}px;`"
                                                    class="fixed z-[9999] w-32 rounded-xl border border-slate-200 bg-white py-1 text-left shadow-lg focus:outline-none"
                                                    style="display: none;">
                                                    <button type="button"
                                                        onclick="openDetailModal('{{ $trx->kode_transaksi }}', '{{ addslashes($trx->itemable->judul_buku ?? ($trx->itemable->nama_buku ?? ($trx->itemable->nama_barang ?? ($trx->itemable->nama_item ?? '-')))) }}', '{{ ucfirst($trx->jenis_transaksi) }}', '{{ $trx->jumlah }}', '{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d M Y') }}', '{{ addslashes($trx->keterangan ?? '-') }}')"
                                                        @click="open = false"
                                                        class="flex w-full items-center gap-2 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition">
                                                        <span>Detail</span>
                                                    </button>

                                                    <button type="button"
                                                        onclick="openEditModal('{{ route('transactions.update', $trx) }}', '{{ $trx->jenis_transaksi }}', '{{ $trx->jumlah }}', '{{ $trx->tanggal_transaksi }}', '{{ addslashes($trx->keterangan ?? '') }}')"
                                                        @click="open = false"
                                                        class="flex w-full items-center gap-2 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition">
                                                        <span>Edit</span>
                                                    </button>

                                                    <form method="POST" action="{{ route('transactions.destroy', $trx) }}"
                                                        data-confirm-message="Hapus transaksi ini? Stok item akan disesuaikan kembali."
                                                        onsubmit="return openDeleteModal(this)" class="block m-0 p-0">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="flex w-full items-center gap-2 px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">
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
            <div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50"
                onclick="closeDetailModal()">
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl border border-slate-100 relative"
                        onclick="event.stopPropagation()">

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
            <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50" onclick="closeEditModal()">
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl border border-slate-100 relative"
                        onclick="event.stopPropagation()">

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
                                    <input type="date" id="editTanggal" name="tanggal_transaksi"
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
            <div id="tambahModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50"
                onclick="closeTambahModal()">
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl border border-slate-100 relative"
                        onclick="event.stopPropagation()">

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

                        {{-- ========================================== --}}
                        {{-- JAVASCRIPT HANDLER (TERMASUK LIVE SEARCH AJAX) --}}
                        {{-- ========================================== --}}
                        <script>
                            // Variable Global TomSelect
                            let tomBuku = null;
                            let tomItem = null;

                            // Modal Tambah
                            function openTambahModal() {
                                closeDetailModal();
                                closeEditModal();
                                document.getElementById('tambahModal').classList.remove('hidden');
                            }

                            function closeTambahModal() {
                                document.getElementById('tambahModal').classList.add('hidden');
                            }

                            // Modal Detail (Mata)
                            function openDetailModal(kode, item, jenis, jumlah, tanggal, keterangan) {
                                closeTambahModal();
                                closeEditModal();
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
                                closeTambahModal();
                                closeDetailModal();
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

                            document.addEventListener('keydown', function(event) {
                                if (event.key !== 'Escape') {
                                    return;
                                }

                                closeDetailModal();
                                closeEditModal();
                                closeTambahModal();
                            });

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
                    @endsection
