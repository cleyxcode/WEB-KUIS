<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LencanaResource\Pages;
use App\Models\Lencana;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LencanaResource extends Resource
{
    protected static ?string $model = Lencana::class;

    protected static ?string $navigationIcon  = 'heroicon-o-trophy';
    protected static ?string $navigationGroup = 'Data Siswa';
    protected static ?string $navigationLabel = 'Lencana';
    protected static ?int    $navigationSort  = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('kunci_lencana')
                ->label('Kunci Lencana')
                ->required()
                ->unique(ignoreRecord: true),

            TextInput::make('nama')
                ->label('Nama')
                ->required(),

            Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->rows(2),

            TextInput::make('ikon')
                ->label('Ikon (emoji)')
                ->default('🏅'),

            Select::make('warna')
                ->label('Warna')
                ->options([
                    'green'  => 'Hijau',
                    'blue'   => 'Biru',
                    'purple' => 'Ungu',
                    'amber'  => 'Amber',
                    'teal'   => 'Teal',
                    'coral'  => 'Coral',
                ])
                ->default('teal'),

            Select::make('jenis_kondisi')
                ->label('Jenis Kondisi')
                ->options([
                    'login'    => 'Login',
                    'baca'     => 'Baca Materi',
                    'kuis'     => 'Ikut Kuis',
                    'nilai'    => 'Nilai',
                    'streak'   => 'Streak',
                    'poin'     => 'Poin',
                    'peringkat' => 'Peringkat',
                ])
                ->required(),

            TextInput::make('nilai_kondisi')
                ->label('Nilai Kondisi')
                ->numeric()
                ->helperText('Angka syarat terpenuhi')
                ->default(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ikon')->label('Ikon'),

                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable(),

                TextColumn::make('jenis_kondisi')
                    ->label('Kondisi')
                    ->badge(),

                TextColumn::make('nilai_kondisi')
                    ->label('Syarat'),

                TextColumn::make('siswa_count')
                    ->label('Diraih')
                    ->counts('siswa'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLencanas::route('/'),
            'create' => Pages\CreateLencana::route('/create'),
            'edit'   => Pages\EditLencana::route('/{record}/edit'),
        ];
    }
}
