<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    protected $fillable = ['filename', 'disk', 'size', 'path', 'status', 'backup_date', 'retention_days'];

    protected $casts = [
        'backup_date' => 'date',
    ];

    public function scopeExpired($query)
    {
        return $query->where('retention_days', '>', 0)
            ->whereRaw('DATEDIFF(NOW(), created_at) > retention_days');
    }
}
