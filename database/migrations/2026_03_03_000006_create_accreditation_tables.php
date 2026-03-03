<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Akreditasi
        Schema::create('accreditation_documents', function (Blueprint $table) {
            $table->id();
            $table->string('standar'); // standar_isi, standar_proses, standar_kompetensi_lulusan, standar_pendidik, standar_sarpras, standar_pengelolaan, standar_pembiayaan, standar_penilaian
            $table->string('komponen');
            $table->string('indikator');
            $table->text('deskripsi')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('status')->default('belum_lengkap'); // belum_lengkap, lengkap, diverifikasi
            $table->text('catatan')->nullable();
            $table->foreignId('uploaded_by')->constrained('users');
            $table->timestamps();
        });

        // Evaluasi Diri Sekolah (EDS)
        Schema::create('school_evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('tahun');
            $table->string('aspek');
            $table->text('kondisi_saat_ini')->nullable();
            $table->text('target')->nullable();
            $table->text('program_tindak_lanjut')->nullable();
            $table->string('status')->default('draft'); // draft, final
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_evaluations');
        Schema::dropIfExists('accreditation_documents');
    }
};
