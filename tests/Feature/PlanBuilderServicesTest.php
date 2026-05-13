<?php

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanItem;
use App\Models\TreatmentPlanToothChart;
use App\Models\User;
use App\Services\PlanBuilder\AutoSaveManager;
use App\Services\PlanBuilder\BuilderStateMachine;
use App\Services\PlanBuilder\ItemSuggestionEngine;
use App\Services\PlanBuilder\PlanCloner;
use App\Services\PlanBuilder\PricingCalculator;

beforeEach(function () {
    $this->clinic = Clinic::factory()->create();
    $this->user = User::factory()->clinicOwner()->create(['clinic_id' => $this->clinic->id]);
    $this->patient = Patient::factory()->create(['clinic_id' => $this->clinic->id]);
    $this->actingAs($this->user);
});

// -----------------------------------------------------------------------
// PricingCalculator
// -----------------------------------------------------------------------

test('calculator: line total multiplies quantity by unit price', function () {
    $calc = new PricingCalculator;
    expect($calc->calculateLineTotal(3, 250.00))->toBe(750.00);
});

test('calculator: line total rounds to 2 decimal places', function () {
    $calc = new PricingCalculator;
    expect($calc->calculateLineTotal(3, 33.333))->toBe(100.00);
});

test('calculator: subtotal sums non-optional items only', function () {
    $plan = TreatmentPlan::factory()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
    ]);
    TreatmentPlanItem::factory()->create([
        'treatment_plan_id' => $plan->id,
        'line_total' => 1000.00,
        'is_optional' => false,
    ]);
    TreatmentPlanItem::factory()->create([
        'treatment_plan_id' => $plan->id,
        'line_total' => 500.00,
        'is_optional' => true,
    ]);

    $calc = new PricingCalculator;
    expect($calc->calculateSubtotal($plan))->toBe(1000.00);
});

test('calculator: percentage discount', function () {
    $calc = new PricingCalculator;
    expect($calc->calculateDiscount(1000.00, 'percentage', 10.0))->toBe(100.00);
});

test('calculator: fixed discount', function () {
    $calc = new PricingCalculator;
    expect($calc->calculateDiscount(1000.00, 'fixed', 150.0))->toBe(150.00);
});

test('calculator: fixed discount cannot exceed subtotal', function () {
    $calc = new PricingCalculator;
    expect($calc->calculateDiscount(100.00, 'fixed', 200.0))->toBe(100.00);
});

test('calculator: null discount type returns zero', function () {
    $calc = new PricingCalculator;
    expect($calc->calculateDiscount(1000.00, null, null))->toBe(0.0);
});

test('calculator: tax calculation', function () {
    $calc = new PricingCalculator;
    expect($calc->calculateTax(1000.00, 20.0))->toBe(200.00);
});

test('calculator: deposit calculation', function () {
    $calc = new PricingCalculator;
    expect($calc->calculateDeposit(2000.00, 25.0))->toBe(500.00);
});

test('calculator: calculateTotal returns correct breakdown', function () {
    $plan = TreatmentPlan::factory()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
    ]);
    TreatmentPlanItem::factory()->create([
        'treatment_plan_id' => $plan->id,
        'line_total' => 1000.00,
        'is_optional' => false,
    ]);

    $calc = new PricingCalculator;
    $result = $calc->calculateTotal($plan, 'percentage', 10.0, 20.0, 20.0);

    expect($result['subtotal'])->toBe(1000.00)
        ->and($result['discount'])->toBe(100.00)
        ->and($result['tax'])->toBe(180.00)
        ->and($result['total'])->toBe(1080.00)
        ->and($result['deposit'])->toBe(216.00);
});

// -----------------------------------------------------------------------
// ItemSuggestionEngine
// -----------------------------------------------------------------------

test('suggestion: missing tooth suggests implant', function () {
    $plan = TreatmentPlan::factory()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
    ]);
    TreatmentPlanToothChart::create([
        'treatment_plan_id' => $plan->id,
        'tooth_number' => 21,
        'conditions' => ['missing'],
        'planned_treatments' => [],
    ]);

    $engine = new ItemSuggestionEngine;
    $suggestions = $engine->suggestForDiagnosis($plan);

    $names = array_column($suggestions, 'name');
    expect($names)->toContain('Dental Implant');
});

