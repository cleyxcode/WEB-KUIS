<?php

namespace App\Filament\Pages;

use App\Models\Kuis;
use App\Models\MataPelajaran;
use App\Models\PercobaanKuis;
use App\Models\RiwayatBaca;
use App\Models\Siswa;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class RiwayatAktivitas extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Profil';
    protected static ?string $navigationLabel = 'Riwayat Aktivitas';
    protected static ?int    $navigationSort  = 1;
    protected static string  $view            = 'filament.pages.riwayat-aktivitas';

    public string $activeTab = 'kuis';

    // Filter kuis
    public string $filterStatusKuis = '';
    public string $filterMapelKuis  = '';
    public string $filterSiswaKuis  = '';

    // Filter materi
    public string $filterMapelMateri  = '';
    public string $filterSiswaMateri  = '';

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function getRiwayatKuisProperty(): Collection
    {
        $query = PercobaanKuis::query()
            ->with(['siswa', 'kuis.mataPelajaran'])
            ->orderByDesc('dimulai_pada');

        if ($this->filterStatusKuis !== '') {
            $query->where('status', $this->filterStatusKuis);
        }

        if ($this->filterSiswaKuis !== '') {
            $query->where('siswa_id', $this->filterSiswaKuis);
        }

        if ($this->filterMapelKuis !== '') {
            $query->whereHas('kuis', function ($q) {
                $q->where('mata_pelajaran_id', $this->filterMapelKuis);
            });
        }

        return $query->get();
    }

    public function getRiwayatMateriProperty(): Collection
    {
        $query = RiwayatBaca::query()
            ->with(['siswa', 'materi.mataPelajaran'])
            ->orderByDesc('dibaca_pada');

        if ($this->filterSiswaMateri !== '') {
            $query->where('siswa_id', $this->filterSiswaMateri);
        }

        if ($this->filterMapelMateri !== '') {
            $query->whereHas('materi', function ($q) {
                $q->where('mata_pelajaran_id', $this->filterMapelMateri);
            });
        }

        return $query->get();
    }

    public function getMataPelajaranOptionsProperty(): array
    {
        return MataPelajaran::orderBy('urutan')->pluck('nama', 'id')->toArray();
    }

    public function getSiswaOptionsProperty(): array
    {
        return Siswa::where('aktif', true)->orderBy('nama_lengkap')->pluck('nama_lengkap', 'id')->toArray();
    }

    public function resetFiltersKuis(): void
    {
        $this->filterStatusKuis = '';
        $this->filterMapelKuis  = '';
        $this->filterSiswaKuis  = '';
    }

    public function resetFiltersMateri(): void
    {
        $this->filterMapelMateri  = '';
        $this->filterSiswaMateri  = '';
    }
}
