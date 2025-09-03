<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'tbl_books';   // Nama tabel
    protected $primaryKey = 'id';     // Primary key
    public $timestamps = false;       // Kalau tabel tidak punya created_at / updated_at

    protected $fillable = [
        'judul',
        'kategori',
        'stok',
        'penerbit',
        'tahun_terbit',
        'pengarang',
        'harga_peminjaman',
        'cover',
        'rak_id',   // <-- sudah benar
    ];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'book_id', 'id');
    }

    public function rak()
    {
        return $this->belongsTo(Rak::class, 'rak_id');
    }
}
