<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function index(\App\Services\BadgeService $badgeService): View
    {
        $siswa = Siswa::with(['lencana', 'logPoin' => fn($q) => $q->latest()->take(10)])
            ->findOrFail(session('siswa_id'));

        // Cek lencana
        $badgeService->checkAndAward($siswa);
        $siswa->load('lencana');

        $totalKuis = $siswa->percobaanKuis()->where('status', 'selesai')->count();
        $rataRataNilai = $siswa->percobaanKuis()->where('status', 'selesai')->avg('nilai');

        $riwayatKuis = $siswa->percobaanKuis()
            ->where('status', 'selesai')
            ->with('kuis.mataPelajaran')
            ->latest('diselesaikan_pada')
            ->paginate(10);

        return view('siswa.profil', compact(
            'siswa',
            'totalKuis',
            'rataRataNilai',
            'riwayatKuis'
        ));
    }
}
