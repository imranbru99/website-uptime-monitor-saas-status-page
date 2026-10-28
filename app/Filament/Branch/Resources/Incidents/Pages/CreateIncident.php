<?php

namespace App\Filament\Branch\Resources\Incidents\Pages;

use App\Filament\Branch\Resources\Incidents\IncidentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateIncident extends CreateRecord
{
    protected static string $resource = IncidentResource::class;
}
