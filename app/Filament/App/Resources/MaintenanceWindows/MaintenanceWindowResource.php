<?php

namespace App\Filament\App\Resources\MaintenanceWindows;

use App\Domain\Analytics\Models\MaintenanceWindow;
use App\Filament\App\Resources\MaintenanceWindows\Pages\CreateMaintenanceWindow;
use App\Filament\App\Resources\MaintenanceWindows\Pages\EditMaintenanceWindow;
use App\Filament\App\Resources\MaintenanceWindows\Pages\ListMaintenanceWindows;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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
use Filament\Tables\Table;

class MaintenanceWindowResource extends Resource
{
    protected static ?string $model = MaintenanceWindow::class;

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
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                DateTimePicker::make('starts_at')
                    ->required(),
                DateTimePicker::make('ends_at')
                    ->required(),
                Toggle::make('suppress_notifications')
                    ->required(),
                Toggle::make('mark_monitors_maintenance')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('scheduled'),
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
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('ends_at')
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('suppress_notifications')
                    ->boolean(),
                IconColumn::make('mark_monitors_maintenance')
                    ->boolean(),
                TextColumn::make('status')
                    ->searchable(),
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
            'index' => ListMaintenanceWindows::route('/'),
            'create' => CreateMaintenanceWindow::route('/create'),
            'edit' => EditMaintenanceWindow::route('/{record}/edit'),
        ];
    }
}
