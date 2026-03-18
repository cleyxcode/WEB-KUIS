<?php

namespace App\Filament\Pages;

use App\Models\Kuis;
use App\Models\PercobaanKuis;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LaporanNilai extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon  = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Laporan Nilai';
    protected static string  $view            = 'filament.pages.laporan-nilai';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PercobaanKuis::query()
                    ->where('status', 'selesai')
                    ->with(['siswa', 'kuis.mataPelajaran'])
            )
            ->defaultSort('diselesaikan_pada', 'desc')
            ->columns([
                TextColumn::make('siswa.nama_lengkap')
                    ->label('Nama Siswa')
                    ->searchable(),

                TextColumn::make('siswa.nis')
                    ->label('NIS')
                    ->searchable(),

                TextColumn::make('kuis.judul')
                    ->label('Kuis')
                    ->limit(40),

                TextColumn::make('kuis.mataPelajaran.jenis')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'IPA' => 'success',
                        'IPS' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('nilai')
                    ->label('Nilai')
                    ->badge()
                    ->color(fn ($state): string => match (true) {
                        $state >= 80 => 'success',
                        $state >= 60 => 'warning',
                        default      => 'danger',
                    })
                    ->sortable(),

                TextColumn::make('jumlah_benar')->label('Benar'),
                TextColumn::make('total_soal')->label('Total Soal'),

                TextColumn::make('poin_diperoleh')
                    ->label('Poin')
                    ->sortable(),

                TextColumn::make('waktu_pengerjaan')
                    ->label('Waktu')
                    ->formatStateUsing(fn ($state) => gmdate('i:s', $state)),

                TextColumn::make('diselesaikan_pada')
                    ->label('Selesai')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('kuis_id')
                    ->label('Kuis')
                    ->options(Kuis::pluck('judul', 'id')),
            ]);
    }
}