test('suggestion: decayed tooth suggests crown', function () {
    $plan = TreatmentPlan::factory()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
    ]);
    TreatmentPlanToothChart::create([
        'treatment_plan_id' => $plan->id,
        'tooth_number' => 16,
        'conditions' => ['decayed'],
        'planned_treatments' => [],
    ]);

    $engine = new ItemSuggestionEngine;
    $suggestions = $engine->suggestForDiagnosis($plan);

    $names = array_column($suggestions, 'name');
    expect($names)->toContain('Zirconia Crown');
});

test('suggestion: 4 or more missing upper teeth suggests All-on-4', function () {
    $plan = TreatmentPlan::factory()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
    ]);

    foreach ([11, 12, 13, 14] as $tooth) {
        TreatmentPlanToothChart::create([
            'treatment_plan_id' => $plan->id,
            'tooth_number' => $tooth,
            'conditions' => ['missing'],
            'planned_treatments' => [],
        ]);
    }

    $engine = new ItemSuggestionEngine;
    $suggestions = $engine->suggestForDiagnosis($plan);

    $names = array_column($suggestions, 'name');
    expect($names)->toContain('All-on-4 Dental Implants');
});

test('suggestion: fewer than 4 missing upper teeth does not suggest All-on-4', function () {
    $plan = TreatmentPlan::factory()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
    ]);

    foreach ([11, 12, 13] as $tooth) {
        TreatmentPlanToothChart::create([
            'treatment_plan_id' => $plan->id,
            'tooth_number' => $tooth,
            'conditions' => ['missing'],
            'planned_treatments' => [],
        ]);
    }

    $engine = new ItemSuggestionEngine;
    $suggestions = $engine->suggestForDiagnosis($plan);

    $names = array_column($suggestions, 'name');
    expect($names)->not->toContain('All-on-4 Dental Implants');
});

test('suggestion: implant companions include abutment and crown', function () {
    $item = new TreatmentPlanItem(['name' => 'Dental Implant (Straumann)', 'tooth_positions' => [21]]);

    $engine = new ItemSuggestionEngine;
    $companions = $engine->suggestCompanions($item);

    $names = array_column($companions, 'name');
    expect($names)->toContain('Implant Abutment')
        ->and($names)->toContain('Implant Crown (Zirconia)');
});

test('suggestion: crown companion includes core build-up', function () {
    $item = new TreatmentPlanItem(['name' => 'Zirconia Crown', 'tooth_positions' => [16]]);

    $engine = new ItemSuggestionEngine;
    $companions = $engine->suggestCompanions($item);

    $names = array_column($companions, 'name');
    expect($names)->toContain('Core Build-Up');
});

test('suggestion: root canal companion includes crown', function () {
    $item = new TreatmentPlanItem(['name' => 'Root Canal Treatment', 'tooth_positions' => [36]]);

    $engine = new ItemSuggestionEngine;
    $companions = $engine->suggestCompanions($item);

    $names = array_column($companions, 'name');
    expect($names)->toContain('Zirconia Crown');
});

// -----------------------------------------------------------------------
// PlanCloner
// -----------------------------------------------------------------------

test('cloner: clone produces new plan with new reference and draft status', function () {
    $source = TreatmentPlan::factory()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
        'status' => 'sent',
    ]);
    TreatmentPlanItem::factory()->create(['treatment_plan_id' => $source->id, 'is_optional' => false]);

    $cloner = new PlanCloner;
    $clone = $cloner->clone($source);

    expect($clone->status)->toBe('draft')
        ->and($clone->id)->not->toBe($source->id)
        ->and($clone->reference_number)->not->toBe($source->reference_number)
        ->and($clone->public_token)->not->toBe($source->public_token)
        ->and($clone->items)->toHaveCount(1);
});

test('cloner: clone with new patient_id overrides patient', function () {
    $patient2 = Patient::factory()->create(['clinic_id' => $this->clinic->id]);
    $source = TreatmentPlan::factory()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
    ]);

    $cloner = new PlanCloner;
    $clone = $cloner->clone($source, $patient2->id);

    expect($clone->patient_id)->toBe($patient2->id);
});

test('cloner: saveAsTemplate sets is_template and template_name', function () {
    $plan = TreatmentPlan::factory()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
    ]);

    $cloner = new PlanCloner;
    $template = $cloner->saveAsTemplate($plan, 'My Template');

    expect($template->is_template)->toBeTrue()
        ->and($template->template_name)->toBe('My Template');
});

