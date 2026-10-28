<?php

namespace App\Filament\App\Resources\Monitors;

use App\Domain\Monitoring\Models\Monitor;
use App\Domain\Monitoring\Services\CheckRunner;
use App\Domain\Monitoring\Services\SSRFValidator;
use App\Filament\App\Resources\Monitors\Pages\CreateMonitor;
use App\Filament\App\Resources\Monitors\Pages\EditMonitor;
use App\Filament\App\Resources\Monitors\Pages\ListMonitors;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Throwable;
use UnitEnum;

class MonitorResource extends Resource
{
    protected static ?string $model = Monitor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static string|UnitEnum|null $navigationGroup = 'Monitoring';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Configuration')
                    ->description('Identify your monitoring target and check frequency.')
                    ->components([
                        Grid::make(2)->components([
                            TextInput::make('name')
                                ->label('Monitor Name')
                                ->placeholder('e.g. Production Web App')
                                ->required()
                                ->maxLength(255),

                            Select::make('monitor_type')
                                ->label('Monitor Type')
                                ->options([
                                    'https' => 'HTTPS / Web Endpoint',
                                    'http' => 'HTTP Endpoint',
                                    'api' => 'REST API Endpoint',
                                    'tcp' => 'TCP Port Service',
                                    'dns' => 'DNS Resolution Check',
                                    'ssl' => 'SSL/TLS Certificate Validity',
                                    'domain' => 'Domain Expiration (RDAP/WHOIS)',
                                    'ping' => 'Ping / Latency Probe',
                                    'keyword' => 'Keyword & Content Assertion',
                                    'heartbeat' => 'Scheduled Cron Heartbeat',
                                    'server' => 'Server Monitoring Agent',
                                    'database' => 'Database Availability',
                                ])
                                ->default('https')
                                ->required(),
                        ]),

                        TextInput::make('target')
                            ->label('Target Host / URL / IP')
                            ->placeholder('https://example.com or api.example.com:443')
                            ->required()
                            ->maxLength(500)
                            ->rules([
                                function () {
                                    return function (string $attribute, $value, $fail) {
                                        try {
                                            SSRFValidator::validate($value);
                                        } catch (Throwable $e) {
                                            $fail($e->getMessage());
                                        }
                                    };
                                },
                            ]),

                        Textarea::make('description')
                            ->label('Description / Service Purpose')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),

                Section::make('Timing & Thresholds')
                    ->description('Configure execution schedules and failure thresholds.')
                    ->components([
                        Grid::make(3)->components([
                            Select::make('check_interval_seconds')
                                ->label('Check Interval')
                                ->options([
                                    10 => 'Every 10 seconds (Ultra-Fast)',
                                    30 => 'Every 30 seconds',
                                    60 => 'Every 1 minute (Standard)',
                                    120 => 'Every 2 minutes',
                                    300 => 'Every 5 minutes',
                                    600 => 'Every 10 minutes',
                                    1800 => 'Every 30 minutes',
                                    3600 => 'Every 1 hour',
                                ])
                                ->default(60)
                                ->required(),

                            TextInput::make('timeout_seconds')
                                ->label('Request Timeout (seconds)')
                                ->numeric()
                                ->default(15)
                                ->required(),

                            TextInput::make('confirmation_threshold')
                                ->label('Confirmation Threshold')
                                ->helperText('Consecutive failures required before marking DOWN')
                                ->numeric()
                                ->default(2)
                                ->required(),
                        ]),

                        Grid::make(2)->components([
                            Toggle::make('is_active')
                                ->label('Active')
                                ->default(true),

                            Toggle::make('is_paused')
                                ->label('Paused')
                                ->default(false),
                        ]),
                    ]),

                Section::make('Advanced Request & Assertions')
                    ->description('Optional headers, HTTP method, payload, and expected text assertions.')
                    ->collapsible()
                    ->collapsed()
                    ->components([
                        Grid::make(2)->components([
                            Select::make('config.method')
                                ->label('HTTP Method')
                                ->options([
                                    'GET' => 'GET',
                                    'POST' => 'POST',
                                    'HEAD' => 'HEAD',
                                    'PUT' => 'PUT',
                                    'PATCH' => 'PATCH',
                                    'DELETE' => 'DELETE',
                                ])
                                ->default('GET'),

                            TextInput::make('config.expected_status')
                                ->label('Expected HTTP Status Code')
                                ->placeholder('200')
                                ->numeric()
                                ->default(200),
                        ]),

                        Grid::make(2)->components([
                            TextInput::make('config.expected_keyword')
                                ->label('Required Keyword in Response')
                                ->placeholder('e.g. Status: OK or {"success": true}'),

                            TextInput::make('config.forbidden_keyword')
                                ->label('Forbidden Keyword in Response')
                                ->placeholder('e.g. Error or Exception or 503'),
                        ]),

                        KeyValue::make('config.headers')
                            ->label('Custom Request Headers')
                            ->keyLabel('Header Name')
                            ->valueLabel('Header Value'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('status')
                    ->label('Health')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'healthy' => 'success',
                        'degraded' => 'warning',
                        'suspected_down' => 'warning',
                        'down' => 'danger',
                        'recovering' => 'info',
                        'paused' => 'gray',
                        'maintenance' => 'purple',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Monitor Name')
                    ->description(fn (Monitor $record): string => $record->target)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('monitor_type')
                    ->label('Type')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('uptime_percentage_24h')
                    ->label('24h Uptime')
                    ->formatStateUsing(fn ($state) => "{$state}%")
                    ->color(fn ($state) => $state >= 99.9 ? 'success' : ($state >= 95 ? 'warning' : 'danger'))
                    ->sortable(),

                TextColumn::make('avg_latency_ms')
                    ->label('Avg Latency')
                    ->formatStateUsing(fn ($state) => "{$state} ms")
                    ->sortable(),

                TextColumn::make('last_checked_at')
                    ->label('Last Checked')
                    ->since()
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'healthy' => 'Healthy',
                        'down' => 'Down',
                        'degraded' => 'Degraded',
                        'paused' => 'Paused',
                        'maintenance' => 'Maintenance',
                    ]),
                SelectFilter::make('monitor_type')
                    ->options([
                        'https' => 'HTTPS',
                        'http' => 'HTTP',
                        'api' => 'API',
                        'tcp' => 'TCP',
                        'ssl' => 'SSL',
                        'dns' => 'DNS',
                        'heartbeat' => 'Heartbeat',
                    ]),
            ])
            ->recordActions([
                Action::make('checkNow')
                    ->label('Check Now')
                    ->icon(Heroicon::OutlinedArrowPath)
                    ->color('info')
                    ->action(function (Monitor $record, CheckRunner $runner) {
                        $check = $runner->execute($record);
                        $color = $check->status === 'success' ? 'success' : 'danger';
                        Notification::make()
                            ->title("Check executed: {$check->status}")
                            ->body("Latency: {$check->latency_ms}ms".($check->error_message ? " | Error: {$check->error_message}" : ''))
                            ->color($color)
                            ->send();
                    }),
                Action::make('togglePause')
                    ->label(fn (Monitor $record) => $record->is_paused ? 'Resume' : 'Pause')
                    ->icon(fn (Monitor $record) => $record->is_paused ? Heroicon::OutlinedPlay : Heroicon::OutlinedPause)
                    ->color('gray')
                    ->action(function (Monitor $record) {
                        $record->update(['is_paused' => ! $record->is_paused]);
                        Notification::make()
                            ->title($record->is_paused ? 'Monitor Paused' : 'Monitor Resumed')
                            ->send();
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMonitors::route('/'),
            'create' => CreateMonitor::route('/create'),
            'edit' => EditMonitor::route('/{record}/edit'),
        ];
    }
}
