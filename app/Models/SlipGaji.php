<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlipGaji extends Model
{
    protected $table = 'slip_gaji';

    protected $fillable = [
        'nama',
        'bulan',
        'tahun',
        'total_gaji',
    ];

    public function details()
    {
        return $this->hasMany(SlipGajiDetail::class);
    }
}
