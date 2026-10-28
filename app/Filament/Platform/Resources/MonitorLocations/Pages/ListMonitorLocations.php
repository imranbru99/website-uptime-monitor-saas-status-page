<?php

namespace App\Filament\Platform\Resources\MonitorLocations\Pages;

use App\Filament\Platform\Resources\MonitorLocations\MonitorLocationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMonitorLocations extends ListRecords
{
    protected static string $resource = MonitorLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
