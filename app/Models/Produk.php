<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';

    protected $fillable = [
        'nama',
        'kategori',
        'stok',
        'harga',
        'harga_beli',
    ];

    public function details()
    {
        return $this->hasMany(InvoiceDetail::class);
    }

    public function produkCabang()
    {
        return $this->hasMany(ProdukCabang::class);
    }

    public function scopeKategori($query, $kategori)
    {
        if ($kategori) {
            return $query->where('kategori', $kategori);
        }

        return $query;
    }

    public function scopeMenipis($query)
    {
        return $query->where('stok', '>', 0)->where('stok', '<=', 10);
    }

    public function scopeHabis($query)
    {
        return $query->where('stok', 0);
    }

    public function getStokStatusAttribute()
    {
        if ($this->stok > 10) {
            return 'tersedia';
        } elseif ($this->stok > 0) {
            return 'menipis';
        }

        return 'habis';
    }

    public function getTerjualAttribute()
    {
        if (array_key_exists('terjual', $this->attributes)) {
            return $this->attributes['terjual'];
        }
        if ($this->relationLoaded('details')) {
            return $this->details->sum('qty');
        }

        return $this->details()->sum('qty');
    }

    public function stokDiCabang($cabangId)
    {
        $pc = $this->produkCabang()->where('cabang_id', $cabangId)->first();

        return $pc ? $pc->stok : 0;
    }
}
