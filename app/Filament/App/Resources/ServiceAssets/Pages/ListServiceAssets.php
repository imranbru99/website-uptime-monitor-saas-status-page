<?php

namespace App\Filament\App\Resources\ServiceAssets\Pages;

use App\Filament\App\Resources\ServiceAssets\ServiceAssetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListServiceAssets extends ListRecords
{
    protected static string $resource = ServiceAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
