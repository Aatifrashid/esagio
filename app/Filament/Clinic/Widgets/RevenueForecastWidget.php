<?php

namespace App\Filament\Clinic\Widgets;

use App\Models\CrmPipelineStage;
use App\Models\Patient;
use App\Models\TreatmentPlan;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RevenueForecastWidget extends Widget
{
    protected static string $view = 'filament.clinic.widgets.revenue-forecast-widget';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $clinic = Auth::user()?->clinic;

        if (! $clinic) {
            return ['stages' => collect(), 'totalWeighted' => 0, 'totalUnweighted' => 0];
        }

        $stages = CrmPipelineStage::query()
            ->whereHas('pipeline', fn ($q) => $q->where('clinic_id', $clinic->id))
            ->where('is_lost', false)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($stage) use ($clinic) {
                $patientIds = Patient::where('clinic_id', $clinic->id)
                    ->where('pipeline_stage_id', $stage->id)
                    ->pluck('id');

                $totalValue = TreatmentPlan::where('clinic_id', $clinic->id)
                    ->whereIn('patient_id', $patientIds)
                    ->whereNotIn('status', ['rejected', 'expired'])
                    ->sum('total_amount');

                $dealCount = $patientIds->count();
                $probability = $stage->probability_percentage ?? 0;
                $weighted = $totalValue * ($probability / 100);

                return [
                    'name' => $stage->name,
                    'colour' => $stage->colour ?? '#94a3b8',
                    'probability' => $probability,
                    'deal_count' => $dealCount,
                    'total_value' => $totalValue,
                    'weighted_value' => $weighted,
                    'is_won' => $stage->is_won,
                ];
            });

        $totalWeighted = $stages->sum('weighted_value');
        $totalUnweighted = $stages->sum('total_value');

        $wonRevenue = TreatmentPlan::where('clinic_id', $clinic->id)
            ->where('status', 'accepted')
            ->whereMonth('accepted_at', now()->month)
            ->whereYear('accepted_at', now()->year)
            ->sum('total_amount');

        $monthlyTarget = $clinic->monthly_revenue_target ?? 0;

        return [
            'stages' => $stages,
            'totalWeighted' => $totalWeighted,
            'totalUnweighted' => $totalUnweighted,
            'wonRevenue' => $wonRevenue,
            'monthlyTarget' => $monthlyTarget,
        ];
    }
}
