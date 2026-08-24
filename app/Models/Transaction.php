<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi',
        'jenis_transaksi',
        'itemable_type',
        'itemable_id',
        'jumlah',
        'tanggal_transaksi',
        'keterangan',
    ];

    /**
     * Relasi Polymorphic (Buku atau Item Lainnya)
     */
    public function itemable()
    {
        return $this->morphTo();
    }
}
