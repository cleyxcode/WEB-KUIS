<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    public function index(): View
    {
        $siswaLogin = Siswa::findOrFail(session('siswa_id'));

        $peringkat = Siswa::where('aktif', true)
            ->withCount('lencana')
            ->orderByDesc('total_poin')
            ->limit(50)
            ->get()
            ->map(function ($siswa, $index) use ($siswaLogin) {
                $siswa->rank  = $index + 1;
                $siswa->is_me = $siswa->id === $siswaLogin->id;
                return $siswa;
            });

        // posisi siswa login jika di luar top 50
        $rankSaya = $peringkat->firstWhere('id', $siswaLogin->id)?->rank;
        if (! $rankSaya) {
            $rankSaya = Siswa::where('aktif', true)
                ->where('total_poin', '>', $siswaLogin->total_poin)
                ->count() + 1;
        }

        // Poin yang dibutuhkan untuk naik ke peringkat atas
        $poinKeRankAtas = null;
        $rankIdx = $peringkat->search(fn ($s) => $s->id === $siswaLogin->id);
        if ($rankIdx !== false && $rankIdx > 0) {
            $diAtas          = $peringkat[$rankIdx - 1];
            $poinKeRankAtas  = $diAtas->total_poin - $siswaLogin->total_poin + 1;
        }

        return view('siswa.leaderboard', compact(
            'peringkat', 'siswaLogin', 'rankSaya', 'poinKeRankAtas'
        ));
    }
}
