<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MateriResource\Pages;
use App\Models\Materi;
use App\Models\MataPelajaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MateriResource extends Resource
{
    protected static ?string $model = Materi::class;

    protected static ?string $navigationIcon  = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Konten Pembelajaran';
    protected static ?string $navigationLabel = 'Materi';
    protected static ?string $modelLabel      = 'Materi';
    protected static ?int    $navigationSort  = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Materi')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('mata_pelajaran_id')
                        ->label('Mata Pelajaran')
                        ->options(MataPelajaran::orderBy('urutan')->pluck('nama', 'id'))
                        ->required()
                        ->searchable(),

                    Forms\Components\TextInput::make('bab')
                        ->label('Bab')
                        ->placeholder('Contoh: Bab 1')
                        ->maxLength(50),

                    Forms\Components\TextInput::make('judul')
                        ->label('Judul Materi')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('deskripsi')
                        ->label('Deskripsi Singkat')
                        ->rows(3)
                        ->maxLength(500)
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Konten')
                ->schema([
                    Forms\Components\RichEditor::make('konten')
                        ->label('Isi Materi')
                        ->toolbarButtons([
                            'bold', 'italic', 'underline',
                            'h2', 'h3',
                            'bulletList', 'orderedList',
                            'link', 'blockquote',
                            'undo', 'redo',
                        ])
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Media')
                ->columns(2)
                ->schema([
                    Forms\Components\FileUpload::make('gambar_sampul')
                        ->label('Gambar Sampul')
                        ->image()
                        ->directory('materi/sampul')
                        ->imagePreviewHeight('160')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),

                    Forms\Components\FileUpload::make('file_pdf')
                        ->label('File PDF')
                        ->acceptedFileTypes(['application/pdf'])
                        ->directory('materi/pdf')
                        ->maxSize(10240)
                        ->helperText('Maksimal 10 MB'),
                ]),

            Forms\Components\Section::make('Pengaturan')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('urutan')
                        ->label('Urutan')
                        ->numeric()
                        ->default(0),

                    Forms\Components\Toggle::make('dipublikasi')
                        ->label('Publikasikan')
                        ->helperText('Aktifkan agar materi terlihat oleh siswa')
                        ->default(false),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('gambar_sampul')
                    ->label('Sampul')
                    ->circular()
                    ->defaultImageUrl(fn () => null),

                Tables\Columns\TextColumn::make('mataPelajaran.nama')
                    ->label('Mata Pelajaran')
                    ->badge()
                    ->color(fn ($record) => $record->mataPelajaran?->jenis === 'IPA' ? 'success' : 'info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('bab')
                    ->label('Bab')
                    ->sortable(),

                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\IconColumn::make('file_pdf')
                    ->label('PDF')
                    ->boolean()
                    ->trueIcon('heroicon-o-document-text')
                    ->falseIcon('heroicon-o-minus'),

                Tables\Columns\ToggleColumn::make('dipublikasi')
                    ->label('Publik'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('urutan')
            ->filters([
                Tables\Filters\SelectFilter::make('mata_pelajaran_id')
                    ->label('Mata Pelajaran')
                    ->options(MataPelajaran::orderBy('urutan')->pluck('nama', 'id')),

                Tables\Filters\TernaryFilter::make('dipublikasi')
                    ->label('Status Publikasi'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMateris::route('/'),
            'create' => Pages\CreateMateri::route('/create'),
            'edit'   => Pages\EditMateri::route('/{record}/edit'),
        ];
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
