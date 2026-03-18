<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatBaca extends Model
{
    protected $table = 'riwayat_baca';

    public $timestamps = false;

    protected $fillable = ['siswa_id', 'materi_id', 'dibaca_pada', 'poin_diperoleh'];

    protected $casts = [
        'dibaca_pada' => 'datetime',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function materi(): BelongsTo
    {
        return $this->belongsTo(Materi::class);
    }
}
