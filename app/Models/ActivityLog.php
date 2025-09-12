<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'activity', 'model', 'model_id', 'details', 'ip_address'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

