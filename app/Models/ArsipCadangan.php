<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipCadangan extends Model
{
    protected $table = 'arsip_cadangan';

    protected $fillable = [
        'nama_file',
        'google_drive_id',
        'path_lokal',
        'jenis',
        'ukuran_byte',
        'status',
        'catatan',
        'pengguna_id',
    ];

    protected $casts = [
        'ukuran_byte' => 'integer',
    ];

    /**
     * Relasi ke User
     */
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    /**
     * Ukuran file yang terformat
     */
    public function getUkuranFormatAttribute(): string
    {
        $bytes = $this->ukuran_byte;
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }

    /**
     * Scope: hanya yang berhasil
     */
    public function scopeBerhasil($query)
    {
        return $query->where('status', 'berhasil');
    }
}
