<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lencana extends Model
{
    protected $table = 'lencana';

    protected $fillable = [
        'kunci_lencana', 'nama', 'deskripsi', 'ikon',
        'warna', 'jenis_kondisi', 'nilai_kondisi',
    ];

    public function siswa(): BelongsToMany
    {
        return $this->belongsToMany(Siswa::class, 'lencana_siswa')
            ->withPivot('diperoleh_pada');
    }
}
