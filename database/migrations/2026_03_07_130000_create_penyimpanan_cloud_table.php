<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penyimpanan_cloud', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('pengguna')->cascadeOnDelete();
            $table->string('nama'); // Nama deskriptif: "Backup DB Maret 2026"
            $table->string('jenis_drive'); // google_drive, google_drive_bisnis, onedrive, terabox, custom
            $table->string('jenis_drive_kustom')->nullable(); // Jika custom, nama platform
            $table->string('jenis_data'); // database, dokumen, laporan, kehadiran, keuangan, surat, arsip, lainnya
            $table->text('url_link'); // URL/link ke file di cloud
            $table->text('deskripsi')->nullable();
            $table->bigInteger('ukuran_byte')->nullable();
            $table->string('status')->default('aktif'); // aktif, arsip, rusak
            $table->boolean('bisa_dihapus')->default(true); // false = data penting, tidak bisa dihapus oleh non-admin
            $table->string('peran_pemilik'); // peran pemilik: admin, kepala_sekolah, staf, dll
            $table->timestamps();

            $table->index(['pengguna_id', 'jenis_drive']);
            $table->index('peran_pemilik');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyimpanan_cloud');
    }
};
