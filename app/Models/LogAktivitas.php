<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';

    protected $fillable = [
        'user_id',
        'aksi',
        'tipe',
        'model',
        'model_id',
        'deskripsi',
        'data_lama',
        'data_baru',
        'ip',
        'status_code',
    ];

    protected $casts = [
        'data_lama' => 'array',
        'data_baru' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function catat($aksi, $model, $modelId = null, $deskripsi = null, $dataLama = null, $dataBaru = null, $tipe = 'aksi')
    {
        try {
            return self::create([
                'user_id' => auth()->id(),
                'aksi' => $aksi,
                'tipe' => $tipe,
                'model' => $model,
                'model_id' => (string) $modelId,
                'deskripsi' => $deskripsi,
                'data_lama' => $dataLama,
                'data_baru' => $dataBaru,
                'ip' => request()->ip(),
            ]);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
