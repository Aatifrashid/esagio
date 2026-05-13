<?php

namespace App\Filament\Clinic\Widgets;

use App\Models\Patient;
use App\Models\TreatmentPlan;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CrmStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $clinicId = auth()->user()?->clinic_id;

        $totalContacts = Patient::where('clinic_id', $clinicId)->count();

        $newThisMonth = Patient::where('clinic_id', $clinicId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $plansSent = TreatmentPlan::where('clinic_id', $clinicId)
            ->whereIn('status', ['sent', 'accepted', 'declined'])
            ->count();

        $accepted = TreatmentPlan::where('clinic_id', $clinicId)
            ->where('status', 'accepted')
            ->count();

        $acceptanceRate = $plansSent > 0
            ? round(($accepted / $plansSent) * 100).'%'
            : '0%';

        return [
            Stat::make('Total Contacts', $totalContacts)
                ->description('All patients in CRM')
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make('New This Month', $newThisMonth)
                ->description('Added this month')
                ->icon('heroicon-o-user-plus')
                ->color('success'),

            Stat::make('Plans Sent', $plansSent)
                ->description('Treatment plans sent')
                ->icon('heroicon-o-document-text')
                ->color('info'),

            Stat::make('Acceptance Rate', $acceptanceRate)
                ->description('Plans accepted vs sent')
                ->icon('heroicon-o-chart-bar')
                ->color('warning'),
        ];
    }
}
