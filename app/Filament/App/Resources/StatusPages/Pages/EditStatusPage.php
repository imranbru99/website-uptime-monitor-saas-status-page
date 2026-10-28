<?php

namespace App\Filament\App\Resources\StatusPages\Pages;

use App\Filament\App\Resources\StatusPages\StatusPageResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditStatusPage extends EditRecord
{
    protected static string $resource = StatusPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
