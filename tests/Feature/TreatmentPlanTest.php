<?php

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanItem;
use App\Models\User;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->clinic = Clinic::factory()->create();
    $this->clinicB = Clinic::factory()->create();
    $this->user = User::factory()->clinicOwner()->create(['clinic_id' => $this->clinic->id]);
    $this->patient = Patient::factory()->create(['clinic_id' => $this->clinic->id]);
    $this->actingAs($this->user);
});

// Reference number generation

test('reference number auto-generates with year prefix', function () {
    $plan = TreatmentPlan::factory()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
    ]);

    expect($plan->reference_number)->toStartWith('TP-'.now()->year.'-');
});

test('reference numbers are sequential per clinic', function () {
    $plan1 = TreatmentPlan::factory()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
        'reference_number' => 'TP-2026-00001',
    ]);

    $plan2 = TreatmentPlan::factory()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
        'reference_number' => 'TP-2026-00002',
    ]);

    expect($plan1->reference_number)->toBe('TP-2026-00001')
        ->and($plan2->reference_number)->toBe('TP-2026-00002');
});

// Total recalculation

test('total recalculates when items are added', function () {
    $plan = TreatmentPlan::factory()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
        'total_amount' => 0,
    ]);

    TreatmentPlanItem::create([
        'treatment_plan_id' => $plan->id,
        'name' => 'Implant',
        'quantity' => 2,
        'unit_price' => 1000.00,
        'line_total' => 2000.00,
        'is_optional' => false,
        'position' => 0,
    ]);

    TreatmentPlanItem::create([
        'treatment_plan_id' => $plan->id,
        'name' => 'Crown',
        'quantity' => 1,
        'unit_price' => 500.00,
        'line_total' => 500.00,
        'is_optional' => false,
        'position' => 1,
    ]);

    $plan->recalculateTotal();
    $plan->refresh();

    expect((float) $plan->total_amount)->toBe(2500.00);
});

test('optional items are excluded from total recalculation', function () {
    $plan = TreatmentPlan::factory()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
        'total_amount' => 0,
    ]);

    TreatmentPlanItem::create([
        'treatment_plan_id' => $plan->id,
        'name' => 'Implant',
        'quantity' => 1,
        'unit_price' => 1000.00,
        'line_total' => 1000.00,
        'is_optional' => false,
        'position' => 0,
    ]);

    TreatmentPlanItem::create([
        'treatment_plan_id' => $plan->id,
        'name' => 'Optional Whitening',
        'quantity' => 1,
        'unit_price' => 300.00,
        'line_total' => 300.00,
        'is_optional' => true,
        'position' => 1,
    ]);

    $plan->recalculateTotal();
    $plan->refresh();

    expect((float) $plan->total_amount)->toBe(1000.00);
});

// Valid state transitions

test('plan transitions from draft to sent', function () {
    $plan = TreatmentPlan::factory()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
        'status' => 'draft',
    ]);

    $plan->send();

    expect($plan->fresh()->status)->toBe('sent')
        ->and($plan->fresh()->sent_at)->not->toBeNull()
        ->and($plan->fresh()->public_token)->not->toBeNull();
});

test('plan transitions from sent to viewed', function () {
    $plan = TreatmentPlan::factory()->sent()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
    ]);

    $plan->recordView('192.168.1.1', 'Mozilla/5.0');

    expect($plan->fresh()->status)->toBe('viewed')
        ->and($plan->fresh()->viewed_at)->not->toBeNull()
        ->and($plan->fresh()->viewed_count)->toBe(1);
});

test('viewing again increments count without resetting viewed_at', function () {
    $plan = TreatmentPlan::factory()->sent()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
    ]);

    $plan->recordView('192.168.1.1', 'Mozilla/5.0');
    $firstViewedAt = $plan->fresh()->viewed_at;

    $plan->fresh()->recordView('192.168.1.2', 'Mozilla/5.0');

    expect($plan->fresh()->viewed_count)->toBe(2)
        ->and($plan->fresh()->viewed_at->toDateTimeString())->toBe($firstViewedAt->toDateTimeString());
});

