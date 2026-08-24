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
    </style>
</head>

<body
    class="h-full bg-slate-50 text-slate-900 antialiased selection:bg-indigo-600 selection:text-white flex flex-col justify-between">

    {{-- HEADER / NAVBAR --}}
    <header
        class="w-full bg-white/80 backdrop-blur-md border-b border-indigo-100/60 py-3 px-4 sm:px-10 flex items-center justify-between sticky top-0 z-50">
        <div class="flex items-center gap-2.5">
            <div
                class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold text-xs sm:text-sm tracking-wider shadow-md shadow-indigo-200">

            </div>
            <span class="font-bold text-xs sm:text-sm tracking-tight text-slate-800 uppercase"></span>
        </div>

        <div>
            @if (Route::has('login'))
                <nav class="flex items-center gap-2 sm:gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="text-[11px] sm:text-xs font-semibold px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 shadow-sm shadow-indigo-200 transition">
                            Dashboard →
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-[11px] sm:text-xs font-semibold px-2.5 py-1.5 sm:px-4 sm:py-2 rounded-lg text-slate-700 hover:text-indigo-600 transition">
                            Masuk
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="text-[11px] sm:text-xs font-semibold px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 shadow-sm shadow-indigo-200 transition">
                                Daftar
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    {{-- HERO SECTION (Desain responsif: Mobile tetap rapat ke bawah, Desktop rapat & padat ke tengah) --}}
    <main
        class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-10 text-center flex-1 flex flex-col justify-center items-center">

        {{-- Badge kecil di atas judul --}}
        <div
            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 text-[10px] sm:text-[11px] font-bold mb-3 tracking-wide uppercase shadow-sm">
            <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
            Sistem Inventaris & Logistik Modern
        </div>

        {{-- Judul Utama --}}
        <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-slate-900 leading-tight mb-2.5 max-w-2xl">
            Kelola Stok Barang & Transaksi <span class="text-indigo-600">Lebih Terstruktur.</span>
        </h1>

        {{-- Deskripsi Singkat --}}
        <p class="text-xs sm:text-sm text-slate-600 max-w-lg mb-6 leading-relaxed">
            Platform manajemen gudang digital yang dirancang untuk memantau inventaris buku, barang masuk, dan barang
            keluar secara real-time.
        </p>

        {{-- Tombol Aksi Utama --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-2.5 sm:gap-3 w-full sm:w-auto mb-8">
            @auth
                <a href="{{ url('/dashboard') }}"
                    class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-xs font-bold tracking-wide uppercase hover:bg-indigo-700 transition shadow-md shadow-indigo-200">
                    Buka Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-xs font-bold tracking-wide uppercase hover:bg-indigo-700 transition shadow-md shadow-indigo-200">
                    Mulai Masuk Sistem
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-white border border-indigo-200 text-indigo-700 text-xs font-bold tracking-wide uppercase hover:bg-indigo-50 transition shadow-sm">
                        Buat Akun Baru
                    </a>
                @endif
            @endauth
        </div>

        {{-- Fitur Singkat Grid (Otomatis menyesuaikan: 1 kolom di HP, 3 kolom rapat di Desktop) --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 sm:gap-4 w-full text-left">
            <div
                class="p-3.5 sm:p-4 rounded-xl border border-indigo-100/80 bg-white shadow-sm hover:shadow-md transition">
                <div
                    class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs mb-2 font-bold">
                    📊</div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-1">Grafik Real-Time</h3>
                <p class="text-[11px] text-slate-500 leading-normal">Pantau perbandingan arus barang masuk dan keluar
                    langsung dari satu layar.</p>
            </div>
            <div
                class="p-3.5 sm:p-4 rounded-xl border border-indigo-100/80 bg-white shadow-sm hover:shadow-md transition">
                <div
                    class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs mb-2 font-bold">
                    📚</div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-1">Katalog Terintegrasi</h3>
                <p class="text-[11px] text-slate-500 leading-normal">Manajemen data buku dan inventaris barang dengan
                    pencarian cepat.</p>
            </div>
            <div
                class="p-3.5 sm:p-4 rounded-xl border border-indigo-100/80 bg-white shadow-sm hover:shadow-md transition">
                <div
                    class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs mb-2 font-bold">
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
