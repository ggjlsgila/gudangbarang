<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incoming_items', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->morphs('itemable'); // Mendukung transaksi untuk Buku (Book) maupun Barang Lainnya (Item)
            $table->integer('jumlah');
            $table->date('tanggal_masuk');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incoming_items');
    }
};
