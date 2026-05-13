<?php

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanComment;
use App\Models\TreatmentPlanEvent;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->clinic = Clinic::factory()->create(['plan_tier' => 'pro']);
    $this->patient = Patient::factory()->create(['clinic_id' => $this->clinic->id]);

    $this->plan = TreatmentPlan::withoutGlobalScopes()->create([
        'clinic_id' => $this->clinic->id,
        'patient_id' => $this->patient->id,
        'reference_number' => 'TP-2026-00001',
        'title' => 'Smile Makeover',
        'status' => 'sent',
        'currency' => 'GBP',
        'language' => 'en',
        'public_token' => Str::uuid(),
        'public_password' => null,
        'total_amount' => 3000.00,
        'deposit_amount' => 500.00,
        'viewed_count' => 0,
        'version' => 1,
        'is_template' => false,
        'sent_at' => now()->subDays(2),
        'valid_until' => now()->addDays(30),
    ]);
});

// -----------------------------------------------------------------------
// Public plan URL renders
// -----------------------------------------------------------------------

test('public plan URL renders successfully', function () {
    $this->get(route('patient.plan.show', ['token' => $this->plan->public_token]))
        ->assertOk()
        ->assertSee('Smile Makeover')
        ->assertSee($this->patient->first_name);
});

test('plan with no matching token returns 404', function () {
    $this->get(route('patient.plan.show', ['token' => Str::uuid()]))
        ->assertNotFound();
});

// -----------------------------------------------------------------------
// View tracking
// -----------------------------------------------------------------------

test('viewing plan increments viewed_count', function () {
    $this->get(route('patient.plan.show', ['token' => $this->plan->public_token]));

    expect($this->plan->fresh()->viewed_count)->toBe(1);
});

test('viewing plan creates a viewed event', function () {
    $this->get(route('patient.plan.show', ['token' => $this->plan->public_token]));

    $event = TreatmentPlanEvent::where('treatment_plan_id', $this->plan->id)
        ->where('event_type', 'viewed')
        ->first();

    expect($event)->not->toBeNull()
        ->and($event->patient_action)->toBeTrue();
});

test('viewing plan multiple times increments count each time', function () {
    $this->get(route('patient.plan.show', ['token' => $this->plan->public_token]));
    $this->get(route('patient.plan.show', ['token' => $this->plan->public_token]));

    expect($this->plan->fresh()->viewed_count)->toBe(2);
});

// -----------------------------------------------------------------------
// Accept
// -----------------------------------------------------------------------

test('accept records signature and changes status to accepted', function () {
    $this->plan->update(['status' => 'viewed', 'viewed_at' => now()->subHour()]);

    $this->post(route('patient.plan.accept', ['token' => $this->plan->public_token]), [
        'signature' => 'data:image/png;base64,iVBORw0KGgo=',
        'full_name' => $this->patient->first_name.' '.$this->patient->last_name,
    ])->assertRedirect(route('patient.plan.show', ['token' => $this->plan->public_token]));

    $fresh = $this->plan->fresh();
    expect($fresh->status)->toBe('accepted')
        ->and($fresh->patient_signature)->toBe('data:image/png;base64,iVBORw0KGgo=')
        ->and($fresh->patient_signed_at)->not->toBeNull()
        ->and($fresh->accepted_at)->not->toBeNull();
});

test('accept creates accepted event', function () {
    $this->plan->update(['status' => 'viewed', 'viewed_at' => now()->subHour()]);

    $this->post(route('patient.plan.accept', ['token' => $this->plan->public_token]), [
        'signature' => 'data:image/png;base64,iVBORw0KGgo=',
        'full_name' => 'Jane Doe',
    ]);

    $event = TreatmentPlanEvent::where('treatment_plan_id', $this->plan->id)
        ->where('event_type', 'accepted')
        ->first();

    expect($event)->not->toBeNull()
        ->and($event->patient_action)->toBeTrue();
});

test('accept validates required fields', function () {
    $this->plan->update(['status' => 'sent']);

    $this->post(route('patient.plan.accept', ['token' => $this->plan->public_token]), [])
        ->assertSessionHasErrors(['signature', 'full_name']);
});

// -----------------------------------------------------------------------
// Decline
// -----------------------------------------------------------------------

