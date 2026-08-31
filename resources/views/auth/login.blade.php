<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-2 w-full">
        @csrf

        <div class="space-y-4 sm:space-y-5">
            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-slate-700 sm:text-[15px]">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    autocomplete="username"
                    class="h-11 w-full rounded-xl border border-slate-300 bg-white/80 px-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200 transition sm:h-12 sm:text-base" />
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password"
                    class="mb-2 block text-sm font-medium text-slate-700 sm:text-[15px]">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="h-11 w-full rounded-xl border border-slate-300 bg-white/80 px-4 text-sm text-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200 transition sm:h-12 sm:text-base" />
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <label for="remember_me" class="inline-flex items-center text-sm text-slate-600">
                <input id="remember_me" type="checkbox" name="remember"
                    class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500" />
                <span class="ml-2">Remember me</span>
            </label>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-4">
                @if (Route::has('password.request'))
                    <a class="text-sm font-medium text-slate-600 transition hover:text-slate-900"
                        href="{{ route('password.request') }}">
                        Forgot your password?
                    </a>
                @endif

                <button type="submit"
                    class="inline-flex w-full items-center justify-center rounded-xl bg-[#1d4ed8] px-5 py-3 text-sm font-bold uppercase tracking-wide text-white shadow-md transition hover:bg-[#1e40af] focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 sm:w-auto">
                    LOG IN
                </button>
            </div>
        </div>
    </form>
</x-guest-layout>
