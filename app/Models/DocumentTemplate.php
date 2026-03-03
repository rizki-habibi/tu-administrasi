<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'category', 'content', 'fields', 'format', 'is_active', 'created_by',
    ];

    protected $casts = [
        'fields' => 'array',
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'akademik' => 'Akademik',
            'kepegawaian' => 'Kepegawaian',
            'kesiswaan' => 'Kesiswaan',
            'sarpras' => 'Sarana & Prasarana',
            'keuangan' => 'Keuangan',
            'akreditasi' => 'Akreditasi',
            default => ucfirst($this->category),
        };
    }
}
