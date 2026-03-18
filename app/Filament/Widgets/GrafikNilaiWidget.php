<?php

namespace App\Filament\Widgets;

use App\Models\PercobaanKuis;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class GrafikNilaiWidget extends ChartWidget
{
    protected static ?string $heading = 'Aktivitas Kuis (7 Hari Terakhir)';

    protected function getData(): array
    {
        $data   = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date     = Carbon::today()->subDays($i);
            $labels[] = $date->format('d M');
            $data[]   = PercobaanKuis::where('status', 'selesai')
                ->whereDate('diselesaikan_pada', $date)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Percobaan Selesai',
                    'data'            => $data,
                    'borderColor'     => '#0F6E56',
                    'backgroundColor' => 'rgba(15, 110, 86, 0.1)',
                    'fill'            => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
