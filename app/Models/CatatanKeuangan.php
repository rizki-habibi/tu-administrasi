<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanKeuangan extends Model
{
    use HasFactory;

    protected $table = 'catatan_keuangan';

    protected $fillable = [
        'kode_transaksi', 'jenis', 'kategori', 'uraian', 'jumlah',
        'tanggal', 'bukti_path', 'bukti_nama', 'keterangan',
        'status', 'dibuat_oleh', 'diverifikasi_oleh',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }

    public function verifier()
    {
        return $this->belongsTo(Pengguna::class, 'diverifikasi_oleh');
    }

    public static function generateKode($jenis): string
    {
        $prefix = $jenis === 'pemasukan' ? 'IN' : 'OUT';
        $count = static::where('kode_transaksi', 'like', "$prefix-%")->count();
        return $prefix . '-' . now()->format('Ym') . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }
}
