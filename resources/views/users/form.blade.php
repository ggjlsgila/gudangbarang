@foreach (['name' => 'Nama', 'email' => 'Email'] as $field => $label)
    <div>
        <label for="{{ $field }}"
            class="mb-1 block text-sm font-semibold text-slate-700">{{ $label }}</label><input
            id="{{ $field }}" name="{{ $field }}" type="{{ $field === 'email' ? 'email' : 'text' }}"
            value="{{ old($field, $user->$field ?? '') }}" required
            class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error($field)
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>
@endforeach
<div><label for="role" class="mb-1 block text-sm font-semibold text-slate-700">Role</label><select id="role"
        name="role" required
        class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value="petugas" @selected(old('role', $user->role ?? '') === 'petugas')>Petugas</option>
        <option value="admin" @selected(old('role', $user->role ?? '') === 'admin')>Admin</option>
    </select></div>
<div><label for="password" class="mb-1 block text-sm font-semibold text-slate-700">Password
        {{ isset($user) ? '(kosongkan jika tidak diubah)' : '' }}</label><input id="password" name="password"
        type="password" {{ isset($user) ? '' : 'required' }}
        class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
    @error('password')
        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
    @enderror
</div>
<div><label for="password_confirmation" class="mb-1 block text-sm font-semibold text-slate-700">Konfirmasi
        Password</label><input id="password_confirmation" name="password_confirmation" type="password"
        {{ isset($user) ? '' : 'required' }}
        class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
