<?php

namespace App\Filament\Platform\Resources\MonitorLocations;

use App\Domain\Monitoring\Models\MonitorLocation;
use App\Filament\Platform\Resources\MonitorLocations\Pages\CreateMonitorLocation;
use App\Filament\Platform\Resources\MonitorLocations\Pages\EditMonitorLocation;
use App\Filament\Platform\Resources\MonitorLocations\Pages\ListMonitorLocations;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MonitorLocationResource extends Resource
{
    protected static ?string $model = MonitorLocation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                TextInput::make('city')
                    ->required(),
                TextInput::make('country')
                    ->required(),
                TextInput::make('region')
                    ->required(),
                TextInput::make('latitude')
                    ->numeric(),
                TextInput::make('longitude')
                    ->numeric(),
                TextInput::make('ip_address'),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
                DateTimePicker::make('last_heartbeat_at'),
                Textarea::make('capabilities')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('city')
                    ->searchable(),
                TextColumn::make('country')
                    ->searchable(),
                TextColumn::make('region')
                    ->searchable(),
                TextColumn::make('latitude')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('longitude')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ip_address')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('last_heartbeat_at')
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
            'index' => ListMonitorLocations::route('/'),
            'create' => CreateMonitorLocation::route('/create'),
            'edit' => EditMonitorLocation::route('/{record}/edit'),
        ];
    }
}
