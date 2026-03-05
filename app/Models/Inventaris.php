<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    use HasFactory;

    protected $table = 'inventaris';

    protected $fillable = [
        'kode_barang', 'nama_barang', 'deskripsi', 'kategori', 'lokasi',
        'jumlah', 'kondisi', 'tanggal_perolehan', 'sumber_dana',
        'harga_perolehan', 'foto', 'catatan', 'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal_perolehan' => 'date',
        'harga_perolehan' => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }

    public function damageReports()
    {
        return $this->hasMany(LaporanKerusakan::class, 'inventaris_id');
    }

    public function getKondisiBadgeAttribute(): string
    {
        return match ($this->kondisi) {
            'baik' => 'success',
            'rusak_ringan' => 'warning',
            'rusak_berat' => 'danger',
            default => 'secondary',
        };
    }

    public static function generateKode($kategori): string
    {
        $prefix = match ($kategori) {
            'mebeulair' => 'MBL',
            'elektronik' => 'ELK',
            'buku' => 'BKU',
            'alat_lab' => 'LAB',
            'olahraga' => 'OLR',
            default => 'LNY',
        };
        $last = static::where('kode_barang', 'like', "$prefix-%")->count();
        return $prefix . '-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }
}
