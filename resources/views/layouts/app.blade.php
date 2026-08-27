<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <title>{{ config('app.name', 'Gudang Barang') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* =========================================
           LAYOUT UTAMA & VARIABEL
        ========================================= */

        :root {
            --sidebar-width: 260px;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
        }

        body {
            background: #f8fafc;
            /* Latar belakang aplikasi soft slate */
            color: #334155;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        /* =========================================
           SIDEBAR STYLING (Putih Bersih & Border Soft)
        ========================================= */

        #sidebar {
            width: var(--sidebar-width);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1000;

            background: #ffffff;
            /* Putih Bersih */
            border-right: 1px solid #e2e8f0;
            /* Border samar */

            transform: translateX(-100%);
            transition: transform 0.25s ease;

            display: flex;
            flex-direction: column;
        }

        #sidebar.sidebar-open {
            transform: translateX(0);
        }

        /* =========================================
           CONTENT
        ========================================= */

        #main-content {
            min-height: 100vh;
            width: 100%;
            margin-left: 0;

            background: #f8fafc;
        }

        /* =========================================
           DESKTOP RESPONSIVE
        ========================================= */

        @media (min-width: 768px) {

            #sidebar {
                transform: translateX(0);
            }

            #main-content {
                margin-left: var(--sidebar-width);
                width: calc(100% - var(--sidebar-width));
            }

            #mobile-header {
                display: none;
            }

            #sidebar-overlay {
                display: none !important;
            }

            #sidebar-close {
                display: none;
            }
        }

        /* =========================================
           MOBILE RESPONSIVE
        ========================================= */

        @media (max-width: 767px) {

            #mobile-header {
                display: flex;
            }

            #sidebar {
                width: 240px;
                max-width: 240px;
                box-shadow: 10px 0 30px rgba(15, 23, 42, 0.08);
            }

            #sidebar-overlay.overlay-show {
                display: block;
            }
        }

        /* =========================================
           DROPDOWN LOGIC (Logika Asli)
        ========================================= */

        .sidebar-dropdown {
            display: none;
        }

        .sidebar-dropdown.show {
            display: block;
        }

        .dropdown-arrow {
            transition: transform 0.2s ease;
        }

        .dropdown-arrow.rotate {
            transform: rotate(180deg);
        }

        /* =========================================
           SCROLLBAR SIDEBAR MODERN
        ========================================= */

        #sidebar-menu {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }

        /* =========================================
           CUSTOM TOM SELECT (PENYESUAIAN TAILWIND UI)
        ========================================= */

        .ts-control {
            border-radius: 0.75rem !important;
            /* rounded-xl */
            padding: 0.5rem 0.75rem !important;
            border-color: #e2e8f0 !important;
            /* border-slate-200 */
            font-size: 0.75rem !important;
            /* text-xs */
            font-weight: 500 !important;
            box-shadow: none !important;
            background-color: #ffffff !important;
        }

        .ts-control.focus {
            border-color: #6366f1 !important;
            /* focus:border-indigo-500 */
            box-shadow: 0 0 0 1px #6366f1 !important;
        }

        select,
        input,
        textarea,
        .ts-wrapper {
            max-width: 100% !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        /* Styling & penyesuaian z-index Dropdown TomSelect */
        .ts-dropdown {
            max-width: 100% !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1) !important;
            border-color: #f1f5f9 !important;
            overflow: hidden;
            font-size: 0.75rem !important;
            z-index: 9999 !important;
        }

        /* Warna item pilihan saat di-highlight/pilih */
        .ts-dropdown .option.active {
            background-color: #4f46e5 !important;
            /* bg-indigo-600 */
            color: #ffffff !important;
        }
    </style>
</head>

<body>{{-- =========================================
         SIDEBAR COMPONENT
    ========================================== --}} @include('components.sidebar') {{-- =========================================
         MAIN CONTENT AREA
    ========================================== --}} <main id="main-content">
        {{-- Header khusus mobile --}} <header id="mobile-header"
            class="sticky top-0 z-40 h-16 items-center justify-between border-b border-slate-200 bg-white px-4 shadow-sm">
            <button type="button" onclick="openSidebar()"
                class="flex h-10 w-10 items-center justify-center rounded-xl text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                aria-label="Buka menu"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg></button>
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Manufaktur Indonesia"
                    class="h-8 w-8 rounded-lg object-contain">
                <span class="text-base font-bold text-slate-800">Gudang Barang</span>
            </div>{{-- Spacer penyeimbang flex --}} <div class="h-10 w-10"></div>
        </header>{{-- =====================================
             ISI HALAMAN UTAMA
        ====================================== --}} <div class="min-h-screen p-4 sm:p-6 lg:p-8">@yield('content') </div>
    </main>{{-- =========================================
         OVERLAY MOBILE
    ========================================== --}} <div id="sidebar-overlay"
        class="fixed inset-0 z-[900] hidden bg-slate-900/20 backdrop-blur-sm transition-opacity"
        onclick="closeSidebar()"></div>{{-- =========================================
         JAVASCRIPT SIDEBAR (100% LOGIKA UTUH)
    ========================================== --}}
    <script>
        function openSidebar() {

            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (sidebar) {
                sidebar.classList.add('sidebar-open');
            }

            if (overlay) {
                overlay.classList.add('overlay-show');
            }
        }


        function closeSidebar() {

            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (sidebar) {
                sidebar.classList.remove('sidebar-open');
            }

            if (overlay) {
                overlay.classList.remove('overlay-show');
            }
        }


        function toggleSidebar() {

            const sidebar = document.getElementById('sidebar');

            if (!sidebar) {
                return;
            }

            if (sidebar.classList.contains('sidebar-open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        }


        function toggleDropdown(id, button) {

            const dropdown = document.getElementById(id);

            if (!dropdown) {
                return;
            }

            dropdown.classList.toggle('show');

            const arrow = button.querySelector('.dropdown-arrow');

            if (arrow) {
                arrow.classList.toggle('rotate');
            }
        }


        // Kalau layar berubah menjadi desktop,
        // pastikan overlay mobile hilang.
        window.addEventListener('resize', function() {

            if (window.innerWidth >= 768) {
                const overlay = document.getElementById('sidebar-overlay');

                if (overlay) {
                    overlay.classList.remove('overlay-show');
                }
            }

        });
    </script>
</body>

</html>
