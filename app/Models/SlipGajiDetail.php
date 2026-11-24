<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlipGajiDetail extends Model
{
    protected $table = 'slip_gaji_detail';

    protected $fillable = [
        'slip_gaji_id',
        'karyawan_id',
        'gaji_pokok',
        'total_hadir',
        'total_terlambat',
        'total_absen',
        'total_gaji',
    ];

    public function slipGaji()
    {
        return $this->belongsTo(SlipGaji::class);
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
