<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StarAnalysis extends Model
{
    use HasFactory;

    protected $table = 'star_analyses';

    protected $fillable = [
        'judul', 'kategori', 'situation', 'task', 'action', 'result',
        'refleksi', 'tindak_lanjut', 'file_path', 'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
