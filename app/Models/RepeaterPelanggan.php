<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepeaterPelanggan extends Model
{
    protected $table = 'repeater_pelanggan';

    protected $fillable = [
        'repeater_id', 'bulan', 'tahun',
        'nama_pelanggan', 'paket', 'harga',
        'dpp', 'ppn', 'bhp_uso',
    ];

    public function repeater()
    {
        return $this->belongsTo(Repeater::class);
    }
}
