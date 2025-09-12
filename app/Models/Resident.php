<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    protected $table = 'residents';

    protected $fillable = ['user_id','alamat','telp','cover'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
