<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'member';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'alamat',
        'no_hp',
        // tambahkan kolom lain sesuai tabelmu
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
