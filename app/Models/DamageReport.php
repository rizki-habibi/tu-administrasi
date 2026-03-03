<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DamageReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventaris_id', 'tanggal_laporan', 'deskripsi_kerusakan',
        'tingkat_kerusakan', 'foto', 'status', 'tindakan', 'reported_by',
    ];

    protected $casts = ['tanggal_laporan' => 'date'];

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class, 'inventaris_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
