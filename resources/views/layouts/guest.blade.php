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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>


<body class="font-sans text-gray-900 antialiased">

    <div
        class="min-h-screen flex items-center justify-center
               relative overflow-hidden
               bg-gradient-to-br from-slate-100 via-white to-slate-200">

        {{-- ========================================= --}}
        {{-- BACKGROUND DECORATION --}}
        {{-- ========================================= --}}

        {{-- Blur kanan atas --}}
        <div
            class="absolute -top-40 -right-40
                   w-[500px] h-[500px]
                   bg-slate-300/30
                   rounded-full blur-3xl">
        </div>




        {{-- ========================================= --}}
        {{-- LOGIN CARD --}}
        {{-- ========================================= --}}

        <div
            class="relative z-10
                   w-full max-w-md
                   mx-4
                   px-7 py-8
                   sm:px-9 sm:py-9
                   bg-white/95
                   backdrop-blur-xl
                   rounded-2xl
                   border border-white
                   shadow-[0_20px_60px_rgba(15,23,42,0.12)]">


            {{-- ========================================= --}}
            {{-- LOGO PERUSAHAAN --}}
            {{-- ========================================= --}}

            <div class="flex justify-center mb-6">

                <div
                    class="flex items-center justify-center
                           w-20 h-20
                           rounded-2xl
                           bg-slate-50
                           border border-slate-200
                           shadow-sm">

                    <img src="{{ asset('images/logo.png') }}" alt="Logo Perusahaan" class="w-14 h-14 object-contain">

                </div>

            </div>


            {{-- ========================================= --}}
            {{-- FORM LOGIN --}}
            {{-- ========================================= --}}

            {{ $slot }}


        </div>

    </div>

</body>

</html>
