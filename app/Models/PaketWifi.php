<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketWifi extends Model
{
    protected $table = 'paket_wifi';

    protected $fillable = [
        'nama',
        'harga',
        'kecepatan',
        'deskripsi',
    ];

    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class, 'paket_id');
    }
}
