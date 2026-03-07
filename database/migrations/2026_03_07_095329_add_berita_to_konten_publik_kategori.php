<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE konten_publik MODIFY COLUMN kategori ENUM('profil','visi_misi','pengurus','dokumen','galeri','video','kerjasama','prestasi','pengumuman','saran','berita') DEFAULT 'dokumen'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE konten_publik MODIFY COLUMN kategori ENUM('profil','visi_misi','pengurus','dokumen','galeri','video','kerjasama','prestasi','pengumuman','saran') DEFAULT 'dokumen'");
    }
};
