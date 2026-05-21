<?php

namespace App\Filament\Clinic\Widgets;

use App\Models\Patient;
use App\Models\TreatmentPlan;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class DashboardStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $clinic = Auth::user()?->clinic;

        if (! $clinic) {
            return [];
        }

        $clinicId = $clinic->id;

        // Contacts
        $totalContacts = Patient::where('clinic_id', $clinicId)->count();
        $newThisMonth = Patient::where('clinic_id', $clinicId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Plans
        $plansSent = TreatmentPlan::where('clinic_id', $clinicId)
            ->whereIn('status', ['sent', 'viewed', 'accepted', 'declined'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $accepted = TreatmentPlan::where('clinic_id', $clinicId)
            ->where('status', 'accepted')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $acceptanceRate = $plansSent > 0 ? round(($accepted / $plansSent) * 100) : 0;

        // Revenue
        $revenueThisMonth = TreatmentPlan::where('clinic_id', $clinicId)
            ->where('status', 'accepted')
            ->whereMonth('accepted_at', now()->month)
            ->whereYear('accepted_at', now()->year)
            ->sum('total_amount');

        $revenueLastMonth = TreatmentPlan::where('clinic_id', $clinicId)
            ->where('status', 'accepted')
            ->whereMonth('accepted_at', now()->subMonth()->month)
            ->whereYear('accepted_at', now()->subMonth()->year)
            ->sum('total_amount');

        $change = $revenueLastMonth > 0
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100)
            : 0;

        return [
            Stat::make('Total Contacts', $totalContacts)
                ->description($newThisMonth . ' new this month')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('primary'),

            Stat::make('Plans Sent', $plansSent)
                ->description($accepted . ' accepted')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),

            Stat::make('Acceptance Rate', $acceptanceRate . '%')
                ->description($accepted . ' of ' . $plansSent . ' sent')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color($acceptanceRate >= 60 ? 'success' : ($acceptanceRate >= 40 ? 'warning' : 'danger')),

            Stat::make('Revenue', '£' . number_format($revenueThisMonth, 0))
                ->description($change >= 0 ? '+' . $change . '% vs last month' : $change . '% vs last month')
                ->descriptionIcon($change >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($change >= 0 ? 'success' : 'danger'),
        ];
    }
}
