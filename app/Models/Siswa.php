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

    public function checkAndUpdateStreak(): bool
    {
        $now = now();
        $lastActive = $this->terakhir_aktif;

        // Belum pernah aktif sama sekali
        if (!$lastActive) {
            $this->streak_sekarang = 1;
            $this->streak_terpanjang = max($this->streak_terpanjang ?? 0, 1);
            $this->terakhir_aktif = $now;
            $this->save();
            return true;
        }

        if ($lastActive->isToday()) {
            // Sudah aktif hari ini — cek jika streak masih 0 (data lama yang belum ter-inisialisasi)
            if ($this->streak_sekarang == 0) {
                $this->streak_sekarang = 1;
                $this->streak_terpanjang = max($this->streak_terpanjang ?? 0, 1);
                $this->save();
                return true;
            }
            return false; // Sudah dihitung hari ini, tidak perlu update
        }

        if ($lastActive->isYesterday()) {
            // Aktif hari berturut-turut, tambah streak
            $this->streak_sekarang += 1;
            if ($this->streak_sekarang > $this->streak_terpanjang) {
                $this->streak_terpanjang = $this->streak_sekarang;
            }
        } else {
            // Lebih dari sehari tidak aktif, reset streak
            $this->streak_sekarang = 1;
        }

        $this->terakhir_aktif = $now;
        $this->save();
        return true;
    }
}
