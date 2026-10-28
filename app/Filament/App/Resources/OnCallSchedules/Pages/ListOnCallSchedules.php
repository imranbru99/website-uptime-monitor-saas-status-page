<?php

namespace App\Filament\App\Resources\OnCallSchedules\Pages;

use App\Filament\App\Resources\OnCallSchedules\OnCallScheduleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOnCallSchedules extends ListRecords
{
    protected static string $resource = OnCallScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
