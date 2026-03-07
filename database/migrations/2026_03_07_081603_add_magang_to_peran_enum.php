<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE pengguna MODIFY COLUMN peran ENUM('admin','kepala_sekolah','kepegawaian','pramu_bakti','keuangan','persuratan','perpustakaan','inventaris','kesiswaan_kurikulum','staff','magang') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pengguna MODIFY COLUMN peran ENUM('admin','kepala_sekolah','kepegawaian','pramu_bakti','keuangan','persuratan','perpustakaan','inventaris','kesiswaan_kurikulum','staff') NOT NULL");
    }
};
