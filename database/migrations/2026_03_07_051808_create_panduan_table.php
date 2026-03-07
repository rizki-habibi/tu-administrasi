<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('panduan', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->longText('konten')->nullable();
            $table->string('ikon')->default('bi-book');
            $table->string('warna')->default('#6366f1');
            $table->string('versi')->nullable();
            $table->string('logo')->nullable();
            $table->enum('kategori', ['panduan', 'dokumentasi', 'changelog', 'referensi'])->default('panduan');
            $table->enum('visibilitas', ['semua', 'admin'])->default('semua');
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->foreignId('dibuat_oleh')->constrained('pengguna')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['aktif', 'urutan']);
            $table->index(['kategori', 'aktif']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panduan');
    }
};
