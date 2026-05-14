<?php

use App\Models\Clinic;
use App\Models\User;
use App\Models\WebhookEndpoint;
use App\Models\WebhookLog;
use App\Services\Integrations\WebhookDispatcher;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->clinic = Clinic::factory()->create();
    $this->user = User::factory()->create(['clinic_id' => $this->clinic->id, 'role' => 'clinic_owner']);
});

test('webhook dispatcher sends to matching endpoints', function () {
    Http::fake(['*' => Http::response('ok', 200)]);

    $endpoint = WebhookEndpoint::create([
        'clinic_id' => $this->clinic->id,
        'url' => 'https://example.com/webhook',
        'secret' => 'test-secret-key',
        'events' => ['plan.sent', 'plan.accepted'],
        'is_active' => true,
    ]);

    $dispatcher = new WebhookDispatcher;
    $sent = $dispatcher->dispatch($this->clinic, 'plan.sent', ['plan_id' => 1]);

    expect($sent)->toBe(1);
    expect(WebhookLog::count())->toBe(1);
    expect(WebhookLog::first()->response_code)->toBe(200);
});

test('webhook dispatcher skips non-matching events', function () {
    Http::fake();

    WebhookEndpoint::create([
        'clinic_id' => $this->clinic->id,
        'url' => 'https://example.com/webhook',
        'secret' => 'test-secret',
        'events' => ['plan.accepted'],
        'is_active' => true,
    ]);

    $dispatcher = new WebhookDispatcher;
    $sent = $dispatcher->dispatch($this->clinic, 'patient.created', ['patient_id' => 1]);

    expect($sent)->toBe(0);
    Http::assertNothingSent();
});

test('webhook dispatcher skips inactive endpoints', function () {
    Http::fake();

    WebhookEndpoint::create([
        'clinic_id' => $this->clinic->id,
        'url' => 'https://example.com/webhook',
        'secret' => 'test-secret',
        'events' => ['plan.sent'],
        'is_active' => false,
    ]);

    $dispatcher = new WebhookDispatcher;
    $sent = $dispatcher->dispatch($this->clinic, 'plan.sent', ['data' => true]);

    expect($sent)->toBe(0);
});

test('webhook includes hmac signature', function () {
    Http::fake(['*' => Http::response('ok', 200)]);

    $endpoint = WebhookEndpoint::create([
        'clinic_id' => $this->clinic->id,
        'url' => 'https://example.com/webhook',
        'secret' => 'my-secret',
        'events' => ['plan.created'],
        'is_active' => true,
    ]);

    $dispatcher = new WebhookDispatcher;
    $dispatcher->dispatch($this->clinic, 'plan.created', ['id' => 5]);

    Http::assertSent(function ($request) {
        return $request->hasHeader('X-Esagio-Signature')
            && $request->hasHeader('X-Esagio-Event');
    });
});

test('webhook endpoint listensTo method works', function () {
    $endpoint = new WebhookEndpoint(['events' => ['plan.sent', 'plan.accepted']]);

    expect($endpoint->listensTo('plan.sent'))->toBeTrue();
    expect($endpoint->listensTo('patient.created'))->toBeFalse();
});
