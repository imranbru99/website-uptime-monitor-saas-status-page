<?php

namespace App\Filament\App\Resources\SlaPolicies\Pages;

use App\Filament\App\Resources\SlaPolicies\SlaPolicyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSlaPolicy extends EditRecord
{
    protected static string $resource = SlaPolicyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
