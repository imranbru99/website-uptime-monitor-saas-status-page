<?php

namespace App\Filament\App\Resources\Incidents;

use App\Domain\Incidents\Models\Incident;
use App\Filament\App\Resources\Incidents\Pages\CreateIncident;
use App\Filament\App\Resources\Incidents\Pages\EditIncident;
use App\Filament\App\Resources\Incidents\Pages\ListIncidents;
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

class IncidentResource extends Resource
{
    protected static ?string $model = Incident::class;

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
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('severity')
                    ->required()
                    ->default('high'),
                TextInput::make('status')
                    ->required()
                    ->default('detected'),
                DateTimePicker::make('detected_at')
                    ->required(),
                DateTimePicker::make('acknowledged_at'),
                Select::make('acknowledged_by_id')
                    ->relationship('acknowledgedBy', 'name'),
                DateTimePicker::make('resolved_at'),
                Select::make('resolved_by_id')
                    ->relationship('resolvedBy', 'name'),
                DateTimePicker::make('closed_at'),
                Textarea::make('root_cause')
                    ->columnSpanFull(),
                Textarea::make('resolution_summary')
                    ->columnSpanFull(),
                TextInput::make('error_classification'),
                Textarea::make('impacted_services')
                    ->columnSpanFull(),
                Textarea::make('tags')
                    ->columnSpanFull(),
                Toggle::make('is_correlated')
                    ->required(),
                Select::make('parent_incident_id')
                    ->relationship('parentIncident', 'title'),
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
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('severity')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('detected_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('acknowledged_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('acknowledgedBy.name')
                    ->searchable(),
                TextColumn::make('resolved_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('resolvedBy.name')
                    ->searchable(),
                TextColumn::make('closed_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('error_classification')
                    ->searchable(),
                IconColumn::make('is_correlated')
                    ->boolean(),
                TextColumn::make('parentIncident.title')
                    ->searchable(),
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
            'index' => ListIncidents::route('/'),
            'create' => CreateIncident::route('/create'),
            'edit' => EditIncident::route('/{record}/edit'),
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
