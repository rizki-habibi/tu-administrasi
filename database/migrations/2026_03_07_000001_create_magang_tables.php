<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom khusus magang di tabel pengguna
        Schema::table('pengguna', function (Blueprint $table) {
            $table->string('pembimbing_lapangan')->nullable()->after('alamat');
            $table->string('instansi_asal')->nullable()->after('pembimbing_lapangan');
            $table->date('tanggal_mulai_magang')->nullable()->after('instansi_asal');
            $table->date('tanggal_selesai_magang')->nullable()->after('tanggal_mulai_magang');
        });

        // Logbook harian magang
        Schema::create('logbook_magang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('pengguna')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->text('kegiatan');
            $table->text('hasil')->nullable();
            $table->text('kendala')->nullable();
            $table->text('rencana_besok')->nullable();
            $table->enum('status', ['draft', 'final'])->default('draft');
            $table->text('catatan_pembimbing')->nullable();
            $table->timestamps();

            $table->unique(['pengguna_id', 'tanggal']);
        });

        // Kegiatan magang (tugas/penugasan)
        Schema::create('kegiatan_magang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('pengguna')->cascadeOnDelete();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['belum_mulai', 'berlangsung', 'selesai'])->default('belum_mulai');
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi'])->default('sedang');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatan_magang');
        Schema::dropIfExists('logbook_magang');

        Schema::table('pengguna', function (Blueprint $table) {
            $table->dropColumn([
                'pembimbing_lapangan',
                'instansi_asal',
                'tanggal_mulai_magang',
                'tanggal_selesai_magang',
            ]);
        });
    }
};
