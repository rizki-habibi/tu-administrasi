<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_pengguna', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('pengguna')->cascadeOnDelete();
            $table->string('kunci'); // e.g. 'tema', 'ukuran_font', 'sidebar_mini', etc.
            $table->text('nilai')->nullable();
            $table->timestamps();

            $table->unique(['pengguna_id', 'kunci']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_pengguna');
    }
};
