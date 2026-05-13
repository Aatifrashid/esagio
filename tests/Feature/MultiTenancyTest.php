<?php

use App\Models\Clinic;
use App\Models\User;
use App\Support\FeatureGates;

beforeEach(function () {
    $this->clinicA = Clinic::factory()->create(['name' => 'Clinic A']);
    $this->clinicB = Clinic::factory()->create(['name' => 'Clinic B']);
    $this->userA = User::factory()->clinicOwner()->create(['clinic_id' => $this->clinicA->id]);
    $this->userB = User::factory()->clinicOwner()->create(['clinic_id' => $this->clinicB->id]);
    $this->superAdmin = User::factory()->superAdmin()->create();
});

test('clinic factory creates valid clinic', function () {
    expect($this->clinicA)->toBeInstanceOf(Clinic::class)
        ->and($this->clinicA->name)->toBe('Clinic A')
        ->and($this->clinicA->is_active)->toBeTrue()
        ->and($this->clinicA->plan_tier)->toBe('free');
});

test('user belongs to clinic', function () {
    expect($this->userA->clinic->id)->toBe($this->clinicA->id);
});

test('super admin has no clinic', function () {
    expect($this->superAdmin->clinic_id)->toBeNull()
        ->and($this->superAdmin->isSuperAdmin())->toBeTrue();
});

test('clinic has many users', function () {
    expect($this->clinicA->users)->toHaveCount(1);
});

test('suspended clinic is detected', function () {
    $clinic = Clinic::factory()->suspended()->create();
    expect($clinic->isSuspended())->toBeTrue();
});

test('trial clinic is detected', function () {
    $clinic = Clinic::factory()->onTrial()->create();
    expect($clinic->isOnTrial())->toBeTrue();
});

test('feature gates free tier', function () {
    $clinic = Clinic::factory()->create(['plan_tier' => 'free']);
    expect(FeatureGates::clinicHasAccess($clinic, 'tooth_chart_2d'))->toBeTrue()
        ->and(FeatureGates::clinicHasAccess($clinic, 'tooth_chart_3d'))->toBeFalse()
        ->and(FeatureGates::clinicHasAccess($clinic, 'video_consultation'))->toBeFalse()
        ->and(FeatureGates::clinicHasAccess($clinic, 'custom_branding'))->toBeFalse()
        ->and(FeatureGates::clinicHasAccess($clinic, 'remove_watermark'))->toBeFalse();
});

test('feature gates starter tier', function () {
    $clinic = Clinic::factory()->starter()->create();
    expect(FeatureGates::clinicHasAccess($clinic, 'custom_branding'))->toBeTrue()
        ->and(FeatureGates::clinicHasAccess($clinic, 'remove_watermark'))->toBeTrue()
        ->and(FeatureGates::clinicHasAccess($clinic, 'tooth_chart_3d'))->toBeFalse()
        ->and(FeatureGates::clinicHasAccess($clinic, 'video_consultation'))->toBeFalse();
});

test('feature gates professional tier', function () {
    $clinic = Clinic::factory()->professional()->create();
    expect(FeatureGates::clinicHasAccess($clinic, 'tooth_chart_3d'))->toBeTrue()
        ->and(FeatureGates::clinicHasAccess($clinic, 'video_consultation'))->toBeTrue()
        ->and(FeatureGates::clinicHasAccess($clinic, 'automated_followups'))->toBeTrue()
        ->and(FeatureGates::clinicHasAccess($clinic, 'crm_pipelines'))->toBeTrue()
        ->and(FeatureGates::clinicHasAccess($clinic, 'white_label'))->toBeFalse();
});

test('feature gates agency tier', function () {
    $clinic = Clinic::factory()->agency()->create();
    expect(FeatureGates::clinicHasAccess($clinic, 'white_label'))->toBeTrue()
        ->and(FeatureGates::clinicHasAccess($clinic, 'api_access'))->toBeTrue();
});

test('feature override grants access', function () {
    $clinic = Clinic::factory()->create([
        'plan_tier' => 'free',
        'feature_overrides' => ['tooth_chart_3d' => true],
    ]);
    expect(FeatureGates::clinicHasAccess($clinic, 'tooth_chart_3d'))->toBeTrue();
});

test('feature override denies access', function () {
    $clinic = Clinic::factory()->professional()->create([
        'feature_overrides' => ['video_consultation' => false],
    ]);
    expect(FeatureGates::clinicHasAccess($clinic, 'video_consultation'))->toBeFalse();
});

test('plan limits enforced', function () {
    $clinic = Clinic::factory()->create(['plan_tier' => 'free', 'plans_used_this_month' => 4]);
    expect(FeatureGates::clinicCanCreatePlan($clinic))->toBeTrue();

    $clinic->plans_used_this_month = 5;
    expect(FeatureGates::clinicCanCreatePlan($clinic))->toBeFalse();
});

test('video minute limits enforced', function () {
    $clinic = Clinic::factory()->professional()->create(['video_minutes_used_this_month' => 100]);
    expect(FeatureGates::clinicCanUseVideoMinutes($clinic, 20))->toBeTrue()
        ->and(FeatureGates::clinicCanUseVideoMinutes($clinic, 21))->toBeFalse();

    $freeclinic = Clinic::factory()->create(['plan_tier' => 'free']);
    expect(FeatureGates::clinicCanUseVideoMinutes($freeclinic, 1))->toBeFalse();
});
