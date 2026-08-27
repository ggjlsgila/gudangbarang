@extends('layouts.app')

@section('content')
    <div class="space-y-3 pb-6">

        {{-- Header Ringkas --}}
        <div>
            <h1 class="text-lg font-bold text-gray-900">Dashboard</h1>
            <p class="text-[11px] text-gray-500">Ringkasan stok dan aktivitas barang.</p>
        </div>

        {{-- Stat Cards: Di Mobile 2 Kolom Sejajar yang Compact, di Desktop 3 Kolom --}}
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-2.5">

            {{-- Total Buku --}}
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white p-3 shadow-sm">
                <div>
                    <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Total Buku</p>
                    <p class="mt-0.5 text-lg font-bold text-gray-900">{{ $totalBuku }}</p>
                </div>
                <div class="flex h-7 w-7 items-center justify-center rounded-md bg-gray-100 text-sm">
                    📚
                </div>
            </div>

            {{-- Barang Lainnya --}}
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white p-3 shadow-sm">
                <div>
                    <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Item Lain</p>
                    <p class="mt-0.5 text-lg font-bold text-gray-900">{{ $totalBarang }}</p>
                </div>
                <div class="flex h-7 w-7 items-center justify-center rounded-md bg-gray-100 text-sm">
                    📦
                </div>
            </div>

            {{-- Total Stok Buku (Di mobile melebar memenuhi 2 kolom bawah) --}}
            <div
                class="col-span-2 lg:col-span-1 flex items-center justify-between rounded-lg border border-gray-200 bg-white p-3 shadow-sm">
                <div>
                    <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Total Stok Buku</p>
                    <p class="mt-0.5 text-lg font-bold text-gray-900">{{ $totalStokBuku }} <span
                            class="text-[11px] font-normal text-gray-500">pcs</span></p>
                </div>
                <div class="flex h-7 w-7 items-center justify-center rounded-md bg-gray-100 text-sm">
                    📊
                </div>
            </div>

        </div>

        {{-- Peringatan Stok --}}
        <div class="rounded-lg border border-gray-200 bg-white p-3 shadow-sm">
            <div class="flex items-center gap-1.5 mb-1.5">
                <span class="text-sm">⚠️</span>
                <h2 class="text-[11px] font-bold uppercase tracking-wider text-gray-800">Peringatan Stok Menipis</h2>
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
            <div class="lg:col-span-4 flex flex-col rounded-lg border border-gray-200 bg-white p-3 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-[11px] font-bold uppercase tracking-wider text-gray-800">
                        📊 Perbandingan Transaksi
                    </h2>
                </div>
                {{-- Tinggi grafik diperkecil di mobile (h-[180px]) agar tidak terlalu panjang --}}
                <div class="relative w-full h-[180px] lg:h-[220px]">
                    <canvas id="transactionChart"></canvas>
                </div>
            </div>

            {{-- 2. TRANSAKSI TERBARU --}}
            <div
                class="lg:col-span-8 flex flex-col justify-between rounded-lg border border-gray-200 bg-white p-3 shadow-sm">
                <div>
                    <div class="flex items-center justify-between mb-2.5">
                        <h2 class="text-[11px] font-bold uppercase tracking-wider text-gray-800">Transaksi Terbaru</h2>
                        <a href="{{ route('transactions.index') }}"
                            class="text-[11px] font-semibold text-gray-600 hover:text-black">
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
                                                class="text-[10px] font-mono text-gray-500 shrink-0">{{ $log->kode_transaksi }}</span>
                                        </div>
                                        <p class="text-xs font-semibold text-gray-800 truncate">
                                            {{ $log->itemable->judul ?? ($log->itemable->nama_buku ?? ($log->itemable->nama_barang ?? 'Item')) }}
                                        </p>
                                        <p class="text-[10px] text-gray-400">
                                            {{ \Carbon\Carbon::parse($log->tanggal_transaksi)->format('d/m/Y') }}
                                        </p>
                                    </div>

                                    <div class="text-right shrink-0 pl-2">
                                        <span
                                            class="text-xs font-extrabold {{ $log->jenis_transaksi === 'masuk' ? 'text-green-600' : 'text-red-600' }}">
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
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('transactionChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Buku', 'Barang Lain'],
                    datasets: [{
                            label: 'Masuk',
                            data: [{{ $bukuMasuk ?? 0 }}, {{ $barangMasuk ?? 0 }}],
                            backgroundColor: 'rgba(21, 128, 61, 0.75)',
                            borderRadius: 4,
                            barPercentage: 0.6,
                            categoryPercentage: 0.7
                        },
                        {
                            label: 'Keluar',
                            data: [{{ $bukuKeluar ?? 0 }}, {{ $barangKeluar ?? 0 }}],
                            backgroundColor: 'rgba(185, 28, 28, 0.75)',
                            borderRadius: 4,
                            barPercentage: 0.6,
                            categoryPercentage: 0.7
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: {
                                boxWidth: 8,
                                boxHeight: 8,
                                usePointStyle: true,
                                pointStyle: 'rectRounded',
                                font: {
                                    size: 9,
                                    weight: '500'
                                },
                                color: '#6B7280'
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
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: {
                                    size: 9
                                }
                            },
                            grid: {
                                color: '#F3F4F6'
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 9,
                                    weight: '600'
                                }
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
