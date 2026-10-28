<?php

namespace App\Filament\App\Resources\Incidents\Pages;

use App\Filament\App\Resources\Incidents\IncidentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateIncident extends CreateRecord
{
    protected static string $resource = IncidentResource::class;
}
