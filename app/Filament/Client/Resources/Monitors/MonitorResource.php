<?php

namespace App\Filament\Client\Resources\Monitors;

use App\Domain\Monitoring\Models\Monitor;
use App\Filament\Client\Resources\Monitors\Pages\CreateMonitor;
use App\Filament\Client\Resources\Monitors\Pages\EditMonitor;
use App\Filament\Client\Resources\Monitors\Pages\ListMonitors;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MonitorResource extends Resource
{
    protected static ?string $model = Monitor::class;

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
                Select::make('team_id')
                    ->relationship('team', 'name'),
                Select::make('private_agent_id')
                    ->relationship('privateAgent', 'name'),
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('monitor_type')
                    ->required(),
                TextInput::make('target')
                    ->required(),
                TextInput::make('check_interval_seconds')
                    ->required()
                    ->numeric()
                    ->default(60),
                TextInput::make('timeout_seconds')
                    ->required()
                    ->numeric()
                    ->default(15),
                TextInput::make('retry_count')
                    ->required()
                    ->numeric()
                    ->default(2),
                TextInput::make('confirmation_threshold')
                    ->required()
                    ->numeric()
                    ->default(2),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                TextInput::make('consecutive_failures')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('consecutive_successes')
                    ->required()
                    ->numeric()
                    ->default(0),
                DateTimePicker::make('last_checked_at'),
                DateTimePicker::make('next_check_due_at'),
                DateTimePicker::make('last_status_change_at'),
                TextInput::make('uptime_percentage_24h')
                    ->required()
                    ->numeric()
                    ->default(100),
                TextInput::make('uptime_percentage_7d')
                    ->required()
                    ->numeric()
                    ->default(100),
                TextInput::make('uptime_percentage_30d')
                    ->required()
                    ->numeric()
                    ->default(100),
                TextInput::make('avg_latency_ms')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('tags')
                    ->columnSpanFull(),
                Textarea::make('selected_locations')
                    ->columnSpanFull(),
                Textarea::make('config')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
                Toggle::make('is_paused')
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
                TextColumn::make('team.name')
                    ->searchable(),
                TextColumn::make('privateAgent.name')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('monitor_type')
                    ->searchable(),
                TextColumn::make('target')
                    ->searchable(),
                TextColumn::make('check_interval_seconds')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('timeout_seconds')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('retry_count')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('confirmation_threshold')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('consecutive_failures')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('consecutive_successes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('last_checked_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('next_check_due_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('last_status_change_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('uptime_percentage_24h')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('uptime_percentage_7d')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('uptime_percentage_30d')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('avg_latency_ms')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean(),
                IconColumn::make('is_paused')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
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
            'index' => ListMonitors::route('/'),
            'create' => CreateMonitor::route('/create'),
            'edit' => EditMonitor::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
