<?php

namespace App\Filament\Platform\Resources\SubscriptionPlans;

use App\Domain\Billing\Models\SubscriptionPlan;
use App\Filament\Platform\Resources\SubscriptionPlans\Pages\CreateSubscriptionPlan;
use App\Filament\Platform\Resources\SubscriptionPlans\Pages\EditSubscriptionPlan;
use App\Filament\Platform\Resources\SubscriptionPlans\Pages\ListSubscriptionPlans;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SubscriptionPlanResource extends Resource
{
    protected static ?string $model = SubscriptionPlan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('price_monthly')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('price_yearly')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('currency')
                    ->required()
                    ->default('USD'),
                TextInput::make('max_monitors')
                    ->required()
                    ->numeric()
                    ->default(10),
                TextInput::make('min_check_interval_seconds')
                    ->required()
                    ->numeric()
                    ->default(60),
                TextInput::make('max_status_pages')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('max_users')
                    ->required()
                    ->numeric()
                    ->default(3),
                TextInput::make('max_branches')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('max_alert_channels')
                    ->required()
                    ->numeric()
                    ->default(3),
                TextInput::make('retention_days')
                    ->required()
                    ->numeric()
                    ->default(30),
                Toggle::make('allow_api')
                    ->required(),
                Toggle::make('allow_ai')
                    ->required(),
                Toggle::make('allow_agency')
                    ->required(),
                Toggle::make('allow_white_label')
                    ->required(),
                Textarea::make('features')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
                Toggle::make('is_featured')
                    ->required(),
                TextInput::make('order_index')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('price_monthly')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price_yearly')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('currency')
                    ->searchable(),
                TextColumn::make('max_monitors')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('min_check_interval_seconds')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('max_status_pages')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('max_users')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('max_branches')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('max_alert_channels')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('retention_days')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('allow_api')
                    ->boolean(),
                IconColumn::make('allow_ai')
                    ->boolean(),
                IconColumn::make('allow_agency')
                    ->boolean(),
                IconColumn::make('allow_white_label')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->boolean(),
                IconColumn::make('is_featured')
                    ->boolean(),
                TextColumn::make('order_index')
                    ->numeric()
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
            'index' => ListSubscriptionPlans::route('/'),
            'create' => CreateSubscriptionPlan::route('/create'),
            'edit' => EditSubscriptionPlan::route('/{record}/edit'),
        ];
    }
}
