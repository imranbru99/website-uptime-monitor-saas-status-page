<?php

namespace App\Filament\Client\Resources\StatusPages;

use App\Domain\StatusPages\Models\StatusPage;
use App\Filament\Client\Resources\StatusPages\Pages\CreateStatusPage;
use App\Filament\Client\Resources\StatusPages\Pages\EditStatusPage;
use App\Filament\Client\Resources\StatusPages\Pages\ListStatusPages;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
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

class StatusPageResource extends Resource
{
    protected static ?string $model = StatusPage::class;

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
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('visibility')
                    ->required()
                    ->default('public'),
                TextInput::make('password_hash')
                    ->password(),
                TextInput::make('logo_url')
                    ->url(),
                TextInput::make('favicon_url')
                    ->url(),
                TextInput::make('primary_color')
                    ->required()
                    ->default('#3B82F6'),
                TextInput::make('accent_color')
                    ->required()
                    ->default('#10B981'),
                TextInput::make('custom_domain'),
                Toggle::make('custom_domain_verified')
                    ->required(),
                Textarea::make('custom_css')
                    ->columnSpanFull(),
                Textarea::make('header_text')
                    ->columnSpanFull(),
                Textarea::make('footer_text')
                    ->columnSpanFull(),
                Toggle::make('show_uptime_history')
                    ->required(),
                Toggle::make('show_incident_history')
                    ->required(),
                Toggle::make('show_latency_chart')
                    ->required(),
                TextInput::make('meta_title'),
                Textarea::make('meta_description')
                    ->columnSpanFull(),
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
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('visibility')
                    ->searchable(),
                TextColumn::make('logo_url')
                    ->searchable(),
                TextColumn::make('favicon_url')
                    ->searchable(),
                TextColumn::make('primary_color')
                    ->searchable(),
                TextColumn::make('accent_color')
                    ->searchable(),
                TextColumn::make('custom_domain')
                    ->searchable(),
                IconColumn::make('custom_domain_verified')
                    ->boolean(),
                IconColumn::make('show_uptime_history')
                    ->boolean(),
                IconColumn::make('show_incident_history')
                    ->boolean(),
                IconColumn::make('show_latency_chart')
                    ->boolean(),
                TextColumn::make('meta_title')
                    ->searchable(),
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
            'index' => ListStatusPages::route('/'),
            'create' => CreateStatusPage::route('/create'),
            'edit' => EditStatusPage::route('/{record}/edit'),
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
