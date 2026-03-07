<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konten_publik', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->longText('konten')->nullable();
            $table->enum('kategori', [
                'profil', 'visi_misi', 'pengurus', 'dokumen', 'galeri',
                'video', 'kerjasama', 'prestasi', 'pengumuman', 'saran',
            ])->default('dokumen');
            $table->enum('tipe', ['teks', 'gambar', 'video', 'dokumen', 'link'])->default('teks');
            $table->string('path_file')->nullable();
            $table->string('nama_file')->nullable();
            $table->string('tipe_file')->nullable();
            $table->unsignedBigInteger('ukuran_file')->nullable();
            $table->string('url_external')->nullable();
            $table->string('thumbnail')->nullable();
            $table->enum('bagian', ['halaman_utama', 'kinerja', 'keduanya'])->default('kinerja');
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->boolean('unggulan')->default(false);
            $table->foreignId('dibuat_oleh')->constrained('pengguna')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['kategori', 'aktif']);
            $table->index(['bagian', 'aktif', 'urutan']);
        });

        Schema::create('saran_pengunjung', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->nullable();
            $table->string('subjek');
            $table->text('pesan');
            $table->enum('status', ['baru', 'dibaca', 'ditanggapi'])->default('baru');
            $table->text('tanggapan')->nullable();
            $table->foreignId('ditanggapi_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamp('ditanggapi_pada')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saran_pengunjung');
        Schema::dropIfExists('konten_publik');
    }
};
