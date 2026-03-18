<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('percobaan_kuis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kuis_id')->constrained('kuis')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->json('urutan_acak')->nullable();
            $table->json('opsi_acak')->nullable();
            $table->decimal('nilai', 5, 2)->default(0);
            $table->unsignedInteger('jumlah_benar')->default(0);
            $table->unsignedInteger('jumlah_salah')->default(0);
            $table->unsignedInteger('total_soal')->default(0);
            $table->unsignedInteger('poin_diperoleh')->default(0);
            $table->unsignedInteger('waktu_pengerjaan')->default(0);
            $table->enum('status', ['berlangsung', 'selesai'])->default('berlangsung');
            $table->timestamp('dimulai_pada')->nullable();
            $table->timestamp('diselesaikan_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('percobaan_kuis');
    }
};
