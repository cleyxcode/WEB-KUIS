<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\LogPoin;
use App\Models\Materi;
use App\Models\MataPelajaran;
use App\Models\RiwayatBaca;
use Illuminate\Http\Request;

class MateriController extends Controller
{
    public function index(Request $request)
    {
        $siswa = session('siswa');

        $mapel = MataPelajaran::orderBy('urutan')->get();
        $jenisFilter = $request->query('jenis');
        $cari        = trim($request->query('cari', ''));

        $query = Materi::with('mataPelajaran')
            ->where('dipublikasi', true)
            ->orderBy('urutan')
            ->orderBy('created_at');

        if ($jenisFilter && in_array($jenisFilter, ['IPA', 'IPS'])) {
            $query->whereHas('mataPelajaran', fn ($q) => $q->where('jenis', $jenisFilter));
        }

        if ($cari !== '') {
            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                  ->orWhere('bab', 'like', "%{$cari}%")
                  ->orWhere('deskripsi', 'like', "%{$cari}%");
            });
        }

        $materiList = $query->get();

        // Materi yang sudah dibaca oleh siswa ini
        $sudahBaca = RiwayatBaca::where('siswa_id', $siswa->id)
            ->pluck('materi_id')
            ->toArray();

        $totalMateri  = $materiList->count();
        $jumlahDibaca = collect($sudahBaca)->intersect($materiList->pluck('id'))->count();

        return view('siswa.materi.index', compact(
            'materiList', 'mapel', 'jenisFilter', 'cari',
            'sudahBaca', 'totalMateri', 'jumlahDibaca'
        ));
    }

    public function show(Materi $materi)
    {
        if (! $materi->dipublikasi) {
            abort(404);
        }

        $materi->load('mataPelajaran');
        $siswa = session('siswa');

        $sudahDibaca = RiwayatBaca::where('siswa_id', $siswa->id)
            ->where('materi_id', $materi->id)
            ->exists();

        return view('siswa.materi.show', compact('materi', 'sudahDibaca'));
    }

    public function klaim(Request $request, Materi $materi)
    {
        if (! $materi->dipublikasi) {
            abort(404);
        }

        $siswa = session('siswa');

        // Cek sudah pernah klaim
        $sudah = RiwayatBaca::where('siswa_id', $siswa->id)
            ->where('materi_id', $materi->id)
            ->exists();

        if ($sudah) {
            return redirect()->route('siswa.materi.show', $materi->id)
                ->with('info', 'Kamu sudah mengklaim poin untuk materi ini.');
        }

        $poin = 3;

        // Simpan riwayat baca
        RiwayatBaca::create([
            'siswa_id'      => $siswa->id,
            'materi_id'     => $materi->id,
            'dibaca_pada'   => now(),
            'poin_diperoleh' => $poin,
        ]);

        // Tambah poin ke siswa
        $siswa->increment('total_poin', $poin);

        // Log poin
        LogPoin::create([
            'siswa_id'  => $siswa->id,
            'poin'      => $poin,
            'sumber'    => 'materi',
            'sumber_id' => $materi->id,
            'keterangan' => 'Membaca materi: ' . $materi->judul,
        ]);

        // Refresh session siswa
        $siswa->refresh();
        session(['siswa' => $siswa]);

        return redirect()->route('siswa.materi.show', $materi->id)
            ->with('success', "+{$poin} poin didapat! Terus semangat belajar! 🎉");
    }
}
