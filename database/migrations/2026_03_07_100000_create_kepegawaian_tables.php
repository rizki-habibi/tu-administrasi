<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom kepegawaian di tabel pengguna
        Schema::table('pengguna', function (Blueprint $table) {
            $table->string('golongan')->nullable()->after('jabatan');
            $table->string('pangkat')->nullable()->after('golongan');
            $table->string('pendidikan_terakhir')->nullable()->after('pangkat');
            $table->date('tmt_cpns')->nullable()->after('pendidikan_terakhir');
            $table->date('tmt_pns')->nullable()->after('tmt_cpns');
            $table->string('jenis_pegawai')->nullable()->after('tmt_pns'); // PNS, PPPK, Honorer, GTT, PTT
            $table->string('unit_kerja')->nullable()->after('jenis_pegawai');
        });

        // Tabel riwayat jabatan
        Schema::create('riwayat_jabatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('pengguna')->cascadeOnDelete();
            $table->string('nama_jabatan');
            $table->string('unit_kerja')->nullable();
            $table->date('tmt_jabatan'); // Terhitung Mulai Tanggal
            $table->date('tmt_selesai')->nullable();
            $table->string('nomor_sk')->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->string('pejabat_penetap')->nullable();
            $table->string('file_sk')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['pengguna_id', 'tmt_jabatan']);
        });

        // Tabel riwayat pangkat / golongan
        Schema::create('riwayat_pangkat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('pengguna')->cascadeOnDelete();
            $table->string('pangkat'); // Penata Muda, Penata Muda Tk.I, dll
            $table->string('golongan'); // III/a, III/b, dll
            $table->date('tmt_pangkat'); // Terhitung Mulai Tanggal
            $table->string('nomor_sk')->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->string('pejabat_penetap')->nullable();
            $table->string('jenis_kenaikan')->nullable(); // reguler, pilihan, penyesuaian
            $table->string('file_sk')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['pengguna_id', 'tmt_pangkat']);
        });

        // Tabel dokumen kepegawaian (arsip digital)
        Schema::create('dokumen_kepegawaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('pengguna')->cascadeOnDelete();
            $table->string('judul');
            $table->enum('kategori', [
                'sk_cpns', 'sk_pns', 'sk_kenaikan_pangkat', 'sk_jabatan',
                'sk_mutasi', 'ijazah', 'sertifikat', 'piagam',
                'kgb', 'dp3_ppk', 'skp', 'sttpl', 'lainnya'
            ])->default('lainnya');
            $table->string('nomor_dokumen')->nullable();
            $table->date('tanggal_dokumen')->nullable();
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['pengguna_id', 'kategori']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_kepegawaian');
        Schema::dropIfExists('riwayat_pangkat');
        Schema::dropIfExists('riwayat_jabatan');

        Schema::table('pengguna', function (Blueprint $table) {
            $table->dropColumn([
                'golongan', 'pangkat', 'pendidikan_terakhir',
                'tmt_cpns', 'tmt_pns', 'jenis_pegawai', 'unit_kerja'
            ]);
        });
    }
};
