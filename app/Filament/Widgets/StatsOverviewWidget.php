<?php

namespace App\Filament\Widgets;

use App\Models\Kuis;
use App\Models\PercobaanKuis;
use App\Models\Siswa;
use App\Models\Soal;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $rataRata = PercobaanKuis::where('status', 'selesai')->avg('nilai');

        return [
            Stat::make('Siswa Aktif', Siswa::where('aktif', true)->count())
                ->icon('heroicon-o-user-group')
                ->color('success'),

            Stat::make('Bank Soal', Soal::count())
                ->icon('heroicon-o-question-mark-circle')
                ->color('warning'),

            Stat::make('Kuis Aktif', Kuis::where('aktif', true)->count())
                ->icon('heroicon-o-clipboard-document')
                ->color('primary'),

            Stat::make('Total Percobaan', PercobaanKuis::where('status', 'selesai')->count())
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Rata-rata Nilai', number_format($rataRata ?? 0, 1))
                ->icon('heroicon-o-chart-bar')
                ->color('info'),
        ];
    }
}
