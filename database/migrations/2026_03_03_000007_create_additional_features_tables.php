<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Penilaian Kinerja Guru (PKG) / BKD
        Schema::create('teacher_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('periode'); // Semester 1 2025/2026
            $table->string('jenis'); // pkg, bkd, skp
            $table->decimal('nilai', 5, 2)->nullable();
            $table->string('predikat')->nullable(); // amat_baik, baik, cukup, kurang
            $table->text('catatan')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('status')->default('draft'); // draft, submitted, verified
            $table->foreignId('evaluated_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        // Asesmen P5 (Projek Penguatan Profil Pelajar Pancasila)
        Schema::create('p5_assessments', function (Blueprint $table) {
            $table->id();
            $table->string('tema'); // Gaya Hidup Berkelanjutan, Kearifan Lokal, dll
            $table->string('judul_projek');
            $table->text('deskripsi')->nullable();
            $table->string('kelas'); // X, XI, XII
            $table->string('fase'); // E, F
            $table->string('academic_year');
            $table->string('semester');
            $table->string('dimensi'); // beriman, mandiri, gotong_royong, berkebinekaan, bernalar_kritis, kreatif
            $table->text('target_capaian')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('status')->default('draft');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // Metode STAR Analysis
        Schema::create('star_analyses', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori'); // pembelajaran, administrasi, manajemen
            $table->text('situation'); // Situasi
            $table->text('task'); // Tugas
            $table->text('action'); // Aksi
            $table->text('result'); // Hasil
            $table->text('refleksi')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->string('file_path')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // Bukti Fisik
        Schema::create('physical_evidences', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori'); // pembelajaran, administrasi, kegiatan, pengembangan_diri
            $table->text('deskripsi')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->string('terkait')->nullable(); // related feature: pkg, bkd, akreditasi, p5
            $table->foreignId('uploaded_by')->constrained('users');
            $table->timestamps();
        });

        // Model Pembelajaran / Metode Teknologi
        Schema::create('learning_methods', function (Blueprint $table) {
            $table->id();
            $table->string('nama_metode');
            $table->string('jenis'); // model_pembelajaran, teknologi_pembelajaran, media_pembelajaran
            $table->text('deskripsi');
            $table->text('langkah_pelaksanaan')->nullable();
            $table->text('kelebihan')->nullable();
            $table->text('kekurangan')->nullable();
            $table->text('hasil')->nullable();
            $table->string('mata_pelajaran')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('status')->default('draft'); // draft, published
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // Reminder / Pengingat
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type'); // deadline_laporan, bkd, evaluasi_semester, tugas, lainnya
            $table->date('due_date');
            $table->time('reminder_time')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->string('recurring_type')->nullable(); // daily, weekly, monthly, yearly
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users');
            $table->boolean('is_completed')->default(false);
            $table->boolean('is_notified')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
        Schema::dropIfExists('learning_methods');
        Schema::dropIfExists('physical_evidences');
        Schema::dropIfExists('star_analyses');
        Schema::dropIfExists('p5_assessments');
        Schema::dropIfExists('teacher_evaluations');
    }
};
