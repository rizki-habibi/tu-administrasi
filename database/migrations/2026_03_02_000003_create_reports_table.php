<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('category', ['surat_masuk', 'surat_keluar', 'inventaris', 'keuangan', 'kegiatan', 'lainnya'])->default('lainnya');
            $table->enum('priority', ['rendah', 'sedang', 'tinggi'])->default('sedang');
            $table->enum('status', ['draft', 'submitted', 'reviewed', 'completed'])->default('draft');
            $table->string('attachment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
