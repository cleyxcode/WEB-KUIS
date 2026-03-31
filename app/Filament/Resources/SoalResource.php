<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SoalResource\Pages;
use App\Models\MataPelajaran;
use App\Models\Soal;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SoalResource extends Resource
{
    protected static ?string $model = Soal::class;

    protected static ?string $navigationIcon  = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationGroup = 'Bank Soal & Kuis';
    protected static ?string $navigationLabel = 'Bank Soal';
    protected static ?int    $navigationSort  = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('mata_pelajaran_id')
                ->label('Mata Pelajaran')
                ->options(MataPelajaran::orderBy('urutan')->pluck('nama', 'id'))
                ->required(),

            Select::make('tingkat_kesulitan')
                ->label('Tingkat Kesulitan')
                ->options([
                    'mudah'  => 'Mudah',
                    'sedang' => 'Sedang',
                    'sulit'  => 'Sulit',
                ])
                ->default('sedang'),

            Textarea::make('teks_soal')
                ->label('Teks Soal')
                ->required()
                ->rows(3)
                ->columnSpanFull(),

            FileUpload::make('gambar_soal')
                ->label('Gambar Soal')
                ->image()
                ->directory('soal/soal')
                ->columnSpanFull(),

            Fieldset::make('Opsi A')->schema([
                Textarea::make('opsi_a')->label('Teks Opsi A')->required()->rows(2),
                FileUpload::make('gambar_opsi_a')->label('Gambar')->image()->directory('soal/opsi'),
            ]),

            Fieldset::make('Opsi B')->schema([
                Textarea::make('opsi_b')->label('Teks Opsi B')->required()->rows(2),
                FileUpload::make('gambar_opsi_b')->label('Gambar')->image()->directory('soal/opsi'),
            ]),

            Fieldset::make('Opsi C')->schema([
                Textarea::make('opsi_c')->label('Teks Opsi C')->required()->rows(2),
                FileUpload::make('gambar_opsi_c')->label('Gambar')->image()->directory('soal/opsi'),
            ]),

            Fieldset::make('Opsi D')->schema([
                Textarea::make('opsi_d')->label('Teks Opsi D')->required()->rows(2),
                FileUpload::make('gambar_opsi_d')->label('Gambar')->image()->directory('soal/opsi'),
            ]),

            Radio::make('jawaban_benar')
                ->label('Jawaban Benar')
                ->options(['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D'])
                ->inline()
                ->required()
                ->columnSpanFull(),

            Textarea::make('penjelasan')
                ->label('Penjelasan')
                ->rows(3)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),

                TextColumn::make('mataPelajaran.jenis')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'IPA' => 'success',
                        'IPS' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('teks_soal')
                    ->label('Soal')
                    ->limit(80)
                    ->searchable(),

                TextColumn::make('jawaban_benar')
                    ->label('Kunci')
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(fn ($state) => strtoupper($state)),

                TextColumn::make('tingkat_kesulitan')
                    ->label('Kesulitan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'mudah'  => 'success',
                        'sedang' => 'warning',
                        'sulit'  => 'danger',
                        default  => 'gray',
                    }),

                IconColumn::make('gambar_soal')
                    ->label('Gambar')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('mata_pelajaran_id')
                    ->label('Mata Pelajaran')
                    ->options(MataPelajaran::pluck('nama', 'id')),

                SelectFilter::make('tingkat_kesulitan')
                    ->label('Kesulitan')
                    ->options(['mudah' => 'Mudah', 'sedang' => 'Sedang', 'sulit' => 'Sulit']),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSoals::route('/'),
            'create' => Pages\CreateSoal::route('/create'),
            'edit'   => Pages\EditSoal::route('/{record}/edit'),
            'view'   => Pages\ViewSoal::route('/{record}'),
        ];
    }


}
