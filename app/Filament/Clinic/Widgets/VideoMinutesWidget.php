<?php

namespace App\Filament\Clinic\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class VideoMinutesWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        $clinic = Auth::user()?->clinic;

        if (! $clinic) {
            return [];
        }

        $used = $clinic->video_minutes_used_this_month ?? 0;

        $limit = match ($clinic->plan_tier) {
            'starter' => 120,
            'professional' => 600,
            'agency' => 3000,
            default => 0,
        };

        if ($limit === 0) {
            return [
                Stat::make('Video Consultations', 'Not available')
                    ->description('Upgrade to use video consultations')
                    ->color('secondary'),
            ];
        }

        $percentage = round(($used / $limit) * 100);

        return [
            Stat::make('Video Minutes Used', $used.' / '.$limit)
                ->description($percentage.'% of monthly allowance')
                ->descriptionIcon('heroicon-m-video-camera')
                ->color($percentage >= 90 ? 'danger' : ($percentage >= 70 ? 'warning' : 'success')),
        ];
    }
}
