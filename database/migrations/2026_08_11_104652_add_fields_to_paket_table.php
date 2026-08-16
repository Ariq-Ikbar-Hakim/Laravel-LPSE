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
        Schema::table('paket', function (Blueprint $table) {
            $table->string('tahun_anggaran')->nullable()->after('jenis');
            $table->text('keterangan_tambahan')->nullable()->after('tahun_anggaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paket', function (Blueprint $table) {
            $table->dropColumn(['tahun_anggaran', 'keterangan_tambahan']);
        });
    }
};
