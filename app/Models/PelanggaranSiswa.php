<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelanggaranSiswa extends Model
{
    use HasFactory;

    protected $table = 'pelanggaran_siswa';

    protected $fillable = [
        'siswa_id', 'tanggal', 'jenis', 'deskripsi', 'tindakan', 'dilaporkan_oleh',
    ];

    protected $casts = ['tanggal' => 'date'];

    public function student()
    {
        return $this->belongsTo(DataSiswa::class, 'siswa_id');
    }

    public function reporter()
    {
        return $this->belongsTo(Pengguna::class, 'dilaporkan_oleh');
    }
}
