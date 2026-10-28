<?php

namespace App\Filament\Platform\Resources\MonitorLocations\Pages;

use App\Filament\Platform\Resources\MonitorLocations\MonitorLocationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMonitorLocation extends EditRecord
{
    protected static string $resource = MonitorLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
