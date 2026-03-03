<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_records', function (Blueprint $table) {
            $table->id();
            $table->string('nis')->unique(); // Nomor Induk Siswa
            $table->string('nisn')->nullable()->unique(); // Nomor Induk Siswa Nasional
            $table->string('name');
            $table->string('class'); // X IPA 1, XI IPS 2, etc
            $table->string('academic_year');
            $table->enum('gender', ['L', 'P']);
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('religion')->nullable();
            $table->text('address')->nullable();
            $table->string('parent_name')->nullable();
            $table->string('parent_phone')->nullable();
            $table->string('photo')->nullable();
            $table->enum('status', ['aktif', 'mutasi_masuk', 'mutasi_keluar', 'lulus', 'do'])->default('aktif');
            $table->date('entry_date')->nullable();
            $table->date('exit_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('student_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('student_records')->cascadeOnDelete();
            $table->string('title');
            $table->string('level'); // sekolah, kabupaten, provinsi, nasional, internasional
            $table->string('type'); // akademik, non_akademik
            $table->date('date');
            $table->string('organizer')->nullable();
            $table->string('result')->nullable(); // juara 1, juara 2, etc
            $table->string('file_path')->nullable(); // sertifikat
            $table->timestamps();
        });

        Schema::create('student_violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('student_records')->cascadeOnDelete();
            $table->date('date');
            $table->string('type'); // ringan, sedang, berat
            $table->text('description');
            $table->text('action_taken')->nullable(); // tindakan/pembinaan
            $table->foreignId('reported_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_violations');
        Schema::dropIfExists('student_achievements');
        Schema::dropIfExists('student_records');
    }
};
