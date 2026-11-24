<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

// #[Fillable(['name', 'email', 'password'])]
// #[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'nama_lengkap',
        'username',
        'telepon',
        'password',
        'role',
        'cabang_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function kas()
    {
        return $this->hasMany(Kas::class);
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function isCabang()
    {
        return $this->role === 'cabang';
    }

    public function isOwner()
    {
        return $this->role === 'owner';
    }
}
