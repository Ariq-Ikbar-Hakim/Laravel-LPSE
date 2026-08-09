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
        Schema::create('berita_acara', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paket_id')->constrained('paket')->cascadeOnDelete();
            $table->string('nomor_ba')->unique();
            $table->string('file_laporan')->nullable();
            $table->string('verification_hash')->unique();
            $table->string('status')->default('draft'); // draft, tanda_tangan_pertama, selesai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita_acara');
    }
};
