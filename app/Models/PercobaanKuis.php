<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PercobaanKuis extends Model
{
    protected $table = 'percobaan_kuis';

    protected $fillable = [
        'kuis_id', 'siswa_id', 'urutan_acak', 'opsi_acak',
        'nilai', 'jumlah_benar', 'jumlah_salah', 'total_soal',
        'poin_diperoleh', 'waktu_pengerjaan', 'status',
        'dimulai_pada', 'diselesaikan_pada',
    ];

    protected function casts(): array
    {
        return [
            'urutan_acak'      => 'array',
            'opsi_acak'        => 'array',
            'dimulai_pada'     => 'datetime',
            'diselesaikan_pada' => 'datetime',
        ];
    }

    public function kuis(): BelongsTo
    {
        return $this->belongsTo(Kuis::class);
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jawabanPercobaan(): HasMany
    {
        return $this->hasMany(JawabanPercobaan::class, 'percobaan_id');
    }
}
