<?php

namespace App\Filament\App\Resources\OnCallSchedules\Pages;

use App\Filament\App\Resources\OnCallSchedules\OnCallScheduleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOnCallSchedule extends EditRecord
{
    protected static string $resource = OnCallScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
