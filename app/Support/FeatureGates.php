<?php

namespace App\Support;

use App\Models\Clinic;

class FeatureGates
{
    private const TIER_FEATURES = [
        'free' => [
            'tooth_chart_2d' => true,
            'basic_crm' => true,
        ],
        'starter' => [
            'tooth_chart_2d' => true,
            'basic_crm' => true,
            'custom_branding' => true,
            'remove_watermark' => true,
        ],
        'professional' => [
            'tooth_chart_2d' => true,
            'tooth_chart_3d' => true,
            'basic_crm' => true,
            'custom_branding' => true,
            'remove_watermark' => true,
            'video_consultation' => true,
            'automated_followups' => true,
            'crm_pipelines' => true,
        ],
        'agency' => [
            'tooth_chart_2d' => true,
            'tooth_chart_3d' => true,
            'basic_crm' => true,
            'custom_branding' => true,
            'remove_watermark' => true,
            'video_consultation' => true,
            'automated_followups' => true,
            'crm_pipelines' => true,
            'white_label' => true,
            'api_access' => true,
        ],
    ];

    private const PLAN_LIMITS = [
        'free' => 5,
        'starter' => 25,
        'professional' => 150,
        'agency' => PHP_INT_MAX,
    ];

    private const CONTACT_LIMITS = [
        'free' => 50,
        'starter' => 500,
        'professional' => 5000,
        'agency' => PHP_INT_MAX,
    ];

    private const VIDEO_MINUTE_LIMITS = [
        'free' => 0,
        'starter' => 0,
        'professional' => 120,
        'agency' => 600,
    ];

    public static function clinicHasAccess(Clinic $clinic, string $feature): bool
    {
        $override = $clinic->hasFeatureOverride($feature);
        if ($override !== null) {
            return $override;
        }

        $tier = $clinic->plan_tier ?? 'free';
        $features = self::TIER_FEATURES[$tier] ?? self::TIER_FEATURES['free'];

        return $features[$feature] ?? false;
    }

    public static function clinicCanCreatePlan(Clinic $clinic): bool
    {
        $tier = $clinic->plan_tier ?? 'free';
        $limit = self::PLAN_LIMITS[$tier] ?? 5;

        return $clinic->plans_used_this_month < $limit;
    }

    public static function clinicCanAddContact(Clinic $clinic): bool
    {
        $tier = $clinic->plan_tier ?? 'free';
        $limit = self::CONTACT_LIMITS[$tier] ?? 50;

        return $clinic->loadCount('patients')->patients_count < $limit;
    }

    public static function clinicCanUseVideoMinutes(Clinic $clinic, int $minutes): bool
    {
        $tier = $clinic->plan_tier ?? 'free';
        $limit = self::VIDEO_MINUTE_LIMITS[$tier] ?? 0;

        if ($limit === 0) {
            return false;
        }

        return ($clinic->video_minutes_used_this_month + $minutes) <= $limit;
    }

    public static function planLimit(Clinic $clinic): int
    {
        $tier = $clinic->plan_tier ?? 'free';

        return self::PLAN_LIMITS[$tier] ?? 5;
    }

    public static function contactLimit(Clinic $clinic): int
    {
        $tier = $clinic->plan_tier ?? 'free';

        return self::CONTACT_LIMITS[$tier] ?? 50;
    }

    public static function videoMinuteLimit(Clinic $clinic): int
    {
        $tier = $clinic->plan_tier ?? 'free';

        return self::VIDEO_MINUTE_LIMITS[$tier] ?? 0;
    }
}
