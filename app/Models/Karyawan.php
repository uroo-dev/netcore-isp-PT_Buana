<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $fillable = [
        'nama_lengkap',
        'telepon',
        'alamat',
        'gaji_pokok',
    ];

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function slipGajiDetails()
    {
        return $this->hasMany(SlipGajiDetail::class);
    }
}
