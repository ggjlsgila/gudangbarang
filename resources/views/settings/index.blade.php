<div class="bg-white min-h-screen text-black p-8 font-sans max-w-4xl mx-auto">
    <header class="border-b-2 border-black pb-6 mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-black uppercase tracking-tighter">Pengaturan Sistem</h1>
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-widest mt-1">Konfigurasi Umum &
                Operasional Gudang</p>
        </div>
        <span class="text-2xl">⚙️</span>
    </header>

    @if (session('success'))
        <div class="mb-6 p-4 border-2 border-black bg-neutral-100 font-bold text-sm uppercase tracking-wider">
            ✓ {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PATCH')

        <!-- Informasi Organisasi -->
        <div class="border-2 border-black p-6 space-y-4">
            <h2 class="text-sm font-black uppercase tracking-widest border-b border-black pb-2">1. Informasi Perusahaan
                / Organisasi</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest mb-2">Nama Perusahaan /
                        Organisasi</label>
                    <input type="text" name="company_name"
                        value="{{ old('company_name', auth()->user()->company_name ?? '') }}"
                        class="w-full border-2 border-black p-3 uppercase font-bold focus:bg-black focus:text-white transition outline-none"
                        placeholder="Contoh: PT Gudang Berkah Mandiri">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest mb-2">Nomor Telepon /
                        WhatsApp</label>
                    <input type="text" name="company_phone"
                        value="{{ old('company_phone', auth()->user()->company_phone ?? '') }}"
                        class="w-full border-2 border-black p-3 font-bold focus:bg-black focus:text-white transition outline-none"
                        placeholder="08123456789">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-widest mb-2">Alamat Lengkap Gudang /
                    Kantor</label>
                <textarea name="company_address" rows="2"
                    class="w-full border-2 border-black p-3 font-bold focus:bg-black focus:text-white transition outline-none"
                    placeholder="Masukkan alamat lengkap...">{{ old('company_address', auth()->user()->company_address ?? '') }}</textarea>
            </div>
        </div>

        <!-- Preferensi Sistem & Notifikasi -->
        <div class="border-2 border-black p-6 space-y-4">
            <h2 class="text-sm font-black uppercase tracking-widest border-b border-black pb-2">2. Preferensi &
                Notifikasi</h2>

            <div class="flex items-center justify-between border-2 border-black p-4 bg-neutral-50">
                <div>
                    <span class="block font-bold uppercase tracking-widest text-sm">Aktifkan Notifikasi Sistem</span>
                    <span class="text-xs text-neutral-500 font-medium">Terima pemberitahuan untuk stok barang menipis
                        atau transaksi baru.</span>
                </div>
                <input type="checkbox" name="notifications_enabled" value="1"
                    {{ old('notifications_enabled', auth()->user()->notifications_enabled ?? false) ? 'checked' : '' }}
                    class="w-6 h-6 border-2 border-black cursor-pointer accent-black">
            </div>
        </div>

        <!-- Tombol Aksi -->
        <button type="submit"
            class="w-full bg-black text-white py-4 font-black uppercase tracking-widest hover:bg-neutral-800 transition shadow-sm">
            Simpan Konfigurasi
        </button>
    </form>
</div>
