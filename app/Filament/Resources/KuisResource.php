<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KuisResource\Pages;
use App\Models\Kuis;
use App\Models\MataPelajaran;
use App\Models\Soal;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class KuisResource extends Resource
{
    protected static ?string $model = Kuis::class;

    protected static ?string $navigationIcon  = 'heroicon-o-clipboard-document';
    protected static ?string $navigationGroup = 'Bank Soal & Kuis';
    protected static ?string $navigationLabel = 'Kuis';
    protected static ?int    $navigationSort  = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('mata_pelajaran_id')
                ->label('Mata Pelajaran')
                ->options(MataPelajaran::orderBy('urutan')->pluck('nama', 'id'))
                ->required()
                ->live(),

            TextInput::make('judul')
                ->label('Judul')
                ->required(),

            Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->rows(2)
                ->columnSpanFull(),

            CheckboxList::make('soal')
                ->label('Pilih Soal')
                ->relationship('soal', 'teks_soal')
                ->options(function (Get $get) {
                    $mapelId = $get('mata_pelajaran_id');
                    if (! $mapelId) {
                        return [];
                    }

                    return Soal::where('mata_pelajaran_id', $mapelId)
                        ->get()
                        ->mapWithKeys(fn ($soal) => [
                            $soal->id => '[' . strtoupper($soal->jawaban_benar) . '] ' . \Str::limit($soal->teks_soal, 80),
                        ]);
                })
                ->searchable()
                ->bulkToggleable()
                ->columnSpanFull(),

            TextInput::make('jumlah_soal')
                ->label('Jumlah Soal')
                ->numeric()
                ->default(10),

            TextInput::make('waktu_per_soal')
                ->label('Waktu Per Soal (detik)')
                ->numeric()
                ->default(30)
                ->minValue(5),

            Toggle::make('acak_soal')
                ->label('Acak Soal')
                ->default(true)
                ->helperText('Fisher-Yates Shuffle'),

            Toggle::make('acak_opsi')
                ->label('Acak Opsi')
                ->default(true),

            Toggle::make('tampilkan_penjelasan')
                ->label('Tampilkan Penjelasan')
                ->default(true),

            DateTimePicker::make('mulai_pada')
                ->label('Mulai Pada')
                ->nullable(),

            DateTimePicker::make('berakhir_pada')
                ->label('Berakhir Pada')
                ->nullable()
                ->after('mulai_pada'),

            Toggle::make('aktif')
                ->label('Aktif')
                ->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_kuis')
                    ->label('Kode')
                    ->badge()
                    ->color('primary')
                    ->copyable(),

                TextColumn::make('judul')
                    ->label('Judul')
                    ->limit(40)
                    ->searchable(),

                TextColumn::make('mataPelajaran.jenis')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'IPA' => 'success',
                        'IPS' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('soal_count')
                    ->label('Soal')
                    ->counts('soal')
                    ->suffix(' soal'),

                TextColumn::make('waktu_per_soal')
                    ->label('Waktu')
                    ->suffix(' dtk'),

                TextColumn::make('percobaan_kuis_count')
                    ->label('Peserta')
                    ->counts('percobaanKuis'),

                ToggleColumn::make('aktif')
                    ->label('Aktif'),

                TextColumn::make('mulai_pada')
                    ->label('Mulai')
                    ->dateTime('d M Y H:i')
                    ->placeholder('Tidak dibatasi'),
            ])
            ->filters([
                SelectFilter::make('mata_pelajaran_id')
                    ->label('Mata Pelajaran')
                    ->options(MataPelajaran::pluck('nama', 'id')),

                TernaryFilter::make('aktif')->label('Aktif'),
            ])
            ->actions([
                Action::make('toggle_aktif')
                    ->label(fn (Kuis $record) => $record->aktif ? 'Nonaktifkan' : 'Aktifkan')
                    ->icon(fn (Kuis $record) => $record->aktif ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn (Kuis $record) => $record->aktif ? 'danger' : 'success')
                    ->action(fn (Kuis $record) => $record->update(['aktif' => ! $record->aktif])),

                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListKuis::route('/'),
            'create' => Pages\CreateKuis::route('/create'),
            'edit'   => Pages\EditKuis::route('/{record}/edit'),
            'view'   => Pages\ViewKuis::route('/{record}'),
        ];
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
