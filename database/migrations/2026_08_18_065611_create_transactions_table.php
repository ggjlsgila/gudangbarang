<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique(); // Contoh: TRX-IN-001 / TRX-OUT-001
            $table->enum('jenis_transaksi', ['masuk', 'keluar']); // Pilihan Masuk / Keluar

            // Polymorphic Columns: otomatis membuat 'itemable_type' dan 'itemable_id'
            $table->morphs('itemable');

            $table->integer('jumlah');
            $table->date('tanggal_transaksi');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
