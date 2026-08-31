<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="mt-2 w-full">
        @csrf

        <div class="space-y-4 sm:space-y-5">
            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-slate-700 sm:text-[15px]">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                    autocomplete="name"
                    class="h-11 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200 transition sm:h-12 sm:text-base" />
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-slate-700 sm:text-[15px]">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    autocomplete="username"
                    class="h-11 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200 transition sm:h-12 sm:text-base" />
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password"
                    class="mb-2 block text-sm font-medium text-slate-700 sm:text-[15px]">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="h-11 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200 transition sm:h-12 sm:text-base" />
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation"
                    class="mb-2 block text-sm font-medium text-slate-700 sm:text-[15px]">Confirm
                    Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    autocomplete="new-password"
                    class="h-11 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200 transition sm:h-12 sm:text-base" />
                @error('password_confirmation')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
            <a class="text-center text-sm text-slate-600 transition hover:text-slate-900 sm:text-left"
                href="{{ route('login') }}">
                Already registered?
            </a>

            <button type="submit"
                class="inline-flex w-full items-center justify-center rounded-xl bg-[#1d4ed8] px-5 py-3 text-sm font-bold uppercase tracking-wide text-white shadow-md transition hover:bg-[#1e40af] focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 sm:w-auto">
                Register
            </button>
        </div>
    </form>
</x-guest-layout>