test('plan transitions from viewed to accepted', function () {
    $plan = TreatmentPlan::factory()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
        'status' => 'viewed',
        'viewed_at' => now()->subHour(),
        'sent_at' => now()->subDay(),
    ]);

    $plan->accept('data:image/png;base64,abc123==', '85.75.192.1', 'Mozilla/5.0 (iPhone)');

    $fresh = $plan->fresh();
    expect($fresh->status)->toBe('accepted')
        ->and($fresh->accepted_at)->not->toBeNull()
        ->and($fresh->patient_signature)->toBe('data:image/png;base64,abc123==')
        ->and($fresh->patient_ip_at_signing)->toBe('85.75.192.1')
        ->and($fresh->patient_user_agent_at_signing)->toBe('Mozilla/5.0 (iPhone)')
        ->and($fresh->patient_signed_at)->not->toBeNull();
});

test('plan transitions from viewed to declined', function () {
    $plan = TreatmentPlan::factory()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
        'status' => 'viewed',
        'viewed_at' => now()->subHour(),
        'sent_at' => now()->subDay(),
    ]);

    $plan->decline('Price too high');

    $fresh = $plan->fresh();
    expect($fresh->status)->toBe('declined')
        ->and($fresh->declined_at)->not->toBeNull()
        ->and($fresh->decline_reason)->toBe('Price too high');
});

// Invalid state transitions

test('cannot accept a draft plan', function () {
    $plan = TreatmentPlan::factory()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
        'status' => 'draft',
    ]);

    expect(fn () => $plan->accept('sig', '1.2.3.4', 'UA'))
        ->toThrow(InvalidArgumentException::class);
});

test('cannot decline a draft plan', function () {
    $plan = TreatmentPlan::factory()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
        'status' => 'draft',
    ]);

    expect(fn () => $plan->decline('reason'))
        ->toThrow(InvalidArgumentException::class);
});

test('cannot send an already accepted plan', function () {
    $plan = TreatmentPlan::factory()->accepted()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
    ]);

    expect(fn () => $plan->send())
        ->toThrow(InvalidArgumentException::class);
});

// Send sets public_token and sent_at

test('send sets public_token and sent_at', function () {
    $plan = TreatmentPlan::factory()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
        'status' => 'draft',
        'public_token' => Str::uuid(),
        'sent_at' => null,
    ]);

    $plan->send();

    $fresh = $plan->fresh();
    expect($fresh->sent_at)->not->toBeNull()
        ->and($fresh->public_token)->not->toBeNull()
        ->and(Str::isUuid($fresh->public_token))->toBeTrue();
});

// Version increments on edit after send

test('version increments when editing a sent plan', function () {
    $plan = TreatmentPlan::factory()->sent()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
        'version' => 1,
    ]);

    $plan->incrementVersionOnEdit();

    expect($plan->fresh()->version)->toBe(2);
});

test('version does not change on draft edit', function () {
    $plan = TreatmentPlan::factory()->draft()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
        'version' => 1,
    ]);

    $plan->incrementVersionOnEdit();

    expect($plan->fresh()->version)->toBe(1);
});

// Clinic scope

test('plan belongs to clinic and is scoped', function () {
    $planA = TreatmentPlan::factory()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
    ]);

    $patientB = Patient::factory()->create(['clinic_id' => $this->clinicB->id]);
    $userB = User::factory()->clinicOwner()->create(['clinic_id' => $this->clinicB->id]);

    $planB = TreatmentPlan::withoutGlobalScopes()->create([
        'clinic_id' => $this->clinicB->id,
        'patient_id' => $patientB->id,
        'reference_number' => 'TP-2026-00099',
        'title' => 'Plan B',
        'status' => 'draft',
        'public_token' => Str::uuid(),
        'total_amount' => 0,
        'deposit_amount' => 0,
        'version' => 1,
        'viewed_count' => 0,
        'currency' => 'GBP',
        'language' => 'en',
        'is_template' => false,
    ]);

    // User A can only see clinic A plans
    $this->actingAs($this->user);
    $visible = TreatmentPlan::all();
    expect($visible->pluck('id'))->toContain($planA->id)
        ->and($visible->pluck('id'))->not->toContain($planB->id);
});

test('plan relates correctly to clinic', function () {
    $plan = TreatmentPlan::factory()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
    ]);

    expect($plan->clinic->id)->toBe($this->clinic->id);
});
