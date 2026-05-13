<?php

namespace App\Filament\Clinic\Widgets;

use App\Models\TreatmentPlan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class PlanStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $clinic = Auth::user()?->clinic;

        if (! $clinic) {
            return [];
        }

        $plansThisMonth = TreatmentPlan::where('clinic_id', $clinic->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $quota = match ($clinic->plan_tier) {
            'starter' => 20,
            'professional' => 100,
            'agency' => 500,
            default => 3,
        };

        $accepted = TreatmentPlan::where('clinic_id', $clinic->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'accepted')
            ->count();

        $sent = TreatmentPlan::where('clinic_id', $clinic->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->whereIn('status', ['sent', 'viewed', 'accepted', 'declined'])
            ->count();

        $acceptanceRate = $sent > 0 ? round(($accepted / $sent) * 100) : 0;

        return [
            Stat::make('Plans This Month', $plansThisMonth.' / '.$quota)
                ->description('of your monthly quota used')
                ->descriptionIcon('heroicon-m-document-text')
                ->color($plansThisMonth >= $quota ? 'danger' : 'primary'),

            Stat::make('Acceptance Rate', $acceptanceRate.'%')
                ->description($accepted.' accepted of '.$sent.' sent')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color($acceptanceRate >= 60 ? 'success' : ($acceptanceRate >= 40 ? 'warning' : 'danger')),
        ];
    }
}
