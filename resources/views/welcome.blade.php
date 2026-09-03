<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gudang Barang - Sistem Manajemen Inventaris & Transaksi</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    {{-- Tailwind CSS & Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .welcome-grid {
            background-color: #f8fafc;
            background-image: linear-gradient(#e2e8f0 1px, transparent 1px),
                linear-gradient(90deg, #e2e8f0 1px, transparent 1px);
            background-size: 32px 32px;
            mask-image: linear-gradient(to bottom, black, transparent 72%);
        }
    </style>
</head>

<body
    class="h-full bg-slate-50 text-slate-900 antialiased selection:bg-sky-600 selection:text-white flex flex-col justify-between">

    <div class="welcome-grid fixed inset-0 z-0 opacity-70" aria-hidden="true"></div>

    {{-- HEADER / NAVBAR --}}
    <header
        class="w-full bg-white/90 backdrop-blur-md border-b border-slate-200/80 py-3.5 px-4 sm:px-10 flex items-center justify-between sticky top-0 z-50">
        <div class="flex items-center gap-2.5">
            <div
                class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white overflow-hidden flex items-center justify-center border border-slate-200 shadow-sm">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Gudang Barang" class="h-full w-full object-contain">
            </div>
            <div>
                <span class="block font-extrabold text-xs sm:text-sm tracking-tight text-slate-900">Gudang Barang</span>
                <span class="hidden sm:block text-[10px] font-medium text-slate-500">Sistem Inventaris</span>
            </div>
        </div>

        <div>
            <nav class="flex items-center gap-2 sm:gap-3">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="text-[11px] sm:text-xs font-semibold px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg bg-slate-900 text-white hover:bg-sky-700 shadow-sm transition">
                        Dashboard →
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="text-[11px] sm:text-xs font-semibold px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg bg-slate-900 text-white hover:bg-sky-700 shadow-sm transition">
                        Login
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    {{-- HERO SECTION --}}
    <main
        class="relative isolate max-w-5xl mx-auto px-4 sm:px-6 py-10 sm:py-14 text-center flex-1 flex flex-col justify-center items-center overflow-hidden">

        <div
            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white border border-sky-100 text-sky-700 text-[10px] sm:text-[11px] font-bold mb-4 tracking-wide uppercase shadow-sm">
            <span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-pulse"></span>
            Sistem Inventaris & Logistik
        </div>

        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-slate-950 leading-[1.08] mb-3 max-w-3xl">
            Kelola <span class="text-sky-600">Buku</span>, Stok Barang, dan Transaksi Lebih Terstruktur.
        </h1>

        <p class="text-xs sm:text-sm text-slate-600 max-w-xl mb-7 leading-relaxed">
            Platform manajemen gudang digital yang dirancang untuk memantau inventaris buku, barang masuk, dan barang
            keluar secara real-time.
        </p>

        {{-- Tombol Aksi Utama --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-2.5 sm:gap-3 w-full sm:w-auto mb-8">
            <a href="{{ route('login') }}"
                class="w-full sm:w-auto px-6 py-3 rounded-xl bg-slate-900 text-white text-xs font-bold tracking-wide uppercase hover:bg-sky-700 transition shadow-md shadow-slate-300">
                Login ke Sistem
            </a>
        </div>

        <p class="mb-8 text-[11px] text-slate-500">Akun pengguna dibuat oleh admin sistem.</p>

        {{-- Fitur Singkat Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 sm:gap-4 w-full text-left">
            <div
                class="p-4 sm:p-5 rounded-2xl border border-slate-200 bg-white/95 shadow-sm hover:-translate-y-1 hover:shadow-lg hover:shadow-slate-200/70 transition duration-300">
                <div
                    class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center text-xs mb-3 font-bold">
                    📊</div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-1">Grafik Real-Time</h3>
                <p class="text-[11px] text-slate-500 leading-normal">Pantau perbandingan arus barang masuk dan keluar
                    langsung dari satu layar.</p>
            </div>
            <div
                class="p-4 sm:p-5 rounded-2xl border border-slate-200 bg-white/95 shadow-sm hover:-translate-y-1 hover:shadow-lg hover:shadow-slate-200/70 transition duration-300">
                <div
                    class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center text-xs mb-3 font-bold">
                    📚</div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-1">Katalog Terintegrasi</h3>
                <p class="text-[11px] text-slate-500 leading-normal">Manajemen data buku dan inventaris barang dengan
                    pencarian cepat.</p>
            </div>
            <div
                class="p-4 sm:p-5 rounded-2xl border border-slate-200 bg-white/95 shadow-sm hover:-translate-y-1 hover:shadow-lg hover:shadow-slate-200/70 transition duration-300">
                <div
                    class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center text-xs mb-3 font-bold">
                    ⚡</div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-1">Cepat & Responsif</h3>
                <p class="text-[11px] text-slate-500 leading-normal">Antarmuka bersih dengan navigasi yang nyaman
                    dioperasikan di perangkat apa pun.</p>
            </div>
        </div>

    </main>

    {{-- FOOTER --}}
    <footer class="w-full bg-white border-t border-indigo-100/60 py-3 text-center text-slate-500 text-[11px]">
        &copy; {{ date('Y') }} Gudang Barang. All rights reserved.
    </footer>

</body>

</html>
