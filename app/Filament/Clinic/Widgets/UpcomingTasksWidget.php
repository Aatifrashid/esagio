<?php

namespace App\Filament\Clinic\Widgets;

use App\Models\CrmTask;
use Filament\Widgets\Widget;

class UpcomingTasksWidget extends Widget
{
    protected static ?int $sort = 3;

    protected static string $view = 'filament.clinic.widgets.upcoming-tasks';

    protected int|string|array $columnSpan = 1;

    public function getTasks()
    {
        return CrmTask::with(['patient', 'assignedUser'])
            ->where('clinic_id', auth()->user()?->clinic_id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->where('due_date', '<=', now()->endOfWeek())
            ->orderBy('due_date')
            ->take(10)
            ->get();
    }
}
