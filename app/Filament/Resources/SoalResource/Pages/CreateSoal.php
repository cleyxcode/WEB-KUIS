<?php

namespace App\Filament\Resources\SoalResource\Pages;

use App\Filament\Resources\SoalResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSoal extends CreateRecord
{
    protected static string $resource = SoalResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
