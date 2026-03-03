<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('word_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('category')->default('umum'); // umum, surat, laporan, sk, notulen, proposal, keuangan
            $table->longText('content')->nullable(); // HTML content from editor
            $table->longText('ai_prompt')->nullable(); // AI prompt used to generate
            $table->string('template')->nullable(); // template type used
            $table->string('file_path')->nullable(); // path to saved .docx file
            $table->string('status')->default('draft'); // draft, final, archived
            $table->boolean('is_shared')->default(false); // shared = visible to all
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('word_documents');
    }
};
