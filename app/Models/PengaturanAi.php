<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class PengaturanAi extends Model
{
    protected $table = 'pengaturan_ai';

    protected $fillable = [
        'provider',
        'nama_tampilan',
        'api_key',
        'model',
        'base_url',
        'aktif',
        'opsi',
        'diperbarui_oleh',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'opsi' => 'array',
    ];

    // Encrypt API key on set
    public function setApiKeyAttribute($value)
    {
        $this->attributes['api_key'] = Crypt::encryptString($value);
    }

    // Decrypt API key on get
    public function getApiKeyAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    public function diperbaruiOleh()
    {
        return $this->belongsTo(Pengguna::class, 'diperbarui_oleh');
    }

    /**
     * Get the currently active AI config
     */
    public static function getActive(): ?self
    {
        return static::where('aktif', true)->latest()->first();
    }

    /**
     * Available providers
     */
    public static function providers(): array
    {
        return [
            'gemini' => [
                'nama' => 'Google Gemini',
                'models' => ['gemini-2.0-flash', 'gemini-1.5-pro', 'gemini-1.5-flash', 'gemini-1.0-pro'],
                'base_url' => 'https://generativelanguage.googleapis.com/v1beta',
            ],
            'openai' => [
                'nama' => 'OpenAI',
                'models' => ['gpt-4o', 'gpt-4o-mini', 'gpt-4-turbo', 'gpt-3.5-turbo'],
                'base_url' => 'https://api.openai.com/v1',
            ],
            'anthropic' => [
                'nama' => 'Anthropic Claude',
                'models' => ['claude-sonnet-4-20250514', 'claude-3-5-haiku-20241022', 'claude-3-opus-20240229'],
                'base_url' => 'https://api.anthropic.com/v1',
            ],
            'custom' => [
                'nama' => 'Custom / Lainnya',
                'models' => [],
                'base_url' => '',
            ],
        ];
    }

    /**
     * Status badge
     */
    public function getStatusBadgeAttribute(): string
    {
        return $this->aktif
            ? '<span class="badge bg-success">Aktif</span>'
            : '<span class="badge bg-secondary">Nonaktif</span>';
    }

    /**
     * Masked API key for display
     */
    public function getMaskedApiKeyAttribute(): string
    {
        $key = $this->api_key;
        if (strlen($key) <= 8) return str_repeat('*', strlen($key));
        return substr($key, 0, 4) . str_repeat('*', strlen($key) - 8) . substr($key, -4);
    }
}
