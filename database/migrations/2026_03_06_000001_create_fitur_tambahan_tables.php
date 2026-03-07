<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Disposisi Surat (Letter Disposition) - Admin assigns letters to staff
        Schema::create('disposisi_surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained('surat')->cascadeOnDelete();
            $table->foreignId('dari_pengguna_id')->constrained('pengguna')->cascadeOnDelete();
            $table->foreignId('kepada_pengguna_id')->constrained('pengguna')->cascadeOnDelete();
            $table->text('instruksi');
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi', 'urgent'])->default('sedang');
            $table->date('tenggat')->nullable();
            $table->enum('status', ['belum_dibaca', 'dibaca', 'diproses', 'selesai'])->default('belum_dibaca');
            $table->text('catatan_tindakan')->nullable();
            $table->timestamp('dibaca_pada')->nullable();
            $table->timestamp('selesai_pada')->nullable();
            $table->timestamps();
        });

        // Log Aktivitas (Activity Log) - Audit trail
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('pengguna')->cascadeOnDelete();
            $table->string('aksi'); // create, update, delete, login, logout, approve, reject
            $table->string('modul'); // pegawai, kehadiran, surat, keuangan, etc.
            $table->string('deskripsi');
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('data_lama')->nullable();
            $table->json('data_baru')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['pengguna_id', 'created_at']);
            $table->index(['modul', 'aksi']);
        });

        // Catatan Harian Staff (Daily Work Journal)
        Schema::create('catatan_harian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('pengguna')->cascadeOnDelete();
            $table->date('tanggal');
            $table->text('kegiatan');
            $table->text('hasil')->nullable();
            $table->text('kendala')->nullable();
            $table->text('rencana_besok')->nullable();
            $table->enum('status', ['draft', 'final'])->default('draft');
            $table->timestamps();

            $table->unique(['pengguna_id', 'tanggal']);
        });

        // Resolusi/Keputusan Kepala Sekolah
        Schema::create('resolusi', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_resolusi')->unique();
            $table->string('judul');
            $table->text('latar_belakang');
            $table->text('isi_keputusan');
            $table->text('tindak_lanjut')->nullable();
            $table->enum('kategori', ['kebijakan', 'sanksi', 'penghargaan', 'mutasi', 'anggaran', 'kurikulum', 'lainnya'])->default('kebijakan');
            $table->enum('status', ['draft', 'berlaku', 'dicabut'])->default('draft');
            $table->date('tanggal_berlaku');
            $table->date('tanggal_berakhir')->nullable();
            $table->foreignId('dibuat_oleh')->constrained('pengguna')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resolusi');
        Schema::dropIfExists('catatan_harian');
        Schema::dropIfExists('log_aktivitas');
        Schema::dropIfExists('disposisi_surat');
    }
};
