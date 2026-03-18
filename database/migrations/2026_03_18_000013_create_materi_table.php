<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('judul');
            $table->string('bab')->nullable();
            $table->text('deskripsi')->nullable();
            $table->longText('konten')->nullable();
            $table->string('gambar_sampul')->nullable();
            $table->string('file_pdf')->nullable();
            $table->boolean('dipublikasi')->default(false);
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('riwayat_baca', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('materi_id')->constrained('materi')->cascadeOnDelete();
            $table->timestamp('dibaca_pada')->useCurrent();
            $table->unsignedInteger('poin_diperoleh')->default(3);
            $table->unique(['siswa_id', 'materi_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_baca');
        Schema::dropIfExists('materi');
    }
};
