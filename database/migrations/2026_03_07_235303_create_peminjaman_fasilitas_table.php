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
        Schema::create('peminjaman_fasilitas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_fasilitas'); // Lapangan Basket, Aula, Lab Komputer, etc.
            $table->string('jenis')->default('ruangan'); // ruangan, lapangan, peralatan
            $table->foreignId('peminjam_id')->constrained('pengguna')->cascadeOnDelete();
            $table->string('peminjam_nama')->nullable(); // nama peminjam eksternal
            $table->string('keperluan');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('penanggung_jawab');
            $table->text('catatan')->nullable();
            $table->string('status')->default('pending'); // pending, disetujui, ditolak, selesai
            $table->string('alasan_tolak')->nullable();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamp('disetujui_pada')->nullable();
            $table->timestamps();

            $table->index(['nama_fasilitas', 'tanggal', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_fasilitas');
    }
};
