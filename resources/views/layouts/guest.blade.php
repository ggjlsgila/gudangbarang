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
               bg-[#c8cdd1] px-4 py-8 sm:py-12">

        <div class="auth-grid absolute inset-0" aria-hidden="true"></div>

        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-sky-200/10 blur-3xl" aria-hidden="true"></div>
        <div class="absolute -bottom-32 -right-24 h-96 w-96 rounded-full bg-indigo-200/10 blur-3xl" aria-hidden="true">
        </div>

        {{-- ========================================= --}}
        {{-- LOGIN CARD --}}
        {{-- ========================================= --}}

        <div
            class="relative z-10
                   w-full max-w-[420px]
                   px-4 py-5
                   sm:px-7 sm:py-7
                   bg-white/85
                   backdrop-blur-xl
                   rounded-[20px] sm:rounded-[24px]
                   border border-white/80
                   shadow-[0_24px_60px_rgba(15,23,42,0.12)]">

            {{-- ========================================= --}}
            {{-- LOGO PERUSAHAAN --}}
            {{-- ========================================= --}}

            <div class="mb-5 flex flex-col items-center sm:mb-6">

                <img src="{{ asset('images/logo.png') }}" alt="Logo Manufaktur Indonesia"
                    class="h-20 w-auto object-contain sm:h-24">

                <p class="mt-2 text-[9px] font-bold uppercase tracking-[0.18em] text-blue-600 sm:text-[10px]">
                    Sistem Inventaris & Logistik
                </p>
                <h1 class="mt-1 text-[20px] font-extrabold tracking-tight text-slate-800 sm:text-[24px]">
                    Gudang Barang
                </h1>

            </div>

            {{-- ========================================= --}}
            {{-- FORM LOGIN --}}
            {{-- ========================================= --}}

            {{ $slot }}


        </div>

    </div>

</body>

</html>
