<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <style>
        .auth-grid {
            background-color: #f8fafc;
            background-image: linear-gradient(#e2e8f0 1px, transparent 1px),
                linear-gradient(90deg, #e2e8f0 1px, transparent 1px);
            background-size: 32px 32px;
            mask-image: linear-gradient(to bottom, black 20%, transparent 100%);
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>


<body class="font-sans text-gray-900 antialiased">

    <div
        class="min-h-screen flex items-center justify-center
               relative overflow-hidden
         bg-slate-50 px-4 py-8 sm:py-12">

        <div class="auth-grid absolute inset-0 opacity-80" aria-hidden="true"></div>

        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-sky-200/30 blur-3xl" aria-hidden="true"></div>
        <div class="absolute -bottom-32 -right-24 h-96 w-96 rounded-full bg-indigo-200/25 blur-3xl" aria-hidden="true">
        </div>

        {{-- ========================================= --}}
        {{-- BACKGROUND DECORATION --}}
        {{-- ========================================= --}}

        {{-- ========================================= --}}
        {{-- LOGIN CARD --}}
        {{-- ========================================= --}}

        <div
            class="relative z-10
                   w-full max-w-md
                   px-7 py-8
                   sm:px-10 sm:py-10
                   bg-white/90
                   backdrop-blur-xl
                   rounded-3xl
                   border border-white/80
                   shadow-[0_24px_70px_rgba(15,23,42,0.14)]">


            {{-- ========================================= --}}
            {{-- LOGO PERUSAHAAN --}}
            {{-- ========================================= --}}

            <div class="flex flex-col items-center mb-7">

                <div
                    class="flex items-center justify-center
                           w-20 h-20 rounded-2xl bg-white
                           border border-slate-200 shadow-sm">

                    <img src="{{ asset('images/logo.png') }}" alt="Logo Perusahaan" class="w-14 h-14 object-contain">

                </div>

                <p class="mt-4 text-[10px] font-bold uppercase tracking-[0.18em] text-sky-600">Sistem Inventaris &
                    Logistik</p>
                <h1 class="mt-1 text-xl font-extrabold tracking-tight text-slate-900">Gudang Barang</h1>

            </div>


            {{-- ========================================= --}}
            {{-- FORM LOGIN --}}
            {{-- ========================================= --}}

            {{ $slot }}


        </div>

    </div>

</body>

</html>
