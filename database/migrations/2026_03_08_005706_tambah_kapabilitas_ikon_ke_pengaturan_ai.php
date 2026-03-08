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
        Schema::table('pengaturan_ai', function (Blueprint $table) {
            $table->json('kapabilitas')->nullable()->after('opsi');
            $table->string('ikon', 100)->nullable()->after('kapabilitas');
            $table->string('warna_tema', 50)->nullable()->after('ikon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaturan_ai', function (Blueprint $table) {
            $table->dropColumn(['kapabilitas', 'ikon', 'warna_tema']);
        });
    }
};
