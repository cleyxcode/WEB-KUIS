<?php

namespace App\Filament\Resources\KuisResource\Pages;

use App\Filament\Resources\KuisResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKuis extends CreateRecord
{
    protected static string $resource = KuisResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
