<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaResource\Pages;
use App\Filament\Resources\SiswaResource\RelationManagers\PercobaanKuisRelationManager;
use App\Models\Siswa;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static ?string $navigationIcon  = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Data Siswa';
    protected static ?string $navigationLabel = 'Siswa';
    protected static ?int    $navigationSort  = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nama_lengkap')
                ->label('Nama Lengkap')
                ->required(),

            TextInput::make('nama_panggilan')
                ->label('Nama Panggilan')
                ->required()
                ->helperText('Digunakan untuk login'),

            TextInput::make('nis')
                ->label('NIS')
                ->required()
                ->unique(ignoreRecord: true),

            TextInput::make('kelas')
                ->label('Kelas')
                ->required()
                ->default('V'),

            Select::make('jenis_kelamin')
                ->label('Jenis Kelamin')
                ->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])
                ->required(),

            TextInput::make('kode_siswa')
                ->label('Kode Siswa')
                ->required()
                ->unique(ignoreRecord: true)
                ->suffixAction(
                    \Filament\Forms\Components\Actions\Action::make('generate_kode')
                        ->label('Generate')
                        ->icon('heroicon-o-arrow-path')
                        ->action(function ($set) {
                            do {
                                $kode = strtoupper(Str::random(6));
                            } while (Siswa::where('kode_siswa', $kode)->exists());
                            $set('kode_siswa', $kode);
                        })
                ),

            Toggle::make('aktif')
                ->label('Aktif')
                ->default(true),

            TextInput::make('total_poin')
                ->label('Total Poin')
                ->numeric()
                ->disabled()
                ->dehydrated(false),

            TextInput::make('streak_sekarang')
                ->label('Streak Sekarang')
                ->numeric()
                ->disabled()
                ->dehydrated(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_panggilan')
                    ->label('Panggilan')
                    ->searchable(),

                TextColumn::make('kelas')
                    ->label('Kelas'),

                TextColumn::make('jenis_kelamin')
                    ->label('JK')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'L' => 'info',
                        'P' => 'pink',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state === 'L' ? 'Laki-laki' : 'Perempuan'),

                TextColumn::make('kode_siswa')
                    ->label('Kode')
                    ->badge()
                    ->color('gray')
                    ->copyable(),

                TextColumn::make('total_poin')
                    ->label('Poin')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('streak_sekarang')
                    ->label('Streak')
                    ->suffix(' hari'),

                IconColumn::make('aktif')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('aktif')->label('Aktif'),

                SelectFilter::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options(['L' => 'Laki-laki', 'P' => 'Perempuan']),
            ])
            ->actions([
                Action::make('reset_kode')
                    ->label('Reset Kode')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function (Siswa $record) {
                        do {
                            $kode = strtoupper(Str::random(6));
                        } while (Siswa::where('kode_siswa', $kode)->exists());

                        $record->update(['kode_siswa' => $kode]);

                        Notification::make()
                            ->title('Kode berhasil direset: ' . $kode)
                            ->success()
                            ->send();
                    }),

                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getRelationManagers(): array
    {
        return [
            PercobaanKuisRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'edit'   => Pages\EditSiswa::route('/{record}/edit'),
            'view'   => Pages\ViewSiswa::route('/{record}'),
        ];
    }
}
