<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom tanggal_lahir ke tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->date('tanggal_lahir')->nullable()->after('phone');
        });

        // Tabel ucapan ulang tahun (birthday greetings chat)
        Schema::create('ucapan_ulang_tahun', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengirim_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('penerima_id')->constrained('users')->cascadeOnDelete();
            $table->text('pesan');
            $table->year('tahun');
            $table->boolean('sudah_dibaca')->default(false);
            $table->timestamps();
        });

        // Tabel catatan beranda (dashboard notes/notulen)
        Schema::create('catatan_beranda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('judul');
            $table->text('isi');
            $table->string('warna', 20)->default('primary');
            $table->date('tanggal')->nullable();
            $table->boolean('disematkan')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catatan_beranda');
        Schema::dropIfExists('ucapan_ulang_tahun');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('tanggal_lahir');
        });
    }
};
