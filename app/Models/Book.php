<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_buku',
        'judul_buku',
        'stok',
        'keterangan',
    ];

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'itemable');
    }
}
