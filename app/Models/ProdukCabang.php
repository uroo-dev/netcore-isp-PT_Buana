<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukCabang extends Model
{
    protected $table = 'produk_cabang';

    protected $fillable = [
        'produk_id',
        'cabang_id',
        'stok',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }
}
