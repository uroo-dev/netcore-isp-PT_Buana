<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';

    protected $fillable = [
        'nama',
        'telepon',
        'alamat',
        'koordinat',
        'ip_address',
        'tipe',
        'paket_id',
        'paket_wifi_custom',
        'harga_wifi_custom',
        'penanggung_jawab_id',
        'status',
        'tanggal_off',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function paket()
    {
        return $this->belongsTo(PaketWifi::class, 'paket_id');
    }

    public function penanggungJawab()
    {
        return $this->belongsTo(PenanggungJawabWifi::class, 'penanggung_jawab_id');
    }

    public function scopeIndividu($query)
    {
        return $query->where('tipe', 'individu');
    }

    public function scopeInstansi($query)
    {
        return $query->where('tipe', 'instansi');
    }

    public function scopeMitra($query)
    {
        return $query->where('tipe', 'mitra');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'on');
    }

    public function scopeNonaktif($query)
    {
        return $query->where('status', 'off');
    }
}
