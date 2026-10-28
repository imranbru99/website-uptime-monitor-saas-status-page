<?php

namespace App\Filament\App\Resources\AlertRules\Pages;

use App\Filament\App\Resources\AlertRules\AlertRuleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAlertRules extends ListRecords
{
    protected static string $resource = AlertRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
