<?php

namespace App\Filament\Resources\SiswaResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PercobaanKuisRelationManager extends RelationManager
{
    protected static string $relationship = 'percobaanKuis';
    protected static ?string $title       = 'Riwayat Kuis';

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('diselesaikan_pada', 'desc')
            ->columns([
                TextColumn::make('kuis.judul')
                    ->label('Kuis')
                    ->limit(40),

                TextColumn::make('nilai')
                    ->label('Nilai')
                    ->badge()
                    ->suffix('/100')
                    ->color(fn ($state): string => match (true) {
                        $state >= 80 => 'success',
                        $state >= 60 => 'warning',
                        default      => 'danger',
                    }),

                TextColumn::make('jumlah_benar')
                    ->label('Benar'),

                TextColumn::make('total_soal')
                    ->label('Total Soal'),

                TextColumn::make('poin_diperoleh')
                    ->label('Poin'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'selesai'    => 'success',
                        'berlangsung' => 'warning',
                        default      => 'gray',
                    }),

                TextColumn::make('diselesaikan_pada')
                    ->label('Selesai')
                    ->dateTime('d M Y H:i'),
            ]);
    }
}
