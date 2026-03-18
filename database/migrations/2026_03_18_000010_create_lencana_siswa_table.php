<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lencana_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('lencana_id')->constrained('lencana')->cascadeOnDelete();
            $table->timestamp('diperoleh_pada')->nullable();
            $table->unique(['siswa_id', 'lencana_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lencana_siswa');
    }
};
