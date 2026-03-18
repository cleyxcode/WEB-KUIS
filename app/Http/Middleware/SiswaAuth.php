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

        // Share data siswa ke semua view
        $siswa = \App\Models\Siswa::find(session('siswa_id'));
        view()->share('currentSiswa', $siswa);

        return $next($request);
    }
}
