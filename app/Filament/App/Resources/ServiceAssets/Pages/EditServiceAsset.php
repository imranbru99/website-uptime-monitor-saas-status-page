<?php

namespace App\Filament\App\Resources\ServiceAssets\Pages;

use App\Filament\App\Resources\ServiceAssets\ServiceAssetResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditServiceAsset extends EditRecord
{
    protected static string $resource = ServiceAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
