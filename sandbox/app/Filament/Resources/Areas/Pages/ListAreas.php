<?php

namespace App\Filament\Resources\Areas\Pages;

use App\Filament\Resources\Areas\AreaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAreas extends ListRecords
{
    protected static string $resource = AreaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
