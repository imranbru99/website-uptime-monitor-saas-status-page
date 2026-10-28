<?php

namespace App\Filament\Client\Resources\Incidents\Pages;

use App\Filament\Client\Resources\Incidents\IncidentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateIncident extends CreateRecord
{
    protected static string $resource = IncidentResource::class;
}
