<?php

namespace App\Filament\Platform\Resources\Modules;

use App\Domain\Modules\Models\Module;
use App\Filament\Platform\Resources\Modules\Pages\CreateModule;
use App\Filament\Platform\Resources\Modules\Pages\EditModule;
use App\Filament\Platform\Resources\Modules\Pages\ListModules;
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

class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('category')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('icon'),
                TextInput::make('version')
                    ->required()
                    ->default('1.0.0'),
                Textarea::make('dependencies')
                    ->columnSpanFull(),
                Textarea::make('required_permissions')
                    ->columnSpanFull(),
                TextInput::make('plan_tier')
                    ->required()
                    ->default('free'),
                Toggle::make('is_mandatory')
                    ->required(),
                Toggle::make('is_core')
                    ->required(),
                Toggle::make('default_enabled')
                    ->required(),
                Toggle::make('allow_branch_override')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('category')
                    ->searchable(),
                TextColumn::make('icon')
                    ->searchable(),
                TextColumn::make('version')
                    ->searchable(),
                TextColumn::make('plan_tier')
                    ->searchable(),
                IconColumn::make('is_mandatory')
                    ->boolean(),
                IconColumn::make('is_core')
                    ->boolean(),
                IconColumn::make('default_enabled')
                    ->boolean(),
                IconColumn::make('allow_branch_override')
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
            'index' => ListModules::route('/'),
            'create' => CreateModule::route('/create'),
            'edit' => EditModule::route('/{record}/edit'),
        ];
    }
}
