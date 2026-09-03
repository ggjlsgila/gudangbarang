@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-2xl space-y-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Edit User</h1>
            <p class="text-sm text-slate-500">Perbarui akun dan hak akses.</p>
        </div>
        <form method="POST" action="{{ route('users.update', $user) }}"
            class="space-y-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">@csrf @method('PUT')
            @include('users.form')
            <div class="flex justify-end gap-2"><a href="{{ route('users.index') }}"
                    class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Batal</a><button
                    class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-bold text-white">Simpan</button></div>
        </form>
    </div>
@endsection
