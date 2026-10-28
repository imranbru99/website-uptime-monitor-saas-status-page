<?php

namespace App\Filament\App\Resources\NotificationChannels;

use App\Domain\Alerts\Models\NotificationChannel;
use App\Filament\App\Resources\NotificationChannels\Pages\CreateNotificationChannel;
use App\Filament\App\Resources\NotificationChannels\Pages\EditNotificationChannel;
use App\Filament\App\Resources\NotificationChannels\Pages\ListNotificationChannels;
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

class NotificationChannelResource extends Resource
{
    protected static ?string $model = NotificationChannel::class;

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
                TextInput::make('channel_type')
                    ->required(),
                Textarea::make('destination')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('configuration')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
                Toggle::make('is_verified')
                    ->required(),
                DateTimePicker::make('last_tested_at'),
                TextInput::make('last_test_status'),
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
                TextColumn::make('channel_type')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->boolean(),
                IconColumn::make('is_verified')
                    ->boolean(),
                TextColumn::make('last_tested_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('last_test_status')
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
            'index' => ListNotificationChannels::route('/'),
            'create' => CreateNotificationChannel::route('/create'),
            'edit' => EditNotificationChannel::route('/{record}/edit'),
        ];
    }
}
