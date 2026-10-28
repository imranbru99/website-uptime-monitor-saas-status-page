<?php

namespace App\Filament\Operations\Resources\Incidents\Pages;

use App\Filament\Operations\Resources\Incidents\IncidentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateIncident extends CreateRecord
{
    protected static string $resource = IncidentResource::class;
}
