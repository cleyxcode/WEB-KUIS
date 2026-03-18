<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Kuis;
use App\Models\Materi;
use App\Models\RiwayatBaca;
use App\Models\Siswa;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $siswa = Siswa::with('lencana')->findOrFail(session('siswa_id'));

        // Hitung peringkat
        $peringkat = Siswa::where('aktif', true)
            ->where('total_poin', '>', $siswa->total_poin)
            ->count() + 1;

        // Progress ke kelipatan 500 berikutnya
        $kelipatan   = 500;
        $poinDiLevel = $siswa->total_poin % $kelipatan;
        $progressPct = $kelipatan > 0 ? round(($poinDiLevel / $kelipatan) * 100) : 0;
        $targetPoin  = (floor($siswa->total_poin / $kelipatan) + 1) * $kelipatan;

        $kuisAktif = Kuis::where('aktif', true)
            ->where(function ($q) {
                $q->whereNull('berakhir_pada')->orWhere('berakhir_pada', '>', now());
            })
            ->where(function ($q) {
                $q->whereNull('mulai_pada')->orWhere('mulai_pada', '<=', now());
            })
            ->with('mataPelajaran')
            ->latest()
            ->take(6)
            ->get();

        $riwayatKuis = $siswa->percobaanKuis()
            ->where('status', 'selesai')
            ->with('kuis.mataPelajaran')
            ->latest('diselesaikan_pada')
            ->take(5)
            ->get();

        // Materi terbaru yang dipublikasi
        $materiTerbaru = Materi::where('dipublikasi', true)
            ->with('mataPelajaran')
            ->latest()
            ->take(8)
            ->get();

        // ID materi yang sudah dibaca siswa ini
        $sudahBacaIds = RiwayatBaca::where('siswa_id', $siswa->id)
            ->pluck('materi_id')
            ->toArray();

        return view('siswa.dashboard', compact(
            'siswa', 'peringkat', 'progressPct', 'targetPoin',
            'kuisAktif', 'riwayatKuis', 'materiTerbaru', 'sudahBacaIds'
        ));
    }
}
