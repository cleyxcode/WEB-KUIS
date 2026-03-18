<?php

namespace App\Filament\Resources\LencanaResource\Pages;

use App\Filament\Resources\LencanaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLencana extends EditRecord
{
    protected static string $resource = LencanaResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
