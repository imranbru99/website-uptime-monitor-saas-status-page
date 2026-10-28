<?php

namespace App\Filament\Operations\Resources\MonitoringAgents\Pages;

use App\Filament\Operations\Resources\MonitoringAgents\MonitoringAgentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMonitoringAgent extends CreateRecord
{
    protected static string $resource = MonitoringAgentResource::class;
}
