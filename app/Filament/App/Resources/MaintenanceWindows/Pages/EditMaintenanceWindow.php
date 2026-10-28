<?php

namespace App\Filament\App\Resources\MaintenanceWindows\Pages;

use App\Filament\App\Resources\MaintenanceWindows\MaintenanceWindowResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMaintenanceWindow extends EditRecord
{
    protected static string $resource = MaintenanceWindowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
