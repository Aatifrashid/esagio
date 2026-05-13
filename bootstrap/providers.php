<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\ClinicPanelProvider;
use App\Providers\Filament\SuperPanelProvider;
use App\Providers\FortifyServiceProvider;
use App\Providers\VoltServiceProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    ClinicPanelProvider::class,
    SuperPanelProvider::class,
    FortifyServiceProvider::class,
    VoltServiceProvider::class,
];
