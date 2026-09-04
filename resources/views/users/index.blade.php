@extends('layouts.app')

@section('content')
    <div class="space-y-4">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-extrabold text-slate-900">Kelola User</h1>
                <p class="text-sm text-slate-500">Atur akun dan hak akses pengguna.</p>
            </div>
            <a href="{{ route('users.create') }}"
                class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-indigo-700">Tambah User</a>
        </div>

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                {{ session('success') }}</div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-slate-100 bg-slate-50 text-xs uppercase text-slate-500">
                        <tr>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($users as $user)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-slate-800">{{ $user->name }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $user->email }}</td>
                                <td class="px-4 py-3"><span
                                        class="rounded-full px-2.5 py-1 text-xs font-bold {{ $user->role === 'admin' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-700' }}">{{ ucfirst($user->role) }}</span>
                                </td>
                                <td class="px-4 py-3 text-right"><a href="{{ route('users.edit', $user) }}"
                                        class="font-semibold text-indigo-600 hover:text-indigo-800">Edit</a>
                                    @if (!$user->is(auth()->user()))
                                        <form method="POST" action="{{ route('users.destroy', $user) }}"
                                            class="ml-3 inline" data-confirm-message="Hapus user ini?"
                                            onsubmit="return openDeleteModal(this)">@csrf
                                            @method('DELETE')<button
                                                class="font-semibold text-rose-600 hover:text-rose-800">Hapus</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
