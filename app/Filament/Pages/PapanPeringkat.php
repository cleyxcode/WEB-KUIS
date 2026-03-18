<?php

namespace App\Filament\Pages;

use App\Models\Siswa;
use Filament\Pages\Page;

class PapanPeringkat extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-trophy';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Papan Peringkat';
    protected static string  $view            = 'filament.pages.papan-peringkat';

    public function getSiswaProperty()
    {
        return Siswa::where('aktif', true)
            ->withCount('lencana')
            ->orderByDesc('total_poin')
            ->limit(50)
            ->get();
    }
}
