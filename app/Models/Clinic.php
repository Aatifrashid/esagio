<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Cashier\Billable;

class Clinic extends Model
{
    use Billable, HasFactory;

    protected $fillable = [
        'name', 'slug', 'email', 'phone', 'country', 'timezone',
        'currency_default', 'language_default', 'logo_path',
        'primary_colour', 'secondary_colour', 'font_family',
        'address', 'registration_number', 'vat_number', 'plan_tier',
        'trial_ends_at', 'stripe_id', 'stripe_subscription_id',
        'is_active', 'suspended_at', 'suspended_reason',
        'monthly_plan_count_reset_at', 'plans_used_this_month',
        'video_minutes_used_this_month', 'settings', 'feature_overrides',
        'parent_clinic_id', 'referred_by_clinic_id',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'trial_ends_at' => 'datetime',
            'suspended_at' => 'datetime',
            'monthly_plan_count_reset_at' => 'datetime',
            'settings' => 'array',
            'feature_overrides' => 'array',
        ];
    }

    public function branding(): HasOne
    {
        return $this->hasOne(ClinicBranding::class);
    }

    public function teamMembers(): HasMany
    {
        return $this->hasMany(ClinicTeamMember::class)->orderBy('sort_order');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function parentClinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class, 'parent_clinic_id');
    }

    public function subClinics(): HasMany
    {
        return $this->hasMany(Clinic::class, 'parent_clinic_id');
    }

    public function referredBy(): BelongsTo
    {
        return $this->belongsTo(Clinic::class, 'referred_by_clinic_id');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(Clinic::class, 'referred_by_clinic_id');
    }

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }

    public function treatmentPlans(): HasMany
    {
        return $this->hasMany(TreatmentPlan::class);
    }

    public function isOnTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function isSuspended(): bool
    {
        return $this->suspended_at !== null;
    }

    public function hasFeatureOverride(string $feature): ?bool
    {
        $overrides = $this->feature_overrides ?? [];

        return isset($overrides[$feature]) ? (bool) $overrides[$feature] : null;
    }
}
