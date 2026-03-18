<?php

namespace App\Filament\Resources\KuisResource\Pages;

use App\Filament\Resources\KuisResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKuis extends ViewRecord
{
    protected static string $resource = KuisResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
