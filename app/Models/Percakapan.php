<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Percakapan extends Model
{
    protected $table = 'percakapan';

    protected $fillable = [
        'nama', 'tipe', 'dibuat_oleh', 'foto_grup', 'deskripsi',
    ];

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function anggota(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'anggota_percakapan', 'percakapan_id', 'pengguna_id')
            ->withPivot('peran', 'terakhir_dibaca')
            ->withTimestamps();
    }

    public function pesan(): HasMany
    {
        return $this->hasMany(Pesan::class, 'percakapan_id');
    }

    public function pesanTerakhir()
    {
        return $this->hasOne(Pesan::class, 'percakapan_id')->latestOfMany();
    }

    /**
     * Jumlah pesan belum dibaca untuk user tertentu
     */
    public function pesanBelumDibaca(int $userId): int
    {
        $member = $this->anggota()->where('pengguna_id', $userId)->first();
        if (!$member || !$member->pivot->terakhir_dibaca) {
            return $this->pesan()->where('pengirim_id', '!=', $userId)->count();
        }
        return $this->pesan()
            ->where('pengirim_id', '!=', $userId)
            ->where('created_at', '>', $member->pivot->terakhir_dibaca)
            ->count();
    }

    /**
     * Nama tampilan percakapan (untuk pribadi = nama lawan bicara)
     */
    public function getNamaUntuk(int $userId): string
    {
        if ($this->tipe === 'grup') {
            return $this->nama ?? 'Grup Tanpa Nama';
        }
        $lawan = $this->anggota->where('id', '!=', $userId)->first();
        return $lawan ? $lawan->nama : 'Pengguna Dihapus';
    }
}
