@extends('layouts.app')

@section('content')
    <style>
        .chart-filter {
            width: auto !important;
            max-width: none !important;
        }

        @media (max-width: 639px) {
            .chart-filter-form {
                display: grid;
                grid-template-columns: minmax(0, 0.8fr) minmax(0, 1.2fr);
                width: 100%;
            }

            .chart-filter {
                min-width: 0;
                width: 100% !important;
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
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white p-3 sm:p-4 shadow-sm">
                <div>
                    <p class="text-[11px] sm:text-xs font-medium text-gray-500 uppercase tracking-wide">Total Buku</p>
                    <p class="mt-0.5 text-xl sm:text-2xl font-bold text-gray-900">{{ $totalBuku }}</p>
                </div>
                <div class="flex h-7 w-7 items-center justify-center rounded-md bg-gray-100 text-sm">
                    📚
                </div>
            </div>

            {{-- Total Stok Buku --}}
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white p-3 sm:p-4 shadow-sm">
                <div>
                    <p class="text-[11px] sm:text-xs font-medium text-gray-500 uppercase tracking-wide">Total Stok Buku</p>
                    <p class="mt-0.5 text-xl sm:text-2xl font-bold text-gray-900">{{ $totalStokBuku }} <span
                            class="text-xs sm:text-sm font-normal text-gray-500">pcs</span></p>
                </div>
                <div class="flex h-7 w-7 items-center justify-center rounded-md bg-sky-50 text-sm">
                    📊
                </div>
            </div>

            {{-- Barang Masuk --}}
            <div class="rounded-lg border border-emerald-100 bg-emerald-50/40 p-3 sm:p-4 shadow-sm">
                <div>
                    <p class="text-[11px] sm:text-xs font-medium text-emerald-700 uppercase tracking-wide">Barang Masuk</p>
                </div>
                <div class="mt-2 w-full space-y-1 text-[11px] sm:text-xs">
                    <div class="flex items-center justify-between text-emerald-800">
                        <span>Buku</span><strong>{{ $totalBukuMasuk }}</strong>
                    </div>
                    <div class="flex items-center justify-between text-emerald-800"><span>Barang
                            Lainnya</span><strong>{{ $totalBarangMasuk }}</strong></div>
                    <div
                        class="flex items-center justify-between border-t border-emerald-200 pt-1 font-bold text-emerald-950">
                        <span>Semua</span><strong>{{ $totalMasuk }} unit</strong>
                    </div>
                </div>
            </div>

            {{-- Barang Keluar --}}
            <div class="rounded-lg border border-orange-100 bg-orange-50/40 p-3 sm:p-4 shadow-sm">
                <div>
                    <p class="text-[11px] sm:text-xs font-medium text-orange-700 uppercase tracking-wide">Barang Keluar</p>
                </div>
                <div class="mt-2 w-full space-y-1 text-[11px] sm:text-xs">
                    <div class="flex items-center justify-between text-orange-800">
                        <span>Buku</span><strong>{{ $totalBukuKeluar }}</strong>
                    </div>
                    <div class="flex items-center justify-between text-orange-800"><span>Barang
                            Lainnya</span><strong>{{ $totalBarangKeluar }}</strong></div>
                    <div
                        class="flex items-center justify-between border-t border-orange-200 pt-1 font-bold text-orange-950">
                        <span>Semua</span><strong>{{ $totalKeluar }} unit</strong>
                    </div>
                </div>
            </div>

        </div>

        {{-- Peringatan Stok --}}
        <div class="rounded-lg border border-gray-200 bg-white p-3 sm:p-4 shadow-sm">
            <div class="flex items-center gap-1.5 mb-1.5">
                <span class="text-sm">⚠️</span>
                <h2 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-gray-800">Peringatan Stok Menipis</h2>
            </div>

            @if ($stokMenipis->count() > 0)
                <div class="space-y-1">
                    @foreach ($stokMenipis as $buku)
                        <div
                            class="flex items-center justify-between rounded-md bg-red-50/60 border border-red-100 p-2 text-xs">
                            <span
                                class="font-medium text-gray-800 line-clamp-1 pr-2">{{ $buku->judul_buku ?? ($buku->judul ?? $buku->nama_buku) }}</span>
                            <span
                                class="shrink-0 font-semibold text-red-600 bg-white px-1.5 py-0.5 rounded border border-red-200 text-[10px]">
                                Sisa {{ $buku->stok }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-gray-500 italic">Semua stok buku dalam kondisi aman.</p>
            @endif
        </div>


        {{-- BAGIAN BAWAH: Grafik & Transaksi Terbaru --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3">

            {{-- 1. GRAFIK PERBANDINGAN TRANSAKSI --}}
            <div class="lg:col-span-5 flex flex-col rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-2">
                    <h2 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-gray-800">
                        Komposisi Transaksi
                    </h2>
                    <form method="GET" action="{{ route('dashboard') }}"
                        class="chart-filter-form flex items-center justify-end gap-1.5">
                        <label for="tahunGrafik" class="sr-only">Pilih tahun grafik</label>
                        <div class="relative" id="tahunGrafikWrapper">
                            <input type="hidden" id="tahunGrafik" name="tahun_grafik" value="{{ $tahunGrafik }}">
                            <button type="button" id="tahunGrafikButton" onclick="toggleTahunGrafik()"
                                class="chart-filter flex items-center justify-between gap-2 rounded-md border border-gray-200 bg-white py-1 pl-2 pr-2 text-[11px] sm:text-xs font-semibold text-gray-600 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                <span id="tahunGrafikLabel">{{ $tahunGrafik }}</span>
                                <span class="text-[10px]">⌄</span>
                            </button>
                            <div id="tahunGrafikMenu"
                                class="absolute left-0 top-full z-20 mt-1 hidden max-h-32 w-full min-w-[60px] overflow-y-auto rounded-md border border-gray-200 bg-white py-1 shadow-lg">
                                @for ($tahun = now()->year + 1; $tahun >= 2020; $tahun--)
                                    <button type="button" onclick="pilihTahunGrafik('{{ $tahun }}')"
                                        class="block w-full px-2 py-1.5 text-left text-[11px] text-gray-700 hover:bg-slate-100">{{ $tahun }}</button>
                                @endfor
                            </div>
                        </div>
                        <label for="bulanGrafik" class="sr-only">Pilih bulan grafik</label>
                        <div class="relative" id="bulanGrafikWrapper">
                            <input type="hidden" id="bulanGrafik" name="bulan_grafik" value="{{ $bulanGrafik }}">
                            <button type="button" id="bulanGrafikButton" onclick="toggleBulanGrafik()"
                                class="chart-filter flex items-center justify-between gap-2 rounded-md border border-gray-200 bg-white py-1 pl-2 pr-2 text-[11px] sm:text-xs font-semibold text-gray-600 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                <span
                                    id="bulanGrafikLabel">{{ $bulanGrafik ? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'][$bulanGrafik - 1] : 'Semua Bulan' }}</span>
                                <span class="text-[10px]">⌄</span>
                            </button>
                            <div id="bulanGrafikMenu"
                                class="absolute right-0 top-full z-20 mt-1 hidden max-h-32 w-full min-w-[112px] overflow-y-auto rounded-md border border-gray-200 bg-white py-1 shadow-lg">
                                <button type="button" onclick="pilihBulanGrafik('', 'Semua Bulan')"
                                    class="block w-full px-2 py-1.5 text-left text-[11px] text-gray-700 hover:bg-slate-100">Semua
                                    Bulan</button>
                                @foreach (['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'] as $index => $namaBulan)
                                    <button type="button"
                                        onclick="pilihBulanGrafik('{{ $index + 1 }}', '{{ $namaBulan }}')"
                                        class="block w-full px-2 py-1.5 text-left text-[11px] text-gray-700 hover:bg-slate-100">{{ $namaBulan }}</button>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>
                {{-- Tinggi grafik diperkecil di mobile (h-[180px]) agar tidak terlalu panjang --}}
                <div class="relative w-full h-[220px] lg:h-[250px]">
                    <canvas id="transactionChart"></canvas>
                </div>
            </div>

            {{-- 2. TRANSAKSI TERBARU --}}
            <div
                class="lg:col-span-7 flex flex-col justify-between rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div>
                    <div class="flex items-center justify-between mb-2.5">
                        <h2 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-gray-800">Transaksi Terbaru
                        </h2>
                        <a href="{{ route('transactions.index') }}"
                            class="text-xs sm:text-sm font-semibold text-gray-600 hover:text-black">
                            Lihat Semua →
                        </a>
                    </div>

                    @if ($latestTransactions->count() > 0)
                        <div class="divide-y divide-gray-100">
                            @foreach ($latestTransactions as $log)
                                <div class="py-2 first:pt-0 last:pb-0 flex items-center justify-between gap-2">
                                    <div class="space-y-0.5 min-w-0">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            @if ($log->jenis_transaksi === 'masuk')
                                                <span
                                                    class="inline-flex items-center text-[9px] font-bold text-green-700 bg-green-50 px-1 py-0.5 rounded border border-green-200 shrink-0">
                                                    📥 Masuk
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center text-[9px] font-bold text-red-700 bg-red-50 px-1 py-0.5 rounded border border-red-200 shrink-0">
                                                    📤 Keluar
                                                </span>
                                            @endif
                                            <span
                                                class="text-[10px] sm:text-xs font-mono text-gray-500 shrink-0">{{ $log->kode_transaksi }}</span>
                                        </div>
                                        <p class="text-sm sm:text-base font-semibold text-gray-800 truncate">
                                            {{ data_get($log->itemable, 'judul_buku') ?? (data_get($log->itemable, 'nama_barang') ?? (data_get($log->itemable, 'nama_buku') ?? 'Item tidak ditemukan')) }}
                                        </p>
                                        <p class="text-[11px] sm:text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($log->tanggal_transaksi)->format('d/m/Y') }}
                                        </p>
                                    </div>

                                    <div class="text-right shrink-0 pl-2">
                                        <span
                                            class="text-sm sm:text-base font-extrabold {{ $log->jenis_transaksi === 'masuk' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $log->jenis_transaksi === 'masuk' ? '+' : '-' }}{{ $log->jumlah }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-4 text-center text-xs text-gray-400">
                            Belum ada aktivitas transaksi.
                        </div>
                    @endif
                </div>
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
                    ctx.font = '700 12px Inter, sans-serif';
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
                            backgroundColor: '#000',
                            titleFont: {
                                size: 10
                            },
                            bodyFont: {
                                size: 9
                            },
                            padding: 6,
                            cornerRadius: 4
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
