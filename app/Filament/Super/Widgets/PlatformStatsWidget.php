<?php

namespace App\Filament\Super\Widgets;

use App\Models\Clinic;
use App\Models\TreatmentPlan;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PlatformStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Clinics', Clinic::count())
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('primary'),

            Stat::make('Active Clinics', Clinic::where('is_active', true)->count())
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Total Users', User::where('role', '!=', 'super_admin')->count())
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Total Plans', TreatmentPlan::count())
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),
        ];
    }
}
