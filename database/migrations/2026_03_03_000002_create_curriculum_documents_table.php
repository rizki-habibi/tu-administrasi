<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curriculum_documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type'); // kalender_pendidikan, jadwal_pelajaran, rpp, silabus, modul_ajar, kisi_kisi, analisis_butir_soal
            $table->string('academic_year'); // 2025/2026
            $table->string('semester')->nullable(); // ganjil, genap
            $table->string('subject')->nullable(); // mata pelajaran
            $table->string('class_level')->nullable(); // X, XI, XII
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->string('status')->default('draft'); // draft, active, archived
            $table->foreignId('uploaded_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curriculum_documents');
    }
};
