<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi', 'jenis', 'kategori', 'uraian', 'jumlah',
        'tanggal', 'bukti_path', 'bukti_nama', 'keterangan',
        'status', 'created_by', 'verified_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public static function generateKode($jenis): string
    {
        $prefix = $jenis === 'pemasukan' ? 'IN' : 'OUT';
        $count = static::where('kode_transaksi', 'like', "$prefix-%")->count();
        return $prefix . '-' . now()->format('Ym') . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }
}
