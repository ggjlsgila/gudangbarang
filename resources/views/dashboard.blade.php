@extends('layouts.app')

@section('content')
    <style>
        .chart-filter {
            width: auto !important;
            max-width: none !important;
        }

        @media (max-width: 639px) {
            .chart-filter-form {
                width: auto;
            }

            .chart-filter {
                min-width: 0;
                width: auto !important;
            }
        }
    </style>

    <div class="space-y-3 pb-6">

        {{-- Header Ringkas --}}
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-xs sm:text-sm text-gray-500">Ringkasan stok dan aktivitas barang.</p>
        </div>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5">

            {{-- Total Buku --}}
            <div class="rounded-lg border border-blue-100 border-l-4 border-l-blue-500 bg-blue-50/40 p-3 sm:p-4 shadow-sm">

                <!-- Header & Ikon -->
                <div class="flex items-center justify-between">
                    <p class="text-[11px] sm:text-xs font-medium text-blue-700 uppercase tracking-wide">Total Buku</p>
                    <div class="flex h-7 w-7 items-center justify-center rounded bg-blue-100/80 text-sm">
                        📚
                    </div>
                </div>

                <!-- Total Angka Utama -->
                <div class="mt-2">
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-950">
                        {{ $totalBuku }} <span class="text-xs font-normal text-blue-700">judul</span>
                    </h3>
                </div>

                <!-- Keterangan Bawah (Penyeimbang Tinggi Card) -->
                <div
                    class="mt-3 flex items-center gap-2 border-t border-blue-200/60 pt-2 text-[11px] sm:text-xs text-blue-800">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                    <span>Master Data Aktif</span>
                </div>

            </div>

            {{-- Total Stok Buku --}}
            <div class="rounded-lg border border-blue-100 border-l-4 border-l-blue-500 bg-blue-50/40 p-3 sm:p-4 shadow-sm">

                <!-- Header & Ikon -->
                <div class="flex items-center justify-between">
                    <p class="text-[11px] sm:text-xs font-medium text-blue-700 uppercase tracking-wide">Total Stok Buku</p>
                    <div class="flex h-7 w-7 items-center justify-center rounded bg-blue-100/80 text-sm">
                        📊
                    </div>
                </div>

                <!-- Total Angka Utama -->
                <div class="mt-2">
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-950">
                        {{ $totalStokBuku }} <span class="text-xs font-normal text-blue-700">pcs</span>
                    </h3>
                </div>

                <!-- Keterangan Bawah (Penyeimbang Tinggi Card) -->
                <div
                    class="mt-3 flex items-center gap-2 border-t border-blue-200/60 pt-2 text-[11px] sm:text-xs text-blue-800">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                    <span>Akumulasi Stok Fisik</span>
                </div>

            </div>

            {{-- Barang Masuk --}}
            <div
                class="rounded-lg border border-emerald-100 border-l-4 border-l-emerald-500 bg-emerald-50/40 p-3 sm:p-4 shadow-sm">

                <!-- Header & Ikon -->
                <div class="flex items-center justify-between">
                    <p class="text-[11px] sm:text-xs font-medium text-emerald-700 uppercase tracking-wide">Barang Masuk</p>
                    <div class="flex h-7 w-7 items-center justify-center rounded bg-emerald-100 text-emerald-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </div>
                </div>

                <!-- Total Angka Utama -->
                <div class="mt-2">
                    <h3 class="text-2xl sm:text-3xl font-bold text-emerald-950">
                        {{ $totalMasuk }} <span class="text-xs font-normal text-emerald-700">unit</span>
                    </h3>
                </div>

                <!-- Rincian di Bawah -->
                <div
                    class="mt-3 flex items-center gap-3 border-t border-emerald-200/60 pt-2 text-[11px] sm:text-xs text-emerald-800">
                    <div>Buku: <strong class="text-emerald-950">{{ $totalBukuMasuk }}</strong></div>
                    <span>•</span>
                    <div>Lainnya: <strong class="text-emerald-950">{{ $totalBarangMasuk }}</strong></div>
                </div>

            </div>

            {{-- Barang Keluar --}}
            <div
                class="rounded-lg border border-orange-100 border-l-4 border-l-orange-400 bg-orange-50/40 p-3 sm:p-4 shadow-sm">

                <!-- Header & Ikon -->
                <div class="flex items-center justify-between">
                    <p class="text-[11px] sm:text-xs font-medium text-orange-700 uppercase tracking-wide">Barang Keluar</p>
                    <div class="flex h-7 w-7 items-center justify-center rounded bg-orange-100 text-orange-700">
                        <!-- Ikon Panah ke Atas (Keluar) -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                    </div>
                </div>

                <!-- Total Angka Utama -->
                <div class="mt-2">
                    <h3 class="text-2xl sm:text-3xl font-bold text-orange-950">
                        {{ $totalKeluar }} <span class="text-xs font-normal text-orange-700">unit</span>
                    </h3>
                </div>

                <!-- Rincian di Bawah -->
                <div
                    class="mt-3 flex items-center gap-3 border-t border-orange-200/60 pt-2 text-[11px] sm:text-xs text-orange-800">
                    <div>Buku: <strong class="text-orange-950">{{ $totalBukuKeluar }}</strong></div>
                    <span>•</span>
                    <div>Lainnya: <strong class="text-orange-950">{{ $totalBarangKeluar }}</strong></div>
                </div>

            </div>

        </div>

        {{-- Peringatan Stok --}}
        <div
            class="w-full min-w-0 rounded-xl border border-red-500/60 bg-amber-50/20 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200">

            <!-- Header Title & Jumlah Badge -->
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-gray-800 flex items-center gap-2">
                    <span class="flex h-2 w-2 rounded-full bg-amber-500 animate-pulse"></span>
                    ⚠️ Peringatan Stok Menipis
                </h2>

                @if ($stokMenipis->count() > 0)
                    <span
                        class="text-[10px] sm:text-xs font-bold text-amber-800 bg-amber-100/80 px-2.5 py-0.5 rounded-full border border-amber-200 shrink-0">
                        {{ $stokMenipis->count() }} Buku
                    </span>
                @endif
            </div>

            @if ($stokMenipis->count() > 0)
                <!-- Tinggi dikunci di max-h-52 (pas untuk 4 baris item) -->
                <div
                    class="space-y-1.5 max-h-52 overflow-y-auto pr-1.5 [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-gray-200 [&::-webkit-scrollbar-track]:bg-transparent">
                    @foreach ($stokMenipis as $buku)
                        <div
                            class="flex items-center justify-between gap-3 rounded-lg border border-gray-100 bg-white p-2.5 hover:border-amber-200 hover:shadow-xs transition-all">

                            <!-- Judul Buku -->
                            <span class="text-xs sm:text-sm font-medium text-gray-700 truncate min-w-0 flex-1"
                                title="{{ $buku->judul_buku ?? ($buku->judul ?? $buku->nama_buku) }}">
                                {{ $buku->judul_buku ?? ($buku->judul ?? $buku->nama_buku) }}
                            </span>

                            <!-- Badge Sisa Stok -->
                            <span
                                class="shrink-0 font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md border border-rose-100 text-[10px] sm:text-xs">
                                Sisa {{ $buku->stok }}
                            </span>

                        </div>
                    @endforeach
                </div>
            @else
                <p class="py-3 text-center text-xs text-gray-400 italic">Semua stok buku dalam kondisi aman.</p>
            @endif

        </div>
        {{-- =========================================
            BAGIAN BAWAH: GRAFIK & TRANSAKSI TERBARU
        {{-- BAGIAN BAWAH: Grafik & Transaksi Terbaru --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3">

            {{-- 1. GRAFIK PERBANDINGAN TRANSAKSI --}}
            <div
                class="lg:col-span-5 flex flex-col justify-between rounded-xl border border-gray-100 bg-white p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200">

                <!-- Header Title & Filters -->
                <div class="flex items-center justify-between gap-2 mb-3">
                    <h2
                        class="min-w-0 text-xs sm:text-sm font-bold uppercase tracking-wider text-gray-800 flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                        Komposisi Transaksi
                    </h2>

                    <form method="GET" action="{{ route('dashboard') }}"
                        class="chart-filter-form flex shrink-0 items-center justify-end gap-1.5">
                        <label for="tahunGrafik" class="sr-only">Pilih tahun grafik</label>

                        <!-- Filter Tahun -->
                        <div class="relative" id="tahunGrafikWrapper">
                            <input type="hidden" id="tahunGrafik" name="tahun_grafik" value="{{ $tahunGrafik }}">
                            <button type="button" id="tahunGrafikButton" onclick="toggleTahunGrafik()"
                                class="chart-filter flex items-center justify-between gap-1.5 rounded-lg border border-gray-200 bg-gray-50/80 px-2.5 py-1 text-[11px] sm:text-xs font-semibold text-gray-700 hover:bg-gray-100 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-colors">
                                <span id="tahunGrafikLabel">{{ $tahunGrafik }}</span>
                                <svg class="h-3 w-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="tahunGrafikMenu"
                                class="absolute left-0 top-full z-20 mt-1 hidden max-h-36 w-full min-w-[70px] overflow-y-auto rounded-lg border border-gray-100 bg-white py-1 shadow-lg ring-1 ring-black/5">
                                @for ($tahun = now()->year + 1; $tahun >= 2020; $tahun--)
                                    <button type="button" onclick="pilihTahunGrafik('{{ $tahun }}')"
                                        class="block w-full px-3 py-1.5 text-left text-[11px] text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">{{ $tahun }}</button>
                                @endfor
                            </div>
                        </div>

                        <!-- Filter Bulan -->
                        <label for="bulanGrafik" class="sr-only">Pilih bulan grafik</label>
                        <div class="relative" id="bulanGrafikWrapper">
                            <input type="hidden" id="bulanGrafik" name="bulan_grafik" value="{{ $bulanGrafik }}">
                            <button type="button" id="bulanGrafikButton" onclick="toggleBulanGrafik()"
                                class="chart-filter flex items-center justify-between gap-1.5 rounded-lg border border-gray-200 bg-gray-50/80 px-2.5 py-1 text-[11px] sm:text-xs font-semibold text-gray-700 hover:bg-gray-100 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-colors">
                                <span id="bulanGrafikLabel">
                                    {{ $bulanGrafik ? ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$bulanGrafik - 1] : 'Semua Bulan' }}
                                </span>
                                <svg class="h-3 w-3 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="bulanGrafikMenu"
                                class="absolute right-0 top-full z-20 mt-1 hidden max-h-36 w-full min-w-[120px] overflow-y-auto rounded-lg border border-gray-100 bg-white py-1 shadow-lg ring-1 ring-black/5">
                                <button type="button" onclick="pilihBulanGrafik('', 'Semua Bulan')"
                                    class="block w-full px-3 py-1.5 text-left text-[11px] text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">Semua
                                    Bulan</button>
                                @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $index => $namaBulan)
                                    <button type="button"
                                        onclick="pilihBulanGrafik('{{ $index + 1 }}', '{{ $namaBulan }}')"
                                        class="block w-full px-3 py-1.5 text-left text-[11px] text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">{{ $namaBulan }}</button>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Area Chart Canvas -->
                <div class="relative my-auto w-full h-[220px] lg:h-[250px] flex items-center justify-center">
                    <canvas id="transactionChart"></canvas>
                </div>

            </div>

            {{-- 2. TRANSAKSI TERBARU --}}
            <div
                class="lg:col-span-7 flex flex-col justify-between rounded-xl border border-blue-100 border-l-4 border-l-blue-500 bg-blue-50/40 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200">

                <!-- Header Title & Link -->
                <div class="flex items-center justify-between mb-3">
                    <h2
                        class="text-xs sm:text-sm font-bold uppercase tracking-wider text-gray-800 flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                        Transaksi Terbaru
                    </h2>
                    <a href="{{ route('transactions.index') }}"
                        class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors flex items-center gap-1">
                        Lihat Semua <span>→</span>
                    </a>
                </div>

                @if ($latestTransactions->count() > 0)
                    <div class="divide-y divide-blue-100/60">
                        @foreach ($latestTransactions as $log)
                            <div
                                class="py-2.5 first:pt-0 last:pb-0 flex items-center justify-between gap-3 {{ $loop->index > 0 ? 'hidden md:flex' : '' }}">

                                <!-- Info Transaksi & Nama Item -->
                                <div class="space-y-0.5 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        @if ($log->jenis_transaksi === 'masuk')
                                            <span
                                                class="inline-flex items-center text-[10px] font-bold text-emerald-700 bg-emerald-100/80 px-2 py-0.5 rounded-md shrink-0">
                                                📥 Masuk
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center text-[10px] font-bold text-rose-700 bg-rose-100/80 px-2 py-0.5 rounded-md shrink-0">
                                                📤 Keluar
                                            </span>
                                        @endif
                                        <span class="text-[10px] sm:text-xs font-mono text-gray-400 shrink-0">
                                            {{ $log->kode_transaksi }}
                                        </span>
                                    </div>

                                    <p class="text-xs sm:text-sm font-semibold text-gray-800 truncate">
                                        {{ data_get($log->itemable, 'judul_buku') ?? (data_get($log->itemable, 'nama_barang') ?? (data_get($log->itemable, 'nama_buku') ?? 'Item tidak ditemukan')) }}
                                    </p>

                                    <p class="text-[11px] text-gray-400">
                                        {{ \Carbon\Carbon::parse($log->tanggal_transaksi)->format('d/m/Y') }}
                                    </p>
                                </div>

                                <!-- Badge Jumlah Angka Kanan -->
                                <div class="text-right shrink-0">
                                    <span
                                        class="inline-block px-2.5 py-1 rounded-lg text-xs sm:text-sm font-bold {{ $log->jenis_transaksi === 'masuk' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        {{ $log->jenis_transaksi === 'masuk' ? '+' : '-' }}{{ $log->jumlah }}
                                    </span>
                                </div>

                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-6 text-center text-xs text-gray-400">
                        Belum ada aktivitas transaksi.
                    </div>
                @endif

            </div>

        </div>

    </div>

    {{-- SCRIPT CHART.JS --}}

    <script>
        function toggleBulanGrafik() {
            document.getElementById('bulanGrafikMenu').classList.toggle('hidden');
        }

        function toggleTahunGrafik() {
            document.getElementById('tahunGrafikMenu').classList.toggle('hidden');
        }

        function pilihTahunGrafik(value) {
            document.getElementById('tahunGrafik').value = value;
            document.getElementById('tahunGrafikLabel').textContent = value;
            document.getElementById('tahunGrafikMenu').classList.add('hidden');
            document.getElementById('tahunGrafikButton').form?.submit();
        }

        function pilihBulanGrafik(value, label) {
            document.getElementById('bulanGrafik').value = value;
            document.getElementById('bulanGrafikLabel').textContent = label;
            document.getElementById('bulanGrafikMenu').classList.add('hidden');
            document.getElementById('bulanGrafikButton').form?.submit();
        }

        document.addEventListener('click', function(event) {
            const wrapper = document.getElementById('bulanGrafikWrapper');

            if (wrapper && !wrapper.contains(event.target)) {
                document.getElementById('bulanGrafikMenu').classList.add('hidden');
            }

            const yearWrapper = document.getElementById('tahunGrafikWrapper');

            if (yearWrapper && !yearWrapper.contains(event.target)) {
                document.getElementById('tahunGrafikMenu').classList.add('hidden');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('transactionChart').getContext('2d');
            const centerTextPlugin = {
                id: 'centerText',
                afterDraw(chart) {
                    const firstArc = chart.getDatasetMeta(0).data[0];

                    if (!firstArc) {
                        return;
                    }

                    const {
                        ctx
                    } = chart;
                    ctx.save();
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillStyle = '#0F172A';
                    ctx.font = 'bold 16px Inter, sans-serif';
                    ctx.strokeStyle = '#FFFFFF';
                    ctx.lineWidth = 3;
                    ctx.strokeText(@json($labelBulanGrafik), firstArc.x, firstArc.y);
                    ctx.fillText(@json($labelBulanGrafik), firstArc.x, firstArc.y);
                    ctx.restore();
                }
            };

            new Chart(ctx, {
                type: 'doughnut',
                data: {!! json_encode($chartData) !!},
                plugins: [centerTextPlugin],
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: @json($chartHasData),
                            position: window.innerWidth < 640 ? 'bottom' : 'right',
                            align: 'start',
                            labels: {
                                boxWidth: 9,
                                boxHeight: 9,
                                usePointStyle: true,
                                pointStyle: 'rectRounded',
                                padding: 12,
                                font: {
                                    size: 11,
                                    weight: '600'
                                },
                                color: '#64748B'
                            }
                        },
                        tooltip: {
                            backgroundColor: '#FFFFFF',
                            borderColor: '#E2E8F0',
                            borderWidth: 1,
                            titleColor: '#0F172A',
                            bodyColor: '#475569',
                            titleFont: {
                                size: 11,
                                weight: '700'
                            },
                            bodyFont: {
                                size: 11,
                                weight: '600'
                            },
                            padding: 10,
                            cornerRadius: 6,
                            position: 'nearest',
                            yAlign: 'top',
                            xAlign: 'center',
                            callbacks: {
                                title: function(items) {
                                    return items[0].label;
                                },
                                label: function(context) {
                                    return 'Jumlah: ' + context.formattedValue;
                                }
                            }
                        }
                    },
                    cutout: '66%',
                    layout: {
                        padding: 8
                    }
                }
            });
        });
    </script>
@endsection
