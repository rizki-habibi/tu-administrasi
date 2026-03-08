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
        Schema::create('buku_perpustakaan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_buku')->unique();
            $table->string('judul');
            $table->string('pengarang')->nullable();
            $table->string('penerbit')->nullable();
            $table->string('tahun_terbit', 4)->nullable();
            $table->string('isbn', 20)->nullable();
            $table->string('kategori')->default('umum');
            $table->string('lokasi_rak')->nullable();
            $table->integer('jumlah_total')->default(1);
            $table->integer('jumlah_tersedia')->default(1);
            $table->decimal('harga', 12, 2)->nullable();
            $table->string('sumber_dana')->nullable(); // BOS, APBD, Sumbangan, dll
            $table->string('kondisi')->default('baik'); // baik, rusak_ringan, rusak_berat
            $table->text('keterangan')->nullable();
            $table->foreignId('dibuat_oleh')->constrained('pengguna')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['kategori', 'kondisi']);
        });

        Schema::create('peminjaman_buku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buku_id')->constrained('buku_perpustakaan')->cascadeOnDelete();
            $table->string('nama_peminjam');
            $table->string('kelas')->nullable();
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali_rencana');
            $table->date('tanggal_kembali_aktual')->nullable();
            $table->string('status')->default('dipinjam'); // dipinjam, dikembalikan, terlambat
            $table->text('catatan')->nullable();
            $table->foreignId('dicatat_oleh')->constrained('pengguna')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_buku');
        Schema::dropIfExists('buku_perpustakaan');
    }
};
