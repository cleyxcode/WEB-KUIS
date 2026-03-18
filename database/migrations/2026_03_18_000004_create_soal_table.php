<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('teks_soal');
            $table->string('gambar_soal')->nullable();
            $table->text('opsi_a');
            $table->string('gambar_opsi_a')->nullable();
            $table->text('opsi_b');
            $table->string('gambar_opsi_b')->nullable();
            $table->text('opsi_c');
            $table->string('gambar_opsi_c')->nullable();
            $table->text('opsi_d');
            $table->string('gambar_opsi_d')->nullable();
            $table->enum('jawaban_benar', ['a', 'b', 'c', 'd']);
            $table->text('penjelasan')->nullable();
            $table->enum('tingkat_kesulitan', ['mudah', 'sedang', 'sulit'])->default('sedang');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soal');
    }
};
