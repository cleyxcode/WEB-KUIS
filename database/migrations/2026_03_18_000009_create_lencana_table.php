<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lencana', function (Blueprint $table) {
            $table->id();
            $table->string('kunci_lencana')->unique();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->string('ikon')->default('🏅');
            $table->string('warna')->default('teal');
            $table->enum('jenis_kondisi', ['login', 'baca', 'kuis', 'nilai', 'streak', 'poin', 'peringkat']);
            $table->unsignedInteger('nilai_kondisi')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lencana');
    }
};
