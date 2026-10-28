<?php

namespace App\Filament\App\Resources\SlaPolicies;

use App\Domain\Analytics\Models\SlaPolicy;
use App\Filament\App\Resources\SlaPolicies\Pages\CreateSlaPolicy;
use App\Filament\App\Resources\SlaPolicies\Pages\EditSlaPolicy;
use App\Filament\App\Resources\SlaPolicies\Pages\ListSlaPolicies;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SlaPolicyResource extends Resource
{
    protected static ?string $model = SlaPolicy::class;

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
                TextInput::make('target_uptime_percentage')
                    ->required()
                    ->numeric()
                    ->default(99.9),
                TextInput::make('measurement_window')
                    ->required()
                    ->default('monthly'),
                Toggle::make('exclude_maintenance')
                    ->required(),
                TextInput::make('error_budget_alert_threshold')
                    ->required()
                    ->numeric()
                    ->default(80),
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
                TextColumn::make('target_uptime_percentage')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('measurement_window')
                    ->searchable(),
                IconColumn::make('exclude_maintenance')
                    ->boolean(),
                TextColumn::make('error_budget_alert_threshold')
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
            'index' => ListSlaPolicies::route('/'),
            'create' => CreateSlaPolicy::route('/create'),
            'edit' => EditSlaPolicy::route('/{record}/edit'),
        ];
    }
}
