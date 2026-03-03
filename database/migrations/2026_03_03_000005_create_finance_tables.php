<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Keuangan / Anggaran
        Schema::create('finance_records', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->string('jenis'); // pemasukan, pengeluaran
            $table->string('kategori'); // bos, apbd, spp, operasional, gaji, pengadaan, lainnya
            $table->string('uraian');
            $table->decimal('jumlah', 15, 2);
            $table->date('tanggal');
            $table->string('bukti_path')->nullable(); // kwitansi / bukti transfer
            $table->string('bukti_nama')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('status')->default('draft'); // draft, verified, approved
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        // Anggaran / RKAS
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('nama_anggaran');
            $table->string('tahun_anggaran');
            $table->string('sumber_dana'); // BOS, APBD, Komite
            $table->decimal('total_anggaran', 15, 2);
            $table->decimal('terpakai', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->string('status')->default('draft'); // draft, active, closed
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budgets');
        Schema::dropIfExists('finance_records');
    }
};
