<?php

namespace App\Filament\Clinic\Widgets;

use App\Models\CrmActivity;
use Filament\Widgets\Widget;

class RecentActivitiesWidget extends Widget
{
    protected static ?int $sort = 2;

    protected static string $view = 'filament.clinic.widgets.recent-activities';

    protected int|string|array $columnSpan = 1;

    public function getActivities()
    {
        return CrmActivity::with(['patient', 'user'])
            ->where('clinic_id', auth()->user()?->clinic_id)
            ->latest('occurred_at')
            ->take(5)
            ->get();
    }
}
