<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';

    protected $fillable = [
        'pengguna_id', 'aksi', 'modul', 'deskripsi',
        'model_type', 'model_id', 'data_lama', 'data_baru',
        'ip_address', 'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'data_lama' => 'array',
            'data_baru' => 'array',
        ];
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }

    public static function catat(string $aksi, string $modul, string $deskripsi, $model = null, ?array $dataLama = null, ?array $dataBaru = null): self
    {
        return static::create([
            'pengguna_id' => auth()->id(),
            'aksi' => $aksi,
            'modul' => $modul,
            'deskripsi' => $deskripsi,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'data_lama' => $dataLama,
            'data_baru' => $dataBaru,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
