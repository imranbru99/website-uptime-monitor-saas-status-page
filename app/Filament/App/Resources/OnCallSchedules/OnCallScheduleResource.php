<?php

namespace App\Filament\App\Resources\OnCallSchedules;

use App\Domain\Alerts\Models\OnCallSchedule;
use App\Filament\App\Resources\OnCallSchedules\Pages\CreateOnCallSchedule;
use App\Filament\App\Resources\OnCallSchedules\Pages\EditOnCallSchedule;
use App\Filament\App\Resources\OnCallSchedules\Pages\ListOnCallSchedules;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OnCallScheduleResource extends Resource
{
    protected static ?string $model = OnCallSchedule::class;

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
                TextInput::make('name')
                    ->required(),
                TextInput::make('timezone')
                    ->required()
                    ->default('UTC'),
                TextInput::make('rotation_type')
                    ->required()
                    ->default('weekly'),
                DateTimePicker::make('starts_at')
                    ->required(),
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
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('timezone')
                    ->searchable(),
                TextColumn::make('rotation_type')
                    ->searchable(),
                TextColumn::make('starts_at')
                    ->dateTime()
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
            'index' => ListOnCallSchedules::route('/'),
            'create' => CreateOnCallSchedule::route('/create'),
            'edit' => EditOnCallSchedule::route('/{record}/edit'),
        ];
    }
}
