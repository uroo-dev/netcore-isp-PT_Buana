<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoice';

    protected $fillable = [
        'kode_invoice',
        'pelanggan_id',
        'user_id',
        'diskon',
        'pajak',
        'total',
        'dibayar',
        'sisa',
        'status',
        'metode_pembayaran',
        'tanggal',
        'jatuh_tempo',
        'sumber',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jatuh_tempo' => 'date',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(InvoiceDetail::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if ($invoice->kode_invoice) {
                return;
            }
            $tanggal = now()->format('Ymd');
            $prefix = 'INV-'.$tanggal.'-';
            $max = self::where('kode_invoice', 'like', $prefix.'%')->max('kode_invoice');
            $next = $max ? ((int) substr($max, -4)) + 1 : 1;
            $invoice->kode_invoice = $prefix.str_pad($next, 4, '0', STR_PAD_LEFT);
        });
    }

    public function getTanggalJAttribute()
    {
        return $this->tanggal ? $this->tanggal->format('d M Y') : '-';
    }

    public function getJatuhTempoJAttribute()
    {
        return $this->jatuh_tempo ? $this->jatuh_tempo->format('d M Y') : '-';
    }

    public function updateStatus()
    {
        if ($this->sisa <= 0) {
            $this->status = 'lunas';
        } else {
            $this->status = 'cicilan';
        }
        $this->save();
    }
}
