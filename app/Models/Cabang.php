<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    protected $table = 'cabang';

    protected $fillable = [
        'nama',
        'alamat',
        'telepon',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function produkCabang()
    {
        return $this->hasMany(ProdukCabang::class);
    }
}
