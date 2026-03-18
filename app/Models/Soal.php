<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Soal extends Model
{
    protected $table = 'soal';

    protected $fillable = [
        'mata_pelajaran_id', 'user_id', 'teks_soal', 'gambar_soal',
        'opsi_a', 'gambar_opsi_a', 'opsi_b', 'gambar_opsi_b',
        'opsi_c', 'gambar_opsi_c', 'opsi_d', 'gambar_opsi_d',
        'jawaban_benar', 'penjelasan', 'tingkat_kesulitan',
    ];

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kuis(): BelongsToMany
    {
        return $this->belongsToMany(Kuis::class, 'kuis_soal')->withPivot('urutan');
    }
}
