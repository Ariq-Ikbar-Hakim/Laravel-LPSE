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
            $table->decimal('hps', 15, 2)->nullable()->after('pagu');
        });

        Schema::table('berita_acara', function (Blueprint $table) {
            $table->date('tanggal_ba')->nullable()->after('nomor_ba');
        });

        Schema::table('signatures', function (Blueprint $table) {
            $table->string('signature_image')->nullable()->after('qr_code_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paket', function (Blueprint $table) {
            $table->dropColumn('hps');
        });

        Schema::table('berita_acara', function (Blueprint $table) {
            $table->dropColumn('tanggal_ba');
        });

        Schema::table('signatures', function (Blueprint $table) {
            $table->dropColumn('signature_image');
        });
    }
};
