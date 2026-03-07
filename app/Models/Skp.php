<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skp extends Model
{
    use HasFactory;

    protected $table = 'skp';

    protected $fillable = [
        'pengguna_id', 'periode', 'tahun', 'sasaran_kinerja', 'indikator_kinerja',
        'target_kuantitas', 'realisasi_kuantitas', 'target_kualitas', 'realisasi_kualitas',
        'target_waktu', 'realisasi_waktu', 'nilai_capaian', 'predikat',
        'catatan', 'catatan_revisi', 'status', 'disetujui_oleh', 'disetujui_pada',
        'ditolak_pada', 'direvisi_pada',
    ];

    protected function casts(): array
    {
        return [
            'disetujui_pada' => 'datetime',
            'ditolak_pada' => 'datetime',
            'direvisi_pada' => 'datetime',
            'target_kuantitas' => 'decimal:2',
            'realisasi_kuantitas' => 'decimal:2',
            'target_kualitas' => 'decimal:2',
            'realisasi_kualitas' => 'decimal:2',
            'target_waktu' => 'decimal:2',
            'realisasi_waktu' => 'decimal:2',
            'nilai_capaian' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(Pengguna::class, 'disetujui_oleh');
    }

    public function getPredikatBadgeAttribute(): string
    {
        return match ($this->predikat) {
            'sangat_baik' => 'success',
            'baik' => 'primary',
            'cukup' => 'warning',
            'kurang' => 'danger',
            'buruk' => 'dark',
            default => 'secondary',
        };
    }

    public function getPredikatLabelAttribute(): string
    {
        return match ($this->predikat) {
            'sangat_baik' => 'Sangat Baik',
            'baik' => 'Baik',
            'cukup' => 'Cukup',
            'kurang' => 'Kurang',
            'buruk' => 'Buruk',
            default => '-',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'secondary',
            'diajukan' => 'info',
            'menunggu' => 'warning',
            'disetujui' => 'success',
            'dinilai' => 'success',
            'revisi' => 'warning',
            'ditolak' => 'danger',
            default => 'light',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'diajukan' => 'Diajukan',
            'menunggu' => 'Menunggu Review',
            'disetujui' => 'Disetujui',
            'dinilai' => 'Dinilai',
            'revisi' => 'Perlu Revisi',
            'ditolak' => 'Ditolak',
            default => $this->status,
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'bi-pencil',
            'diajukan' => 'bi-send',
            'menunggu' => 'bi-hourglass-split',
            'disetujui' => 'bi-check-circle-fill',
            'dinilai' => 'bi-check2-all',
            'revisi' => 'bi-arrow-repeat',
            'ditolak' => 'bi-x-circle-fill',
            default => 'bi-circle',
        };
    }

    /**
     * Calculate nilai capaian automatically
     */
    public function hitungNilaiCapaian(): float
    {
        $nilaiKuantitas = $this->target_kuantitas > 0
            ? ($this->realisasi_kuantitas / $this->target_kuantitas) * 100
            : 0;
        $nilaiKualitas = $this->target_kualitas > 0
            ? ($this->realisasi_kualitas / $this->target_kualitas) * 100
            : 0;
        $nilaiWaktu = $this->target_waktu > 0
            ? ($this->realisasi_waktu <= $this->target_waktu ? 100 : max(0, 100 - (($this->realisasi_waktu - $this->target_waktu) / $this->target_waktu * 100)))
            : 0;

        return round(($nilaiKuantitas + $nilaiKualitas + $nilaiWaktu) / 3, 2);
    }
}
