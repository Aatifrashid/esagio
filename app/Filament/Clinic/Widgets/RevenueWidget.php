<?php

namespace App\Filament\Clinic\Widgets;

use App\Models\TreatmentPlan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class RevenueWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $clinic = Auth::user()?->clinic;

        if (! $clinic) {
            return [];
        }

        $revenueThisMonth = TreatmentPlan::where('clinic_id', $clinic->id)
            ->where('status', 'accepted')
            ->whereMonth('accepted_at', now()->month)
            ->whereYear('accepted_at', now()->year)
            ->sum('total_amount');

        $revenueLastMonth = TreatmentPlan::where('clinic_id', $clinic->id)
            ->where('status', 'accepted')
            ->whereMonth('accepted_at', now()->subMonth()->month)
            ->whereYear('accepted_at', now()->subMonth()->year)
            ->sum('total_amount');

        $change = $revenueLastMonth > 0
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100)
            : 0;

        return [
            Stat::make('Revenue This Month', '£'.number_format($revenueThisMonth, 2))
                ->description($change >= 0 ? '+'.$change.'% vs last month' : $change.'% vs last month')
                ->descriptionIcon($change >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($change >= 0 ? 'success' : 'danger'),
        ];
    }
}
