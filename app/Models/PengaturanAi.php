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
        'kapabilitas',
        'ikon',
        'warna_tema',
        'diperbarui_oleh',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'opsi' => 'array',
        'kapabilitas' => 'array',
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
            'groq' => [
                'nama' => 'Groq',
                'models' => ['llama-3.3-70b-versatile', 'llama-3.1-8b-instant', 'mixtral-8x7b-32768', 'gemma2-9b-it'],
                'base_url' => 'https://api.groq.com/openai/v1',
            ],
            'openrouter' => [
                'nama' => 'OpenRouter',
                'models' => ['google/gemini-2.0-flash-exp:free', 'meta-llama/llama-3.3-70b-instruct:free', 'deepseek/deepseek-chat-v3-0324:free', 'qwen/qwen-2.5-72b-instruct:free'],
                'base_url' => 'https://openrouter.ai/api/v1',
            ],
            'deepseek' => [
                'nama' => 'DeepSeek',
                'models' => ['deepseek-chat', 'deepseek-reasoner'],
                'base_url' => 'https://api.deepseek.com/v1',
            ],
            'mistral' => [
                'nama' => 'Mistral AI',
                'models' => ['mistral-large-latest', 'mistral-medium-latest', 'mistral-small-latest', 'open-mistral-nemo'],
                'base_url' => 'https://api.mistral.ai/v1',
            ],
            'cohere' => [
                'nama' => 'Cohere',
                'models' => ['command-r-plus', 'command-r', 'command-light'],
                'base_url' => 'https://api.cohere.ai/v1',
            ],
            'custom' => [
                'nama' => 'Custom / Lainnya',
                'models' => [],
                'base_url' => '',
            ],
        ];
    }

    /**
     * Default capabilities list
     */
    public static function daftarKapabilitas(): array
    {
        return [
            'teks' => 'Baca & Generasi Teks',
            'gambar' => 'Analisis Gambar / Vision',
            'dokumen' => 'Generasi Dokumen',
            'ringkasan' => 'Ringkasan & Analisis',
            'terjemahan' => 'Terjemahan Bahasa',
            'kode' => 'Bantuan Coding',
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
