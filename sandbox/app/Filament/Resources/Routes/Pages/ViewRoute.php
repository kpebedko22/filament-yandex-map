<?php

namespace App\Filament\Resources\Routes\Pages;

use App\Filament\Resources\Routes\RouteResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRoute extends ViewRecord
{
    protected static string $resource = RouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
