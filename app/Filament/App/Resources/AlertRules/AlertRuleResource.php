<?php

namespace App\Filament\App\Resources\AlertRules;

use App\Domain\Alerts\Models\AlertRule;
use App\Filament\App\Resources\AlertRules\Pages\CreateAlertRule;
use App\Filament\App\Resources\AlertRules\Pages\EditAlertRule;
use App\Filament\App\Resources\AlertRules\Pages\ListAlertRules;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AlertRuleResource extends Resource
{
    protected static ?string $model = AlertRule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->relationship('tenant', 'name')
                    ->required(),
                Select::make('branch_id')
                    ->relationship('branch', 'name'),
                Select::make('monitor_id')
                    ->relationship('monitor', 'name'),
                TextInput::make('name')
                    ->required(),
                TextInput::make('event_type')
                    ->required(),
                TextInput::make('failure_threshold')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('latency_threshold_ms')
                    ->numeric(),
                TextInput::make('ssl_expiry_days_threshold')
                    ->numeric(),
                Textarea::make('quiet_hours')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tenant.name')
                    ->searchable(),
                TextColumn::make('branch.name')
                    ->searchable(),
                TextColumn::make('monitor.name')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('event_type')
                    ->searchable(),
                TextColumn::make('failure_threshold')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('latency_threshold_ms')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ssl_expiry_days_threshold')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAlertRules::route('/'),
            'create' => CreateAlertRule::route('/create'),
            'edit' => EditAlertRule::route('/{record}/edit'),
        ];
    }
}
