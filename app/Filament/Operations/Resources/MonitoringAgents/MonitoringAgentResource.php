<?php

namespace App\Filament\Operations\Resources\MonitoringAgents;

use App\Domain\Monitoring\Models\MonitoringAgent;
use App\Filament\Operations\Resources\MonitoringAgents\Pages\CreateMonitoringAgent;
use App\Filament\Operations\Resources\MonitoringAgents\Pages\EditMonitoringAgent;
use App\Filament\Operations\Resources\MonitoringAgents\Pages\ListMonitoringAgents;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MonitoringAgentResource extends Resource
{
    protected static ?string $model = MonitoringAgent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->relationship('tenant', 'name'),
                Select::make('branch_id')
                    ->relationship('branch', 'name'),
                Select::make('location_id')
                    ->relationship('location', 'name'),
                TextInput::make('name')
                    ->required(),
                TextInput::make('token_hash')
                    ->required(),
                TextInput::make('type')
                    ->required()
                    ->default('public_probe'),
                TextInput::make('version')
                    ->required()
                    ->default('1.0.0'),
                TextInput::make('os_info'),
                TextInput::make('status')
                    ->required()
                    ->default('online'),
                DateTimePicker::make('last_seen_at'),
                Textarea::make('metrics')
                    ->columnSpanFull(),
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
                TextColumn::make('location.name')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('token_hash')
                    ->searchable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('version')
                    ->searchable(),
                TextColumn::make('os_info')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('last_seen_at')
                    ->dateTime()
                    ->sortable(),
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
            'index' => ListMonitoringAgents::route('/'),
            'create' => CreateMonitoringAgent::route('/create'),
            'edit' => EditMonitoringAgent::route('/{record}/edit'),
        ];
    }
}
