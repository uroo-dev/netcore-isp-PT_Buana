<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenanggungJawabWifi extends Model
{
    protected $table = 'penanggung_jawab_wifi';

    protected $fillable = [
        'nama',
        'telepon',
        'wilayah',
    ];

    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class, 'penanggung_jawab_id');
    }
}
