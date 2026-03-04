<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('arsip_cadangan')) {
            // Table already exists (partial migration), just add FK if missing
            if (!Schema::hasColumn('arsip_cadangan', 'pengguna_id')) {
                Schema::table('arsip_cadangan', function (Blueprint $table) {
                    $table->foreignId('pengguna_id')->nullable()->constrained('pengguna')->nullOnDelete();
                });
            }
            return;
        }

        Schema::create('arsip_cadangan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_file');
            $table->string('google_drive_id')->nullable();
            $table->string('path_lokal')->nullable();
            $table->string('jenis')->default('penuh'); // penuh, database, file
            $table->unsignedBigInteger('ukuran_byte')->default(0);
            $table->string('status')->default('berhasil'); // berhasil, gagal, sedang_proses
            $table->text('catatan')->nullable();
            $table->foreignId('pengguna_id')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_cadangan');
    }
};
