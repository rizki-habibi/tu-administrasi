<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('skp', function (Blueprint $table) {
            if (!Schema::hasColumn('skp', 'catatan_revisi')) {
                $table->text('catatan_revisi')->nullable()->after('catatan');
            }
            if (!Schema::hasColumn('skp', 'ditolak_pada')) {
                $table->timestamp('ditolak_pada')->nullable()->after('disetujui_pada');
            }
            if (!Schema::hasColumn('skp', 'direvisi_pada')) {
                $table->timestamp('direvisi_pada')->nullable()->after('ditolak_pada');
            }
        });
    }

    public function down(): void
    {
        Schema::table('skp', function (Blueprint $table) {
            $table->dropColumn(['catatan_revisi', 'ditolak_pada', 'direvisi_pada']);
        });
    }
};
