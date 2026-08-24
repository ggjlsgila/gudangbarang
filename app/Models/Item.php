<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'stok',
        'keterangan',
    ];
    public function transactions()
{
    return $this->morphMany(Transaction::class, 'itemable');
}
}