test('cloner: createFromTemplate links template_used_id', function () {
    $template = TreatmentPlan::factory()->template()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
    ]);

    $patient2 = Patient::factory()->create(['clinic_id' => $this->clinic->id]);

    $cloner = new PlanCloner;
    $plan = $cloner->createFromTemplate($template, $patient2->id, $this->clinic->id);

    expect($plan->template_used_id)->toBe($template->id)
        ->and($plan->is_template)->toBeFalse()
        ->and($plan->patient_id)->toBe($patient2->id);
});

// -----------------------------------------------------------------------
// BuilderStateMachine
// -----------------------------------------------------------------------

test('state machine: valid transition draft to sent', function () {
    $plan = TreatmentPlan::factory()->draft()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
    ]);

    $sm = new BuilderStateMachine;
    expect($sm->canTransition($plan, 'sent'))->toBeTrue();

    $updated = $sm->transition($plan, 'sent');
    expect($updated->status)->toBe('sent');
});

test('state machine: draft to draft is valid (save)', function () {
    $plan = TreatmentPlan::factory()->draft()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
    ]);

    $sm = new BuilderStateMachine;
    expect($sm->canTransition($plan, 'draft'))->toBeTrue();
});

test('state machine: invalid transition draft to accepted throws', function () {
    $plan = TreatmentPlan::factory()->draft()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
    ]);

    $sm = new BuilderStateMachine;
    expect(fn () => $sm->transition($plan, 'accepted'))
        ->toThrow(InvalidArgumentException::class);
});

test('state machine: invalid transition accepted to sent throws', function () {
    $plan = TreatmentPlan::factory()->accepted()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
    ]);

    $sm = new BuilderStateMachine;
    expect(fn () => $sm->transition($plan, 'sent'))
        ->toThrow(InvalidArgumentException::class);
});

test('state machine: transition logs a TreatmentPlanEvent', function () {
    $plan = TreatmentPlan::factory()->sent()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
    ]);

    $sm = new BuilderStateMachine;
    $sm->transition($plan, 'viewed');

    expect($plan->events()->where('event_type', 'status_changed')->count())->toBe(1);
});

test('state machine: viewed to accepted is valid', function () {
    $plan = TreatmentPlan::factory()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
        'status' => 'viewed',
    ]);

    $sm = new BuilderStateMachine;
    expect($sm->canTransition($plan, 'accepted'))->toBeTrue();
});

test('state machine: sent to expired is valid', function () {
    $plan = TreatmentPlan::factory()->sent()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
    ]);

    $sm = new BuilderStateMachine;
    expect($sm->canTransition($plan, 'expired'))->toBeTrue();
});

// -----------------------------------------------------------------------
// AutoSaveManager
// -----------------------------------------------------------------------

test('auto-save: updates plan title', function () {
    $plan = TreatmentPlan::factory()->draft()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
        'title' => 'Old Title',
    ]);

    $manager = new AutoSaveManager;
    $saved = $manager->save($plan, ['title' => 'New Title']);

    expect($saved->title)->toBe('New Title');
});

test('auto-save: creates new items without IDs', function () {
    $plan = TreatmentPlan::factory()->draft()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
    ]);

    $manager = new AutoSaveManager;
    $manager->save($plan, [
        'items' => [
            [
                'name' => 'Implant',
                'quantity' => 1,
                'unit_price' => 2200.00,
                'line_total' => 2200.00,
                'is_optional' => false,
            ],
        ],
    ]);

    expect($plan->items()->count())->toBe(1);
});

test('auto-save: removes items not in payload', function () {
    $plan = TreatmentPlan::factory()->draft()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
    ]);
    $item = TreatmentPlanItem::factory()->create(['treatment_plan_id' => $plan->id]);

    $manager = new AutoSaveManager;
    $manager->save($plan, ['items' => []]);

    expect($plan->items()->count())->toBe(0);
});

test('auto-save: recalculates total after save', function () {
    $plan = TreatmentPlan::factory()->draft()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
        'total_amount' => 0,
    ]);

    $manager = new AutoSaveManager;
    $manager->save($plan, [
        'items' => [
            [
                'name' => 'Crown',
                'quantity' => 1,
                'unit_price' => 850.00,
                'line_total' => 850.00,
                'is_optional' => false,
            ],
        ],
    ]);

    expect((float) $plan->fresh()->total_amount)->toBe(850.00);
});

test('auto-save: increments version when plan is sent', function () {
    $plan = TreatmentPlan::factory()->sent()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
        'version' => 1,
    ]);

    $manager = new AutoSaveManager;
    $manager->save($plan, ['title' => 'Updated Title']);

    expect($plan->fresh()->version)->toBe(2);
});
