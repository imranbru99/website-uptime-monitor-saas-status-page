<?php

namespace App\Filament\App\Resources\AlertRules\Pages;

use App\Filament\App\Resources\AlertRules\AlertRuleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAlertRule extends EditRecord
{
    protected static string $resource = AlertRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
