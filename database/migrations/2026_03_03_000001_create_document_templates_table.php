<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // e.g., sk_pengangkatan, surat_pengantar
            $table->string('category'); // akademik, kepegawaian, kesiswaan, sarpras, keuangan, akreditasi
            $table->text('content'); // HTML template content
            $table->json('fields')->nullable(); // dynamic fields for template
            $table->string('format')->default('pdf'); // pdf, docx
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_templates');
    }
};
