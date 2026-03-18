<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('kode_kuis')->unique();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->unsignedInteger('jumlah_soal')->default(10);
            $table->unsignedInteger('waktu_per_soal')->default(30);
            $table->boolean('acak_soal')->default(true);
            $table->boolean('acak_opsi')->default(true);
            $table->boolean('tampilkan_penjelasan')->default(true);
            $table->boolean('aktif')->default(false);
            $table->timestamp('mulai_pada')->nullable();
            $table->timestamp('berakhir_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuis');
    }
};
