<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JawabanPercobaan extends Model
{
    protected $table = 'jawaban_percobaan';

    public $timestamps = false;

    protected $fillable = [
        'percobaan_id', 'soal_id', 'jawaban_dipilih',
        'benar', 'waktu_menjawab', 'poin_diperoleh', 'dijawab_pada',
    ];

    protected function casts(): array
    {
        return [
            'benar'       => 'boolean',
            'dijawab_pada' => 'datetime',
        ];
    }

    public function percobaan(): BelongsTo
    {
        return $this->belongsTo(PercobaanKuis::class, 'percobaan_id');
    }

    public function soal(): BelongsTo
    {
        return $this->belongsTo(Soal::class);
    }
}
