<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SiswaAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! session('siswa_id')) {
            return redirect()->route('siswa.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data siswa
        $siswa = \App\Models\Siswa::find(session('siswa_id'));
        
        if (!$siswa) {
            return redirect()->route('siswa.login')
                ->with('error', 'Sesi tidak valid.');
        }

        // Cek dan update streak harian
        if ($siswa->checkAndUpdateStreak()) {
            // Jika streak atau terakhir aktif diperbarui, cek lencana
            app(\App\Services\BadgeService::class)->checkAndAward($siswa);
        }

        // Share data siswa ke semua view
        view()->share('currentSiswa', $siswa);

        return $next($request);
    }
}
