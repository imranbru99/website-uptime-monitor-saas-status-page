<?php

namespace App\Filament\Operations\Resources\MonitoringAgents\Pages;

use App\Filament\Operations\Resources\MonitoringAgents\MonitoringAgentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMonitoringAgents extends ListRecords
{
    protected static string $resource = MonitoringAgentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
