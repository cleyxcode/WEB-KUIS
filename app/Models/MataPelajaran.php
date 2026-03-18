<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';

    protected $fillable = ['nama', 'jenis', 'ikon', 'warna', 'urutan'];

    public function soal(): HasMany
    {
        return $this->hasMany(Soal::class);
    }

    public function kuis(): HasMany
    {
        return $this->hasMany(Kuis::class);
    }

    public function materi(): HasMany
    {
        return $this->hasMany(Materi::class);
    }
}
