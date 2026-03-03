<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Inventaris Barang / Sarana Prasarana
        Schema::create('inventaris', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            $table->text('deskripsi')->nullable();
            $table->string('kategori'); // mebeulair, elektronik, buku, alat_lab, olahraga, lainnya
            $table->string('lokasi'); // ruang kelas, lab, perpustakaan, etc
            $table->integer('jumlah')->default(1);
            $table->string('kondisi')->default('baik'); // baik, rusak_ringan, rusak_berat
            $table->date('tanggal_perolehan')->nullable();
            $table->string('sumber_dana')->nullable(); // BOS, APBD, Sumbangan, dll
            $table->decimal('harga_perolehan', 15, 2)->nullable();
            $table->string('foto')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // Laporan Kerusakan
        Schema::create('damage_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventaris_id')->constrained('inventaris')->cascadeOnDelete();
            $table->date('tanggal_laporan');
            $table->text('deskripsi_kerusakan');
            $table->string('tingkat_kerusakan'); // ringan, sedang, berat
            $table->string('foto')->nullable();
            $table->string('status')->default('dilaporkan'); // dilaporkan, ditangani, selesai
            $table->text('tindakan')->nullable();
            $table->foreignId('reported_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('damage_reports');
        Schema::dropIfExists('inventaris');
    }
};
