<?php

namespace App\Filament\Operations\Resources\MonitoringAgents\Pages;

use App\Filament\Operations\Resources\MonitoringAgents\MonitoringAgentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMonitoringAgent extends EditRecord
{
    protected static string $resource = MonitoringAgentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
