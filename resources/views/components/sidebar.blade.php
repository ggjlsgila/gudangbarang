<aside id="sidebar" class="flex flex-col justify-between antialiased">

    {{-- =========================================
        HEADER SIDEBAR
    ========================================== --}}
    <div class="flex h-16 shrink-0 items-center justify-between border-b border-slate-100 px-5">
        <div class="flex items-center gap-3 min-w-0">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center overflow-hidden rounded-xl">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-full w-full object-cover">
            </div>
            <div class="min-w-0">
                <h1 class="truncate text-base font-bold text-slate-900 tracking-tight">
                    Gudang Barang
                </h1>
                <p class="truncate text-[11px] text-slate-500 font-semibold">
                    Sistem Inventaris
                </p>
            </div>
        </div>

        <button id="sidebar-close" type="button" onclick="closeSidebar()"
            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-700 transition hover:bg-slate-50 hover:text-slate-900"
            aria-label="Tutup menu">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>


    {{-- =========================================
        MENU SIDEBAR
    ========================================== --}}
    <nav id="sidebar-menu" class="flex-1 overflow-y-auto px-3 py-4 space-y-1">

        {{-- DASHBOARD --}}
        <a href="{{ route('dashboard') }}"
            class="flex items-center justify-between rounded-xl px-3 py-2.5 text-sm transition {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'font-semibold text-slate-900 hover:bg-slate-100' }}">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-slate-600' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </div>
        </a>


        {{-- MASTER DATA (Dropdown) --}}
        @php
            $isMasterActive = request()->routeIs('books.*') || request()->routeIs('items.*');
        @endphp

        <div>
            <button type="button" onclick="toggleDropdown('master-data-menu', this)"
                class="flex w-full items-center justify-between rounded-xl px-3 py-2.5 text-sm transition {{ $isMasterActive ? 'bg-indigo-50/70 text-indigo-700 font-bold' : 'font-semibold text-slate-900 hover:bg-slate-100' }}">
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ $isMasterActive ? 'text-indigo-600' : 'text-slate-600' }}" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span>Master Data</span>
                </span>

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="dropdown-arrow h-4 w-4 text-slate-700 {{ $isMasterActive ? 'rotate' : '' }}" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            {{-- Dropdown Container --}}
            <div id="master-data-menu"
                class="sidebar-dropdown space-y-1 pl-9 pr-1 pt-1 {{ $isMasterActive ? 'show' : '' }}">

                {{-- Buku --}}
                <a href="{{ route('books.index') }}"
                    class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-xs transition {{ request()->routeIs('books.*') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'font-semibold text-slate-800 hover:bg-slate-100' }}">
                    <span class="text-sm">📚</span>
                    <span>Buku</span>
                </a>

                @php
                    $isItemsActive = request()->routeIs('items.*');
                @endphp
                {{-- Barang Lainnya --}}
                <a href="{{ route('items.index') }}"
                    class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-xs transition {{ $isItemsActive ? 'bg-indigo-50 text-indigo-700 font-bold' : 'font-semibold text-slate-800 hover:bg-slate-100' }}">
                    <span class="text-sm">📦</span>
                    <span>Barang Lainnya</span>
                </a>

            </div>
        </div>


        {{-- TRANSAKSI --}}
        <a href="{{ route('transactions.index') }}"
            class="flex items-center justify-between rounded-xl px-3 py-2.5 text-sm transition {{ request()->routeIs('transactions.*') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'font-semibold text-slate-900 hover:bg-slate-100' }}">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 {{ request()->routeIs('transactions.*') ? 'text-indigo-600' : 'text-slate-600' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span>Transaksi</span>
            </div>
        </a>

        @if (auth()->user()->role === 'admin')
            <a href="{{ route('users.index') }}"
                class="flex items-center justify-between rounded-xl px-3 py-2.5 text-sm transition {{ request()->routeIs('users.*') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'font-semibold text-slate-900 hover:bg-slate-100' }}">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 {{ request()->routeIs('users.*') ? 'text-indigo-600' : 'text-slate-600' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 19a3 3 0 10-6 0m9-8a3 3 0 11-6 0 3 3 0 016 0zm-9 0a3 3 0 11-6 0 3 3 0 016 0zm-6 8a3 3 0 015.65-1.35M18 18a3 3 0 015.65 1.35" />
                    </svg>
                    <span>User</span>
                </div>
            </a>
        @endif

    </nav>


    {{-- =========================================
        USER + LOGOUT
    ========================================== --}}
    <div class="shrink-0 border-t border-slate-100 p-3 space-y-2">
        @auth
            <div class="flex items-center gap-3 rounded-xl bg-slate-50 border border-slate-200/80 p-2.5">
                <div
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-100 text-xs font-bold text-indigo-700 border border-indigo-200">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-xs font-bold text-slate-900">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="truncate text-[10px] font-medium text-slate-500">
                        {{ auth()->user()->email }}
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-bold text-rose-600 transition hover:bg-rose-50 hover:text-rose-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Keluar Sistem</span>
                </button>
            </form>
        @endauth
    </div>

</aside>
