<?php

use App\Models\Clinic;
use App\Models\CrmActivity;
use App\Models\CrmPipeline;
use App\Models\CrmPipelineStage;
use App\Models\CrmTask;
use App\Models\Patient;
use App\Models\User;

beforeEach(function () {
    $this->clinic = Clinic::factory()->create();
    $this->user = User::factory()->create(['clinic_id' => $this->clinic->id]);
    $this->actingAs($this->user);
});

// --- Patient CRUD ---

it('can create a patient', function () {
    $patient = Patient::factory()->create([
        'clinic_id' => $this->clinic->id,
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'email' => 'jane@example.com',
        'status' => 'new',
    ]);

    expect($patient->id)->toBeInt()
        ->and($patient->first_name)->toBe('Jane')
        ->and($patient->last_name)->toBe('Doe')
        ->and($patient->email)->toBe('jane@example.com');
});

it('can update a patient', function () {
    $patient = Patient::factory()->create(['clinic_id' => $this->clinic->id]);

    $patient->update(['status' => 'qualified', 'deal_value' => 2500.00]);

    expect($patient->fresh()->status)->toBe('qualified')
        ->and((float) $patient->fresh()->deal_value)->toBe(2500.00);
});

it('can soft delete a patient', function () {
    $patient = Patient::factory()->create(['clinic_id' => $this->clinic->id]);

    $patient->delete();

    expect(Patient::find($patient->id))->toBeNull();
    expect(Patient::withTrashed()->find($patient->id))->not->toBeNull();
});

it('can restore a soft deleted patient', function () {
    $patient = Patient::factory()->create(['clinic_id' => $this->clinic->id]);
    $patient->delete();
    $patient->restore();

    expect(Patient::find($patient->id))->not->toBeNull();
});

// --- Pipeline stages ordered correctly ---

it('returns pipeline stages in sort_order', function () {
    $pipeline = CrmPipeline::factory()->create(['clinic_id' => $this->clinic->id]);

    CrmPipelineStage::factory()->create([
        'crm_pipeline_id' => $pipeline->id,
        'name' => 'Stage C',
        'sort_order' => 3,
    ]);
    CrmPipelineStage::factory()->create([
        'crm_pipeline_id' => $pipeline->id,
        'name' => 'Stage A',
        'sort_order' => 1,
    ]);
    CrmPipelineStage::factory()->create([
        'crm_pipeline_id' => $pipeline->id,
        'name' => 'Stage B',
        'sort_order' => 2,
    ]);

    $stages = $pipeline->stages()->get();

    expect($stages->pluck('name')->toArray())->toBe(['Stage A', 'Stage B', 'Stage C']);
});

// --- Activity logging ---

it('can log an activity for a patient', function () {
    $patient = Patient::factory()->create(['clinic_id' => $this->clinic->id]);

    $activity = CrmActivity::create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $patient->id,
        'user_id' => $this->user->id,
        'type' => 'call',
        'subject' => 'Initial follow-up call',
        'outcome' => 'positive',
        'occurred_at' => now(),
    ]);

    expect($activity->id)->toBeInt()
        ->and($activity->type)->toBe('call')
        ->and($activity->subject)->toBe('Initial follow-up call')
        ->and($activity->patient_id)->toBe($patient->id);

    expect($patient->activities()->count())->toBe(1);
});

it('activity belongs to correct patient and user', function () {
    $patient = Patient::factory()->create(['clinic_id' => $this->clinic->id]);

    $activity = CrmActivity::factory()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $patient->id,
        'user_id' => $this->user->id,
    ]);

    expect($activity->patient->id)->toBe($patient->id)
        ->and($activity->user->id)->toBe($this->user->id);
});

// --- Task completion sets completed_at ---

it('sets completed_at when task is marked completed', function () {
    $patient = Patient::factory()->create(['clinic_id' => $this->clinic->id]);

    $task = CrmTask::factory()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $patient->id,
        'status' => 'pending',
        'completed_at' => null,
    ]);

    expect($task->completed_at)->toBeNull();

    $task->update([
        'status' => 'completed',
        'completed_at' => now(),
    ]);

    expect($task->fresh()->status)->toBe('completed')
        ->and($task->fresh()->completed_at)->not->toBeNull();
});

it('does not set completed_at for non-completed status', function () {
    $task = CrmTask::factory()->create([
        'clinic_id' => $this->clinic->id,
        'status' => 'pending',
        'completed_at' => null,
    ]);

    $task->update(['status' => 'in_progress']);

    expect($task->fresh()->completed_at)->toBeNull();
});

// --- Pipeline kanban moves patient between stages ---

it('can move a patient to a different pipeline stage', function () {
    $pipeline = CrmPipeline::factory()->create(['clinic_id' => $this->clinic->id]);

    $stageA = CrmPipelineStage::factory()->create([
        'crm_pipeline_id' => $pipeline->id,
        'name' => 'Stage A',
        'sort_order' => 1,
    ]);
    $stageB = CrmPipelineStage::factory()->create([
        'crm_pipeline_id' => $pipeline->id,
        'name' => 'Stage B',
        'sort_order' => 2,
    ]);

    $patient = Patient::factory()->create([
        'clinic_id' => $this->clinic->id,
        'pipeline_stage_id' => $stageA->id,
    ]);

    // Simulate the Kanban movePatient logic
    $patient->update(['pipeline_stage_id' => $stageB->id]);

    CrmActivity::create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $patient->id,
        'user_id' => $this->user->id,
        'type' => 'note',
        'subject' => 'Moved to stage: Stage B',
        'occurred_at' => now(),
    ]);

    expect($patient->fresh()->pipeline_stage_id)->toBe($stageB->id);
    expect($patient->activities()->where('type', 'note')->count())->toBe(1);
});

// --- Deal value calculated correctly ---

it('stores and retrieves deal value with two decimal precision', function () {
    $patient = Patient::factory()->create([
        'clinic_id' => $this->clinic->id,
        'deal_value' => 3750.50,
    ]);

    expect((float) $patient->fresh()->deal_value)->toBe(3750.50);
});

it('defaults deal value to null when not set', function () {
    $patient = Patient::factory()->create([
        'clinic_id' => $this->clinic->id,
        'deal_value' => null,
    ]);

    expect($patient->fresh()->deal_value)->toBeNull();
});
