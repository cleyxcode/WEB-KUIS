<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('siswa.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_panggilan' => ['required', 'string'],
            'kode_siswa'     => ['required', 'string'],
        ]);

        $siswa = Siswa::where('nama_panggilan', $request->nama_panggilan)
            ->where('aktif', true)
            ->first();

        if (! $siswa || $siswa->kode_siswa !== strtoupper($request->kode_siswa)) {
            return back()->withErrors(['nama_panggilan' => 'Nama panggilan atau kode siswa salah.'])->withInput();
        }

        session(['siswa_id' => $siswa->id, 'siswa' => $siswa]);

        $siswa->update(['terakhir_aktif' => now()]);

        return redirect()->route('siswa.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget(['siswa_id', 'siswa']);

        return redirect()->route('siswa.login');
    }
}
