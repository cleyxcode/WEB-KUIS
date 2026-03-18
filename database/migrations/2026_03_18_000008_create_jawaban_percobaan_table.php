<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jawaban_percobaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('percobaan_id')->constrained('percobaan_kuis')->cascadeOnDelete();
            $table->foreignId('soal_id')->constrained('soal')->cascadeOnDelete();
            $table->enum('jawaban_dipilih', ['a', 'b', 'c', 'd'])->nullable();
            $table->boolean('benar')->default(false);
            $table->unsignedInteger('waktu_menjawab')->default(0);
            $table->unsignedInteger('poin_diperoleh')->default(0);
            $table->timestamp('dijawab_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban_percobaan');
    }
};
