<?php

namespace App\Services;

use App\Models\Lencana;
use App\Models\Siswa;
use App\Models\PercobaanKuis;
use App\Models\RiwayatBaca;
use Illuminate\Support\Facades\DB;

class BadgeService
{
    /**
     * Cek dan berikan lencana yang memenuhi syarat kepada siswa.
     */
    public function checkAndAward(Siswa $siswa): void
    {
        $allLencana = Lencana::all();
        $siswaLencanaIds = $siswa->lencana()->pluck('lencana.id')->toArray();

        foreach ($allLencana as $lencana) {
            // Lewati jika sudah punya
            if (in_array($lencana->id, $siswaLencanaIds)) {
                continue;
            }

            if ($this->shouldAward($siswa, $lencana)) {
                $siswa->lencana()->attach($lencana->id, [
                    'diperoleh_pada' => now()
                ]);
            }
        }
    }

    /**
     * Logika penentuan apakah lencana harus diberikan.
     */
    protected function shouldAward(Siswa $siswa, Lencana $lencana): bool
    {
        $kondisi = $lencana->jenis_kondisi;
        $target = $lencana->nilai_kondisi;
        $kunci = $lencana->kunci_lencana;

        switch ($kondisi) {
            case 'login':
                // Untuk saat ini, login_pertama diberikan jika sudah login (masuk ke dashboard)
                return true;

            case 'baca':
                $count = RiwayatBaca::where('siswa_id', $siswa->id)->count();
                return $count >= $target;

            case 'kuis':
                if ($kunci === 'pakar_ipa') {
                    $count = PercobaanKuis::where('siswa_id', $siswa->id)
                        ->where('status', 'selesai')
                        ->whereHas('kuis.mataPelajaran', fn($q) => $q->where('jenis', 'IPA'))
                        ->count();
                    return $count >= $target;
                }

                if ($kunci === 'pakar_ips') {
                    $count = PercobaanKuis::where('siswa_id', $siswa->id)
                        ->where('status', 'selesai')
                        ->whereHas('kuis.mataPelajaran', fn($q) => $q->where('jenis', 'IPS'))
                        ->count();
                    return $count >= $target;
                }

                // Default: total kuis apa saja
                $count = PercobaanKuis::where('siswa_id', $siswa->id)
                    ->where('status', 'selesai')
                    ->count();
                return $count >= $target;

            case 'nilai':
                // Cek apakah ada kuis dengan nilai >= target
                return PercobaanKuis::where('siswa_id', $siswa->id)
                    ->where('status', 'selesai')
                    ->where('nilai', '>=', $target)
                    ->exists();

            case 'streak':
                return $siswa->streak_terpanjang >= $target || $siswa->streak_sekarang >= $target;

            case 'poin':
                return $siswa->total_poin >= $target;

            case 'peringkat':
                // Hitung peringkat saat ini
                $peringkat = Siswa::where('aktif', true)
                    ->where('total_poin', '>', $siswa->total_poin)
                    ->count() + 1;
                return $peringkat <= $target;

            default:
                return false;
        }
    }
}
