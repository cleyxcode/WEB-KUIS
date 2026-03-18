<?php

namespace App\Filament\Resources\LencanaResource\Pages;

use App\Filament\Resources\LencanaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLencanas extends ListRecords
{
    protected static string $resource = LencanaResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
