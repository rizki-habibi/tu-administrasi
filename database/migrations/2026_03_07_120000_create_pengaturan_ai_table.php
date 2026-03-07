<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_ai', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 50)->default('gemini'); // gemini, openai, anthropic, custom
            $table->string('nama_tampilan', 100)->default('Google Gemini');
            $table->text('api_key');
            $table->string('model', 100)->default('gemini-2.0-flash');
            $table->string('base_url', 500)->nullable(); // for custom providers
            $table->boolean('aktif')->default(true);
            $table->json('opsi')->nullable(); // temperature, max_tokens, etc.
            $table->foreignId('diperbarui_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_ai');
    }
};
