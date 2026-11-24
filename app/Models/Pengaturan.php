<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $table = 'pengaturan';

    protected $fillable = [
        'nama',
        'email',
        'telepon',
        'logo',
        'alamat',
        'backup_email',
        'backup_retention_days',
        'backup_max_storage_mb',
        'backup_schedule_time',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
