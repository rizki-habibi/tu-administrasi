# Rekomendasi API AI — Sistem TU Administrasi

> **Versi:** 1.0  
> **Tanggal:** 7 Maret 2026  
> **Tujuan:** Referensi API AI yang dapat diintegrasikan untuk pengembangan fitur cerdas

---

## Daftar Isi

1. [Pendahuluan](#1-pendahuluan)
2. [API yang Sudah Digunakan](#2-api-yang-sudah-digunakan)
3. [Rekomendasi API AI Gratis](#3-rekomendasi-api-ai-gratis)
4. [Rekomendasi API AI Berbayar](#4-rekomendasi-api-ai-berbayar)
5. [Perbandingan API](#5-perbandingan-api)
6. [Skenario Penggunaan](#6-skenario-penggunaan)
7. [Panduan Implementasi](#7-panduan-implementasi)
8. [Keamanan & Best Practice](#8-keamanan--best-practice)

---

## 1. Pendahuluan

Dokumen ini menyediakan daftar rekomendasi API AI yang dapat diintegrasikan ke Sistem TU Administrasi untuk meningkatkan fitur-fitur cerdas seperti:

- Generate dokumen otomatis
- Chatbot asisten administrasi
- Analisis data & laporan
- OCR (baca dokumen/gambar)
- Ringkasan dokumen otomatis
- Terjemahan & koreksi bahasa

---

## 2. API yang Sudah Digunakan

### Google Gemini AI (Aktif)

| Item | Detail |
|------|--------|
| **Provider** | Google |
| **Model** | `gemini-2.0-flash` (default) |
| **Endpoint** | `https://generativelanguage.googleapis.com/v1beta/models/{model}:generateContent` |
| **Auth** | API Key via query param `?key=` |
| **Harga** | Gratis (tier free) |
| **Limit** | 15 RPM, 1M token/menit, 1.500 request/hari |
| **Digunakan untuk** | Generate dokumen Word, SIATU-AI chatbot |
| **Config** | `.env` → `GEMINI_API_KEY`, `GEMINI_MODEL` |

---

## 3. Rekomendasi API AI Gratis

### 3.1. Google Gemini AI (Extended)

> ✅ **Sudah digunakan** — bisa diperluas fiturnya

| Item | Detail |
|------|--------|
| **URL** | https://aistudio.google.com/apikey |
| **Model Tambahan** | `gemini-2.5-flash-preview`, `gemini-2.5-pro-preview` |
| **Fitur Baru** | Vision (analisis gambar), Structured Output (JSON), Function Calling |
| **Cocok untuk** | OCR dokumen, analisis foto kehadiran, generate laporan terstruktur |

**Contoh Implementasi:**
```php
// Vision — Analisis gambar dokumen
$response = Http::withHeaders([
    'Content-Type' => 'application/json',
])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
    'contents' => [
        'parts' => [
            ['text' => 'Baca dan ekstrak teks dari gambar dokumen ini'],
            ['inline_data' => [
                'mime_type' => 'image/jpeg',
                'data' => base64_encode(file_get_contents($imagePath))
            ]]
        ]
    ]
]);
```

### 3.2. Hugging Face Inference API

| Item | Detail |
|------|--------|
| **URL** | https://huggingface.co/inference-api |
| **Harga** | Gratis (rate-limited), Pro $9/bulan |
| **Auth** | Bearer Token |
| **Model** | 400.000+ model open-source |
| **Cocok untuk** | Klasifikasi teks, NER, sentiment, ringkasan, terjemahan |

**Contoh Implementasi:**
```php
$response = Http::withToken($hfToken)
    ->post('https://api-inference.huggingface.co/models/facebook/bart-large-cnn', [
        'inputs' => $dokumenPanjang
    ]);
// Hasil: ringkasan dokumen otomatis
```

### 3.3. Groq Cloud API

| Item | Detail |
|------|--------|
| **URL** | https://console.groq.com/keys |
| **Harga** | Gratis (generous free tier) |
| **Model** | Llama 3.3 70B, Mixtral 8x7B, Gemma 2 |
| **Kecepatan** | Sangat cepat (hardware khusus LPU) |
| **Limit** | 30 RPM, 14.400 request/hari (free) |
| **Cocok untuk** | Chatbot realtime, analisis cepat, generate teks |

**Contoh Implementasi:**
```php
$response = Http::withToken($groqApiKey)
    ->post('https://api.groq.com/openai/v1/chat/completions', [
        'model' => 'llama-3.3-70b-versatile',
        'messages' => [
            ['role' => 'system', 'content' => 'Kamu adalah asisten administrasi sekolah.'],
            ['role' => 'user', 'content' => $pertanyaan]
        ]
    ]);
```

### 3.4. Cloudflare Workers AI

| Item | Detail |
|------|--------|
| **URL** | https://developers.cloudflare.com/workers-ai/ |
| **Harga** | 10.000 neuron gratis/hari |
| **Model** | Llama, Mistral, Stable Diffusion, Whisper |
| **Cocok untuk** | Generate teks, OCR, speech-to-text |

### 3.5. Cohere API

| Item | Detail |
|------|--------|
| **URL** | https://dashboard.cohere.com/api-keys |
| **Harga** | Gratis (trial), Production tier berbayar |
| **Fitur Unggulan** | Rerank (pencarian dokumen cerdas), Embed (vectorisasi), Generate |
| **Cocok untuk** | Pencarian dokumen cerdas di arsip, klasifikasi surat |

---

## 4. Rekomendasi API AI Berbayar

### 4.1. OpenAI API

| Item | Detail |
|------|--------|
| **URL** | https://platform.openai.com/api-keys |
| **Harga** | Pay-per-use (mulai $0.15/1M token input — GPT-4o-mini) |
| **Model** | GPT-4o, GPT-4o-mini, o1, o3 |
| **Cocok untuk** | Generate dokumen kompleks, analisis mendalam, coding assistant |

### 4.2. Anthropic Claude API

| Item | Detail |
|------|--------|
| **URL** | https://console.anthropic.com/ |
| **Harga** | Pay-per-use (mulai $0.25/1M token — Haiku) |
| **Model** | Claude Sonnet, Claude Haiku |
| **Cocok untuk** | Analisis dokumen panjang (200K context), penulisan profesional |

### 4.3. Mistral AI API

| Item | Detail |
|------|--------|
| **URL** | https://console.mistral.ai/api-keys/ |
| **Harga** | Free tier tersedia, pay-per-use |
| **Model** | Mistral Large, Mistral Small, Codestral |
| **Cocok untuk** | Alternatif murah untuk generate dokumen |

---

## 5. Perbandingan API

| API | Gratis | Kecepatan | Kualitas | Bahasa ID | Mudah Integrasi | Skor |
|-----|--------|-----------|----------|-----------|-----------------|------|
| **Google Gemini** | ✅ | ⚡⚡ | ⭐⭐⭐⭐ | ⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | **Terbaik** |
| **Groq** | ✅ | ⚡⚡⚡ | ⭐⭐⭐ | ⭐⭐⭐ | ⭐⭐⭐⭐ | Sangat Baik |
| **Hugging Face** | ✅ | ⚡ | ⭐⭐⭐ | ⭐⭐ | ⭐⭐⭐ | Baik |
| **OpenAI** | ❌ | ⚡⚡ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | Premium |
| **Anthropic** | ❌ | ⚡⚡ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ | ⭐⭐⭐⭐ | Premium |
| **Cohere** | ✅* | ⚡⚡ | ⭐⭐⭐ | ⭐⭐⭐ | ⭐⭐⭐ | Baik |
| **Cloudflare** | ✅ | ⚡⚡ | ⭐⭐⭐ | ⭐⭐ | ⭐⭐⭐ | Cukup |

> **Rekomendasi Utama:** Tetap gunakan **Google Gemini** sebagai basis utama. Tambahkan **Groq** sebagai fallback cepat. Pertimbangkan **OpenAI** untuk kebutuhan kualitas tinggi.

---

## 6. Skenario Penggunaan

### 6.1. Generate Dokumen Otomatis
- **API:** Google Gemini (sudah aktif)
- **Fitur:** Buat surat, laporan, notulen dari prompt

### 6.2. Chatbot Asisten Administrasi
- **API:** Google Gemini / Groq
- **Fitur:** SIATU-AI — jawab pertanyaan tentang prosedur, data sekolah

### 6.3. OCR Dokumen & Surat
- **API:** Google Gemini Vision / Hugging Face
- **Fitur:** Scan surat fisik → ekstrak teks → otomatis input ke sistem

### 6.4. Ringkasan Laporan
- **API:** Groq (Llama 3.3) / Hugging Face (BART)
- **Fitur:** Ringkas laporan panjang menjadi poin-poin utama

### 6.5. Klasifikasi Surat Otomatis
- **API:** Cohere / Hugging Face
- **Fitur:** Otomatis kategorikan surat masuk (Dinas, Undangan, dll.)

### 6.6. Analisis Sentimen Saran Pengunjung
- **API:** Hugging Face / Google Gemini
- **Fitur:** Analisis sentimen dari saran pengunjung halaman publik

### 6.7. Pencarian Dokumen Cerdas
- **API:** Cohere Rerank / Gemini
- **Fitur:** Cari dokumen berdasarkan konteks, bukan hanya kata kunci

---

## 7. Panduan Implementasi

### 7.1. Arsitektur yang Direkomendasikan

```
┌─────────────┐     ┌──────────────────┐     ┌───────────────┐
│   Frontend   │────→│  Laravel Backend  │────→│   AI Service   │
│  (Blade/JS)  │     │   (Controller)    │     │   (Config)     │
└─────────────┘     └──────────────────┘     └───────────────┘
                              │                        │
                              ▼                        ▼
                    ┌──────────────────┐     ┌───────────────┐
                    │  AI Service      │     │  Gemini API   │
                    │  (app/Services/) │────→│  Groq API     │
                    │                  │     │  HF API       │
                    └──────────────────┘     └───────────────┘
```

### 7.2. Konfigurasi `.env`

```env
# Google Gemini (sudah ada)
GEMINI_API_KEY=your-gemini-key
GEMINI_MODEL=gemini-2.0-flash

# Groq (opsional - fallback)
GROQ_API_KEY=your-groq-key
GROQ_MODEL=llama-3.3-70b-versatile

# Hugging Face (opsional - fitur lanjutan)
HUGGINGFACE_API_KEY=your-hf-key
```

### 7.3. Langkah Integrasi API Baru

1. Daftarkan API key dari provider
2. Tambahkan ke `.env`
3. Daftarkan di `config/services.php`
4. Buat service class di `app/Services/`
5. Gunakan di controller yang relevan

---

## 8. Keamanan & Best Practice

### 8.1. Penyimpanan API Key
- ✅ Simpan di `.env` — **JANGAN** hardcode di kode sumber
- ✅ Tambahkan ke `.gitignore`
- ✅ Gunakan `config('services.xxx')` untuk mengakses

### 8.2. Rate Limiting
- ✅ Implementasi queue untuk request bervolume tinggi
- ✅ Cache hasil AI untuk prompt yang sama
- ✅ Gunakan fallback jika API utama gagal

### 8.3. Validasi Input
- ✅ Sanitasi input pengguna sebelum dikirim ke API
- ✅ Batasi panjang prompt (maks 4.000 karakter)
- ✅ Filter konten berbahaya

### 8.4. Fallback Strategy

```
Gemini API → [Gagal] → Groq API → [Gagal] → Template Lokal
```

Sistem sudah memiliki **fallback ke template lokal** jika Gemini tidak tersedia. Direkomendasikan menambahkan Groq sebagai fallback kedua sebelum template lokal.

---

> *Dokumen ini adalah referensi teknis untuk pengembangan fitur AI pada Sistem TU Administrasi SMA Negeri 2 Jember.*
