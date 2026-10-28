<?php

namespace App\Filament\Operations\Resources\Monitors\Pages;

use App\Filament\Operations\Resources\Monitors\MonitorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMonitors extends ListRecords
{
    protected static string $resource = MonitorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
