<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'nis', 'kode_siswa', 'nama_lengkap', 'nama_panggilan',
        'kelas', 'jenis_kelamin', 'foto', 'aktif',
        'total_poin', 'streak_sekarang', 'streak_terpanjang', 'terakhir_aktif',
    ];

    protected function casts(): array
    {
        return [
            'aktif'         => 'boolean',
            'terakhir_aktif' => 'datetime',
        ];
    }

    public function percobaanKuis(): HasMany
    {
        return $this->hasMany(PercobaanKuis::class);
    }

    public function lencana(): BelongsToMany
    {
        return $this->belongsToMany(Lencana::class, 'lencana_siswa')
            ->withPivot('diperoleh_pada')
            ->withCasts(['diperoleh_pada' => 'datetime']);
    }

    public function logPoin(): HasMany
    {
        return $this->hasMany(LogPoin::class);
    }

}
