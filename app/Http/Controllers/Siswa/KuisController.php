<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Kuis;
use App\Models\PercobaanKuis;
use App\Models\JawabanPercobaan;
use App\Models\LogPoin;
use App\Models\Siswa;
use App\Services\FisherYatesShuffleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class KuisController extends Controller
{
    public function formMasuk(): View
    {
        return view('siswa.kuis.masuk');
    }

    public function prosesKode(Request $request): RedirectResponse
    {
        $request->validate([
            'kode_kuis' => ['required', 'string', 'size:6'],
        ], [
            'kode_kuis.required' => 'Kode kuis tidak boleh kosong.',
            'kode_kuis.size'     => 'Kode kuis harus 6 karakter.',
        ]);

        $kode = strtoupper(trim($request->kode_kuis));
        $kuis = \App\Models\Kuis::where('kode_kuis', $kode)->where('aktif', true)->first();

        if (! $kuis) {
            return back()->withErrors(['kode_kuis' => 'Kode kuis tidak ditemukan atau sudah tidak aktif.']);
        }

        if ($kuis->berakhir_pada && $kuis->berakhir_pada->isPast()) {
            return back()->withErrors(['kode_kuis' => 'Kuis ini sudah berakhir.']);
        }

        if ($kuis->mulai_pada && $kuis->mulai_pada->isFuture()) {
            return back()->withErrors(['kode_kuis' => 'Kuis belum dimulai. Coba lagi nanti.']);
        }

        return redirect()->route('siswa.kuis.mulai', $kode);
    }

    public function index(): View
    {
        $siswaId = session('siswa_id');

        $kuisList = Kuis::where('aktif', true)
            ->where(function ($q) {
                $q->whereNull('berakhir_pada')->orWhere('berakhir_pada', '>', now());
            })
            ->where(function ($q) {
                $q->whereNull('mulai_pada')->orWhere('mulai_pada', '<=', now());
            })
            ->with('mataPelajaran')
            ->withCount('soal')
            ->latest()
            ->get();

        // tandai kuis yang sudah dikerjakan siswa
        $sudahDikerjakan = PercobaanKuis::where('siswa_id', $siswaId)
            ->where('status', 'selesai')
            ->pluck('kuis_id')
            ->toArray();

        return view('siswa.kuis.index', compact('kuisList', 'sudahDikerjakan'));
    }

    public function mulai(string $kodeKuis): RedirectResponse
    {
        $siswa = Siswa::findOrFail(session('siswa_id'));
        $kuis  = Kuis::where('kode_kuis', $kodeKuis)->where('aktif', true)->firstOrFail();

        // cegah mulai ulang jika sudah selesai
        $sudahSelesai = PercobaanKuis::where('kuis_id', $kuis->id)
            ->where('siswa_id', $siswa->id)
            ->where('status', 'selesai')
            ->exists();

        if ($sudahSelesai) {
            return redirect()->route('siswa.kuis.hasil', $kodeKuis)
                ->with('info', 'Kamu sudah mengerjakan kuis ini.');
        }

        // lanjutkan percobaan yang belum selesai
        $percobaan = PercobaanKuis::where('kuis_id', $kuis->id)
            ->where('siswa_id', $siswa->id)
            ->where('status', 'berlangsung')
            ->first();

        if (! $percobaan) {
            // Terapkan Fisher-Yates Shuffle untuk mengacak soal dan opsi jawaban.
            // Setiap siswa mendapat permutasi unik sehingga potensi kecurangan diminimalisir.
            $pengacakan = $kuis->buatPengacakan();

            $percobaan = PercobaanKuis::create([
                'kuis_id'      => $kuis->id,
                'siswa_id'     => $siswa->id,
                'urutan_acak'  => $pengacakan['soal_ids'],
                'opsi_acak'    => $pengacakan['opsi_acak'],
                'total_soal'   => count($pengacakan['soal_ids']),
                'status'       => 'berlangsung',
                'dimulai_pada' => now(),
            ]);
        }

        return redirect()->route('siswa.kuis.kerjakan', [$kodeKuis, 1]);
    }

    public function kerjakan(string $kodeKuis, int $nomor): View
    {
        $siswa     = Siswa::findOrFail(session('siswa_id'));
        $kuis      = Kuis::where('kode_kuis', $kodeKuis)->firstOrFail();
        $percobaan = PercobaanKuis::where('kuis_id', $kuis->id)
            ->where('siswa_id', $siswa->id)
            ->where('status', 'berlangsung')
            ->firstOrFail();

        $soalIds   = $percobaan->urutan_acak;
        $total     = count($soalIds);
        $nomor     = max(1, min($nomor, $total));
        $soalId    = $soalIds[$nomor - 1];

        $soal      = \App\Models\Soal::findOrFail($soalId);
        $opsiUrutan = $percobaan->opsi_acak[$soalId] ?? ['a', 'b', 'c', 'd'];

        $jawabanSudah = JawabanPercobaan::where('percobaan_id', $percobaan->id)
            ->where('soal_id', $soalId)
            ->first();

        return view('siswa.kuis.kerjakan', compact(
            'kuis', 'percobaan', 'soal', 'opsiUrutan',
            'nomor', 'total', 'jawabanSudah'
        ));
    }

    public function jawab(Request $request, string $kodeKuis, int $nomor): JsonResponse
    {
        $request->validate([
            'jawaban' => ['required', 'in:a,b,c,d'],
        ]);

        $siswa     = Siswa::findOrFail(session('siswa_id'));
        $kuis      = Kuis::where('kode_kuis', $kodeKuis)->firstOrFail();
        $percobaan = PercobaanKuis::where('kuis_id', $kuis->id)
            ->where('siswa_id', $siswa->id)
            ->where('status', 'berlangsung')
            ->firstOrFail();

        $soalIds  = $percobaan->urutan_acak;
        $soalId   = $soalIds[$nomor - 1];
        $soal     = \App\Models\Soal::findOrFail($soalId);

        $benar        = $request->jawaban === $soal->jawaban_benar;
        $waktuMenjawab = $request->waktu_menjawab ?? 0;
        $poin         = $benar ? max(10, 30 - $waktuMenjawab) : 0;

        JawabanPercobaan::updateOrCreate(
            ['percobaan_id' => $percobaan->id, 'soal_id' => $soalId],
            [
                'jawaban_dipilih' => $request->jawaban,
                'benar'           => $benar,
                'waktu_menjawab'  => $waktuMenjawab,
                'poin_diperoleh'  => $poin,
                'dijawab_pada'    => now(),
            ]
        );

        return response()->json([
            'benar'           => $benar,
            'jawaban_benar'   => $soal->jawaban_benar,
            'penjelasan'      => $kuis->tampilkan_penjelasan ? $soal->penjelasan : null,
            'poin'            => $poin,
        ]);
    }

    public function selesai(string $kodeKuis): RedirectResponse
    {
        $siswa     = Siswa::findOrFail(session('siswa_id'));
        $kuis      = Kuis::where('kode_kuis', $kodeKuis)->firstOrFail();
        $percobaan = PercobaanKuis::where('kuis_id', $kuis->id)
            ->where('siswa_id', $siswa->id)
            ->where('status', 'berlangsung')
            ->firstOrFail();

        $jawaban      = JawabanPercobaan::where('percobaan_id', $percobaan->id)->get();
        $jumlahBenar  = $jawaban->where('benar', true)->count();
        $jumlahSalah  = $jawaban->where('benar', false)->count();
        $totalSoal    = count($percobaan->urutan_acak);
        $nilai        = $totalSoal > 0 ? round(($jumlahBenar / $totalSoal) * 100, 2) : 0;
        $totalPoin    = $jawaban->sum('poin_diperoleh');
        $waktu        = (int) abs(now()->diffInSeconds($percobaan->dimulai_pada));

        DB::transaction(function () use ($percobaan, $siswa, $jumlahBenar, $jumlahSalah, $totalSoal, $nilai, $totalPoin, $waktu) {
            $percobaan->update([
                'jumlah_benar'     => $jumlahBenar,
                'jumlah_salah'     => $jumlahSalah,
                'total_soal'       => $totalSoal,
                'nilai'            => $nilai,
                'poin_diperoleh'   => $totalPoin,
                'waktu_pengerjaan' => $waktu,
                'status'           => 'selesai',
                'diselesaikan_pada' => now(),
            ]);

            $siswa->increment('total_poin', $totalPoin);

            LogPoin::create([
                'siswa_id'   => $siswa->id,
                'poin'       => $totalPoin,
                'sumber'     => 'kuis',
                'sumber_id'  => $percobaan->kuis_id,
                'keterangan' => 'Kuis: ' . $percobaan->kuis->judul,
            ]);
        });

        return redirect()->route('siswa.kuis.hasil', $kodeKuis);
    }

    public function hasil(string $kodeKuis): View
    {
        $siswa     = Siswa::findOrFail(session('siswa_id'));
        $kuis      = Kuis::where('kode_kuis', $kodeKuis)->with('mataPelajaran')->firstOrFail();
        $percobaan = PercobaanKuis::where('kuis_id', $kuis->id)
            ->where('siswa_id', $siswa->id)
            ->where('status', 'selesai')
            ->with('jawabanPercobaan.soal')
            ->latest('diselesaikan_pada')
            ->firstOrFail();

        return view('siswa.kuis.hasil', compact('kuis', 'percobaan', 'siswa'));
    }
}
