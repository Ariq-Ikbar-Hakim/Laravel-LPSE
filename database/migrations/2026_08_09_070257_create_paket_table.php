<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Run the migrations.
    public function up(): void
    {
        Schema::create('paket', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ppk_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('pp_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('kode_rup');
            $table->string('nama_paket');
            $table->decimal('pagu', 15, 2);
            $table->string('status')->default('draft');
            $table->timestamp('dilihat_admin_at')->nullable();
            $table->string('metode')->nullable();
            $table->string('sumber_dana')->nullable();
            $table->string('jenis')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paket');
    }
};
