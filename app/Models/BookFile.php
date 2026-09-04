<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'original_name',
        'file_path',
        'file_size',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
