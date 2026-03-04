<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengaturanPengguna extends Model
{
    protected $table = 'pengaturan_pengguna';

    protected $fillable = [
        'pengguna_id', 'kunci', 'nilai',
    ];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    /**
     * Get setting value for a user, with default fallback
     */
    public static function dapatkan(int $userId, string $kunci, $default = null)
    {
        $setting = static::where('pengguna_id', $userId)->where('kunci', $kunci)->first();
        return $setting ? $setting->nilai : $default;
    }

    /**
     * Set a setting value for a user
     */
    public static function atur(int $userId, string $kunci, $nilai): static
    {
        return static::updateOrCreate(
            ['pengguna_id' => $userId, 'kunci' => $kunci],
            ['nilai' => $nilai]
        );
    }

    /**
     * Get all settings for a user as key-value array
     */
    public static function semuaUntuk(int $userId): array
    {
        return static::where('pengguna_id', $userId)
            ->pluck('nilai', 'kunci')
            ->toArray();
    }

    /**
     * Default settings
     */
    const DEFAULTS = [
        'tema'              => 'gelap',      // gelap / terang
        'ukuran_font'       => 'normal',     // kecil / normal / besar
        'sidebar_mini'      => 'false',      // true / false
        'warna_aksen'       => '#6366f1',    // hex color
        'notifikasi_suara'  => 'true',       // true / false
        'bahasa'            => 'id',         // id / en
    ];
}
