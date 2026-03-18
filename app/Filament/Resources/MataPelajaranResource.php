<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MataPelajaranResource\Pages;
use App\Models\MataPelajaran;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MataPelajaranResource extends Resource
{
    protected static ?string $model = MataPelajaran::class;

    protected static ?string $navigationIcon  = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Konten Pembelajaran';
    protected static ?string $navigationLabel = 'Mata Pelajaran';
    protected static ?string $modelLabel      = 'Mata Pelajaran';
    protected static ?int    $navigationSort  = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nama')
                ->label('Nama')
                ->required(),

            Select::make('jenis')
                ->label('Jenis')
                ->options(['IPA' => 'IPA', 'IPS' => 'IPS'])
                ->required(),

            TextInput::make('ikon')
                ->label('Ikon (emoji)'),

            Select::make('warna')
                ->label('Warna')
                ->options([
                    'teal'   => 'Teal',
                    'blue'   => 'Biru',
                    'green'  => 'Hijau',
                    'amber'  => 'Amber',
                    'purple' => 'Ungu',
                ])
                ->default('teal'),

            TextInput::make('urutan')
                ->label('Urutan')
                ->numeric()
                ->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ikon')
                    ->label('Ikon'),

                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable(),

                TextColumn::make('jenis')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'IPA' => 'success',
                        'IPS' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable(),

                TextColumn::make('soal_count')
                    ->label('Soal')
                    ->counts('soal'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMataPelajarans::route('/'),
            'create' => Pages\CreateMataPelajaran::route('/create'),
            'edit'   => Pages\EditMataPelajaran::route('/{record}/edit'),
        ];
    }
}
