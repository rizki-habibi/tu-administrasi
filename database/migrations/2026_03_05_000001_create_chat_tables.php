<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel percakapan (conversations)
        Schema::create('percakapan', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable(); // null = chat pribadi, isi = grup
            $table->enum('tipe', ['pribadi', 'grup'])->default('pribadi');
            $table->foreignId('dibuat_oleh')->constrained('pengguna')->cascadeOnDelete();
            $table->string('foto_grup')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // Tabel anggota percakapan
        Schema::create('anggota_percakapan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('percakapan_id')->constrained('percakapan')->cascadeOnDelete();
            $table->foreignId('pengguna_id')->constrained('pengguna')->cascadeOnDelete();
            $table->enum('peran', ['admin', 'anggota'])->default('anggota');
            $table->timestamp('terakhir_dibaca')->nullable();
            $table->timestamps();

            $table->unique(['percakapan_id', 'pengguna_id']);
        });

        // Tabel pesan (messages)
        Schema::create('pesan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('percakapan_id')->constrained('percakapan')->cascadeOnDelete();
            $table->foreignId('pengirim_id')->constrained('pengguna')->cascadeOnDelete();
            $table->text('isi');
            $table->enum('tipe', ['teks', 'file', 'gambar', 'sistem'])->default('teks');
            $table->string('file_path')->nullable();
            $table->string('file_nama')->nullable();
            $table->foreignId('balasan_id')->nullable()->constrained('pesan')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesan');
        Schema::dropIfExists('anggota_percakapan');
        Schema::dropIfExists('percakapan');
    }
};
