<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccreditationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'standar', 'komponen', 'indikator', 'deskripsi',
        'file_path', 'file_name', 'status', 'catatan', 'uploaded_by',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getStandarLabelAttribute(): string
    {
        return match ($this->standar) {
            'standar_isi' => 'Standar Isi',
            'standar_proses' => 'Standar Proses',
            'standar_kompetensi_lulusan' => 'Standar Kompetensi Lulusan',
            'standar_pendidik' => 'Standar Pendidik & Tenaga Kependidikan',
            'standar_sarpras' => 'Standar Sarana & Prasarana',
            'standar_pengelolaan' => 'Standar Pengelolaan',
            'standar_pembiayaan' => 'Standar Pembiayaan',
            'standar_penilaian' => 'Standar Penilaian',
            default => ucfirst(str_replace('_', ' ', $this->standar)),
        };
    }
}
