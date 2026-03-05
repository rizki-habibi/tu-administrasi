<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggaran extends Model
{
    use HasFactory;

    protected $table = 'anggaran';

    protected $fillable = [
        'nama_anggaran', 'tahun_anggaran', 'sumber_dana',
        'total_anggaran', 'terpakai', 'keterangan', 'status', 'dibuat_oleh',
    ];

    protected $casts = [
        'total_anggaran' => 'decimal:2',
        'terpakai' => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }

    public function getSisaAttribute()
    {
        return $this->total_anggaran - $this->terpakai;
    }

    public function getPersentaseTerpakaiAttribute()
    {
        if ($this->total_anggaran == 0) return 0;
        return round(($this->terpakai / $this->total_anggaran) * 100, 1);
    }
}
