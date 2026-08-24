<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    /**
     * Menampilkan halaman pengaturan.
     */
    public function index()
    {
        // Mengambil data user yang sedang login saat ini
       $user = Auth::user();

        return view('settings.index', compact('user'));
    }

    /**
     * Memperbarui pengaturan profil dan sistem user.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        // Validasi input dari form
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id), // Mengabaikan email milik sendiri saat validasi unique
            ],
            'company_name' => ['nullable', 'string', 'max:100'],
            'notifications_enabled' => ['nullable', 'boolean'],

            // Validasi opsional jika ingin mengubah password melalui menu pengaturan
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        // Menyiapkan data dasar untuk di-update
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'company_name' => $validated['company_name'] ?? $user->company_name,
            'notifications_enabled' => $request->has('notifications_enabled'),
        ];

        // Jika user mengisi password baru, hash dan masukkan ke array update
        if (!empty($validated['new_password'])) {
            $updateData['password'] = Hash::make($validated['new_password']);
        }

        // Simpan perubahan ke database
        $user->update($updateData);

        // Kembalikan ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Konfigurasi sistem berhasil diperbarui.');
    }
}
