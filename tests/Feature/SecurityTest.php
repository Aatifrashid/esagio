<?php

use App\Models\AuditLog;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\User;
use App\Services\Gdpr\DataExporter;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->clinic = Clinic::factory()->create();
    $this->owner = User::factory()->create(['clinic_id' => $this->clinic->id, 'role' => 'clinic_owner']);
});

test('gdpr export generates json file', function () {
    Storage::fake('local');

    $patient = Patient::factory()->create(['clinic_id' => $this->clinic->id]);
    $exporter = new DataExporter;
    $path = $exporter->exportPatientData($patient);

    expect($path)->toContain('gdpr-exports/');
    Storage::assertExists($path);

    $data = json_decode(Storage::get($path), true);
    expect($data['patient']['first_name'])->toBe($patient->first_name);
});

test('gdpr delete anonymises patient', function () {
    $patient = Patient::factory()->create([
        'clinic_id' => $this->clinic->id,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
    ]);

    $exporter = new DataExporter;
    $exporter->deletePatientData($patient);

    $patient->refresh();
    expect($patient->first_name)->toBe('[deleted]');
    expect($patient->last_name)->toBe('[deleted]');
    expect($patient->email)->toContain('deleted-');
});

test('gdpr export endpoint requires clinic owner', function () {
    $patient = Patient::factory()->create(['clinic_id' => $this->clinic->id]);
    $viewer = User::factory()->create(['clinic_id' => $this->clinic->id, 'role' => 'viewer']);

    $this->actingAs($viewer)
        ->get(route('gdpr.export', $patient))
        ->assertForbidden();
});

test('gdpr export endpoint works for owner', function () {
    Storage::fake('local');
    $patient = Patient::factory()->create(['clinic_id' => $this->clinic->id]);

    $this->actingAs($this->owner)
        ->get(route('gdpr.export', $patient))
        ->assertOk();
});

test('audit log model belongs to clinic', function () {
    $log = AuditLog::create([
        'clinic_id' => $this->clinic->id,
        'user_id' => $this->owner->id,
        'action' => 'plan.sent',
        'model_type' => 'TreatmentPlan',
        'model_id' => 1,
        'ip_address' => '127.0.0.1',
    ]);

    expect($log->id)->not->toBeNull();
    expect($log->action)->toBe('plan.sent');
});