test('decline records reason and changes status to declined', function () {
    $this->plan->update(['status' => 'viewed', 'viewed_at' => now()->subHour()]);

    $this->post(route('patient.plan.decline', ['token' => $this->plan->public_token]), [
        'decline_reason' => 'Price too high for my budget.',
    ])->assertRedirect(route('patient.plan.show', ['token' => $this->plan->public_token]));

    $fresh = $this->plan->fresh();
    expect($fresh->status)->toBe('declined')
        ->and($fresh->decline_reason)->toBe('Price too high for my budget.')
        ->and($fresh->declined_at)->not->toBeNull();
});

test('decline works without a reason', function () {
    $this->plan->update(['status' => 'viewed', 'viewed_at' => now()->subHour()]);

    $this->post(route('patient.plan.decline', ['token' => $this->plan->public_token]), [])
        ->assertRedirect(route('patient.plan.show', ['token' => $this->plan->public_token]));

    expect($this->plan->fresh()->status)->toBe('declined');
});

test('decline creates declined event', function () {
    $this->plan->update(['status' => 'viewed', 'viewed_at' => now()->subHour()]);

    $this->post(route('patient.plan.decline', ['token' => $this->plan->public_token]), [
        'decline_reason' => 'Going elsewhere.',
    ]);

    $event = TreatmentPlanEvent::where('treatment_plan_id', $this->plan->id)
        ->where('event_type', 'declined')
        ->first();

    expect($event)->not->toBeNull();
});

// -----------------------------------------------------------------------
// Comments
// -----------------------------------------------------------------------

test('patient can add a comment', function () {
    $this->post(route('patient.plan.comment', ['token' => $this->plan->public_token]), [
        'content' => 'I have a question about tooth 14.',
    ])->assertRedirect();

    $comment = TreatmentPlanComment::where('treatment_plan_id', $this->plan->id)->first();
    expect($comment)->not->toBeNull()
        ->and($comment->content)->toBe('I have a question about tooth 14.')
        ->and($comment->author_type)->toBe('patient')
        ->and($comment->is_internal)->toBeFalse();
});

test('comment validates content is required', function () {
    $this->post(route('patient.plan.comment', ['token' => $this->plan->public_token]), [
        'content' => '',
    ])->assertSessionHasErrors(['content']);
});

// -----------------------------------------------------------------------
// Expired plan
// -----------------------------------------------------------------------

test('expired plan shows expired notice and does not increment view count', function () {
    $this->plan->update(['valid_until' => now()->subDays(5)]);

    $this->get(route('patient.plan.show', ['token' => $this->plan->public_token]))
        ->assertOk()
        ->assertSee('expired');

    expect($this->plan->fresh()->viewed_count)->toBe(0);
});

// -----------------------------------------------------------------------
// Password protected plan
// -----------------------------------------------------------------------

test('password-protected plan redirects to password gate', function () {
    $this->plan->update(['public_password' => 'secret123']);

    $this->get(route('patient.plan.show', ['token' => $this->plan->public_token]))
        ->assertRedirect(route('patient.plan.password', ['token' => $this->plan->public_token]));
});

test('password gate shows password form', function () {
    $this->plan->update(['public_password' => 'secret123']);

    $this->get(route('patient.plan.password', ['token' => $this->plan->public_token]))
        ->assertOk()
        ->assertSee('password');
});

test('correct password grants access', function () {
    $this->plan->update(['public_password' => bcrypt('secret123')]);

    $this->post(route('patient.plan.password.submit', ['token' => $this->plan->public_token]), [
        'password' => 'secret123',
    ])->assertRedirect(route('patient.plan.show', ['token' => $this->plan->public_token]));
});

test('incorrect password rejects access', function () {
    $this->plan->update(['public_password' => bcrypt('secret123')]);

    $this->post(route('patient.plan.password.submit', ['token' => $this->plan->public_token]), [
        'password' => 'wrongpassword',
    ])->assertSessionHasErrors(['password']);
});

// -----------------------------------------------------------------------
// Free tier watermark
// -----------------------------------------------------------------------

test('free tier clinic shows powered by Esagio', function () {
    $this->clinic->update(['plan_tier' => 'free']);

    $this->get(route('patient.plan.show', ['token' => $this->plan->public_token]))
        ->assertOk()
        ->assertSee('Esagio');
});

test('paid tier clinic does not show powered by Esagio footer', function () {
    $this->clinic->update(['plan_tier' => 'pro']);

    $response = $this->get(route('patient.plan.show', ['token' => $this->plan->public_token]));
    $response->assertOk();
    // The footer Esagio link only appears when $freeTier is true
    $content = $response->getContent();
    expect(substr_count($content, 'Powered by'))->toBe(0);
});
