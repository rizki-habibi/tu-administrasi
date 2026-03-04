<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update role enum to support new roles
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','kepala_sekolah','kepegawaian','pramu_bakti','keuangan','persuratan','perpustakaan','inventaris','kesiswaan_kurikulum','staff') DEFAULT 'staff'");

        // Add kode_depan and iki_pelaksana fields
        Schema::table('users', function (Blueprint $table) {
            $table->string('kode_depan')->nullable()->after('position');
            $table->string('iki_pelaksana')->nullable()->after('kode_depan');
            $table->string('nip')->nullable()->after('name');
        });

        // Create SKP table
        Schema::create('skp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('periode'); // e.g. 'Semester 1 2025/2026'
            $table->year('tahun');
            $table->string('sasaran_kinerja'); // target kinerja
            $table->text('indikator_kinerja')->nullable();
            $table->decimal('target_kuantitas', 8, 2)->default(0);
            $table->decimal('realisasi_kuantitas', 8, 2)->default(0);
            $table->decimal('target_kualitas', 5, 2)->default(0); // percentage
            $table->decimal('realisasi_kualitas', 5, 2)->default(0);
            $table->decimal('target_waktu', 8, 2)->default(0); // in months
            $table->decimal('realisasi_waktu', 8, 2)->default(0);
            $table->decimal('nilai_capaian', 5, 2)->default(0);
            $table->enum('predikat', ['sangat_baik', 'baik', 'cukup', 'kurang', 'buruk'])->default('baik');
            $table->text('catatan')->nullable();
            $table->enum('status', ['draft', 'diajukan', 'disetujui', 'ditolak'])->default('draft');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        // Add address_in/address_out to attendances for detailed location
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('address_in')->nullable()->after('longitude_in');
            $table->string('address_out')->nullable()->after('longitude_out');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skp');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['kode_depan', 'iki_pelaksana', 'nip']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['address_in', 'address_out']);
        });

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','staff') DEFAULT 'staff'");
    }
};
