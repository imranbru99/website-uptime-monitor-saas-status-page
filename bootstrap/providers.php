<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AppPanelProvider;
use App\Providers\Filament\BranchPanelProvider;
use App\Providers\Filament\ClientPanelProvider;
use App\Providers\Filament\OperationsPanelProvider;
use App\Providers\Filament\PlatformPanelProvider;

return [
    AppServiceProvider::class,
    AppPanelProvider::class,
    BranchPanelProvider::class,
    ClientPanelProvider::class,
    OperationsPanelProvider::class,
    PlatformPanelProvider::class,
];
