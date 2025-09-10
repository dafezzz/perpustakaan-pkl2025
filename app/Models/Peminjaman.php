<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Book;
use App\Models\Pengembalian;

class Peminjaman extends Model
{
    protected $table = 'tbl_peminjaman';
    protected $primaryKey = 'id_peminjaman'; 
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true; // karena tabel ada created_at & updated_at

    protected $fillable = [
        'user_id',
        'book_id',
        'nama_lengkap',
        'email',
        'alamat',
        'no_hp',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
    ];

    // ✅ casting tanggal otomatis jadi Carbon instance
    protected $casts = [
        'tanggal_pinjam'   => 'datetime',
        'tanggal_kembali'  => 'datetime',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relasi ke Book
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }

    // Relasi ke Pengembalian
    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'peminjaman_id');
    }
}
