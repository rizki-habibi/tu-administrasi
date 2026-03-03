<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique()->comment('Auto-generated letter number');
            $table->enum('jenis', ['masuk', 'keluar'])->default('keluar');
            $table->enum('kategori', ['dinas', 'undangan', 'keterangan', 'keputusan', 'edaran', 'tugas', 'pemberitahuan', 'lainnya'])->default('dinas');
            $table->string('perihal');
            $table->text('isi')->nullable()->comment('Letter body / content');
            $table->string('tujuan')->nullable()->comment('Recipient organization/person');
            $table->string('asal')->nullable()->comment('Origin for incoming letters');
            $table->date('tanggal_surat');
            $table->date('tanggal_terima')->nullable()->comment('Date received for incoming');
            $table->enum('status', ['draft', 'diproses', 'dikirim', 'diterima', 'diarsipkan'])->default('draft');
            $table->enum('sifat', ['biasa', 'penting', 'segera', 'rahasia'])->default('biasa');
            $table->string('file_path')->nullable()->comment('Scanned/digital copy');
            $table->string('file_name')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
