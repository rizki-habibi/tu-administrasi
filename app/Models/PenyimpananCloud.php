<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenyimpananCloud extends Model
{
    protected $table = 'penyimpanan_cloud';

    protected $fillable = [
        'pengguna_id', 'nama', 'jenis_drive', 'jenis_drive_kustom',
        'jenis_data', 'url_link', 'deskripsi', 'ukuran_byte',
        'status', 'bisa_dihapus', 'peran_pemilik',
    ];

    protected $casts = [
        'ukuran_byte' => 'integer',
        'bisa_dihapus' => 'boolean',
    ];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function getUkuranFormatAttribute(): string
    {
        if (!$this->ukuran_byte) return '-';
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = $this->ukuran_byte;
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getNamaDriveAttribute(): string
    {
        return match ($this->jenis_drive) {
            'google_drive' => 'Google Drive',
            'google_drive_bisnis' => 'Google Drive Bisnis',
            'onedrive' => 'Microsoft OneDrive',
            'terabox' => 'TeraBox',
            'custom' => $this->jenis_drive_kustom ?: 'Custom',
            default => ucfirst($this->jenis_drive),
        };
    }

    public function getIconDriveAttribute(): string
    {
        return match ($this->jenis_drive) {
            'google_drive', 'google_drive_bisnis' => 'bi-google',
            'onedrive' => 'bi-microsoft',
            'terabox' => 'bi-cloud-fill',
            default => 'bi-hdd-network-fill',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'aktif' => 'success',
            'arsip' => 'secondary',
            'rusak' => 'danger',
            default => 'secondary',
        };
    }

    public static function jenisDriveOptions(): array
    {
        return [
            'google_drive' => 'Google Drive',
            'google_drive_bisnis' => 'Google Drive Bisnis',
            'onedrive' => 'Microsoft OneDrive',
            'terabox' => 'TeraBox',
            'custom' => 'Custom / Lainnya',
        ];
    }

    public static function jenisDataOptions(): array
    {
        return [
            'database' => 'Database',
            'dokumen' => 'Dokumen',
            'laporan' => 'Laporan',
            'kehadiran' => 'Kehadiran',
            'keuangan' => 'Keuangan',
            'surat' => 'Surat',
            'arsip' => 'Arsip',
            'foto' => 'Foto / Media',
            'lainnya' => 'Lainnya',
        ];
    }
}
