<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nis')->unique();
            $table->string('kode_siswa')->unique();
            $table->string('nama_lengkap');
            $table->string('nama_panggilan');
            $table->string('kelas')->default('V');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('foto')->nullable();
            $table->boolean('aktif')->default(true);
            $table->unsignedBigInteger('total_poin')->default(0);
            $table->unsignedInteger('streak_sekarang')->default(0);
            $table->unsignedInteger('streak_terpanjang')->default(0);
            $table->timestamp('terakhir_aktif')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
