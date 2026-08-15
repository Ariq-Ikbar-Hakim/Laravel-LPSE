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
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_telp')->after('email');
            $table->dropColumn(['sub_unit_opd', 'sk_nomor']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('sub_unit_opd')->after('opd');
            $table->string('sk_nomor')->after('jabatan_aktif');
            $table->dropColumn('no_telp');
        });
    }
};
