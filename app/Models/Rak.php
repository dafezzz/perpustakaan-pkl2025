<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rak extends Model
{
    protected $fillable = ['kode_rak', 'nama_rak', 'keterangan'];




    public function books()
{
    return $this->hasMany(Book::class, 'rak_id');
}

}



