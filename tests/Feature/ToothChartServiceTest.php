<?php

use App\Models\Clinic;
use App\Models\TreatmentPlan;
use App\Models\User;
use App\Services\ToothChart3D\ChartService;

beforeEach(function () {
    $this->clinic = Clinic::factory()->create();
    $this->user = User::factory()->clinicOwner()->create(['clinic_id' => $this->clinic->id]);
    $this->actingAs($this->user);
    $this->plan = TreatmentPlan::factory()->create(['clinic_id' => $this->clinic->id]);
    $this->service = new ChartService;
});

test('chart service returns all 32 adult teeth', function () {
    $data = $this->service->getChartData($this->plan);
    expect($data)->toHaveCount(32);
});

test('set condition adds condition to tooth', function () {
    $chart = $this->service->setCondition($this->plan, '11', 'missing');
    expect($chart->conditions)->toContain('missing');
});

test('set condition does not duplicate', function () {
    $this->service->setCondition($this->plan, '11', 'missing');
    $chart = $this->service->setCondition($this->plan, '11', 'missing');
    expect(array_count_values($chart->conditions)['missing'])->toBe(1);
});

test('remove condition removes from tooth', function () {
    $this->service->setCondition($this->plan, '11', 'missing');
    $this->service->setCondition($this->plan, '11', 'decayed');
    $chart = $this->service->removeCondition($this->plan, '11', 'missing');
    expect($chart->conditions)->not->toContain('missing')
        ->and($chart->conditions)->toContain('decayed');
});

test('add planned treatment links item to tooth', function () {
    $this->service->addPlannedTreatment($this->plan, '14', 99);
    $chart = $this->plan->toothCharts()->where('tooth_number', '14')->first();
    expect($chart->planned_treatments)->toContain(99);
});

test('svg data returns positioning for all teeth', function () {
    $svgData = $this->service->toSvgData($this->plan);
    expect($svgData)->toHaveCount(32);
    expect($svgData[0])->toHaveKeys(['number', 'x', 'y', 'fill', 'conditions', 'label']);
});

test('fdi to universal notation converts correctly', function () {
    expect($this->service->convertNotation(11, 'universal'))->toBe('8')
        ->and($this->service->convertNotation(18, 'universal'))->toBe('1')
        ->and($this->service->convertNotation(21, 'universal'))->toBe('9')
        ->and($this->service->convertNotation(31, 'universal'))->toBe('24');
});

test('get conditions for arch filters correctly', function () {
    $this->service->setCondition($this->plan, '11', 'missing');
    $this->service->setCondition($this->plan, '21', 'decayed');
    $this->service->setCondition($this->plan, '31', 'crowned');

    $upper = $this->service->getConditionsForArch($this->plan, 'upper');
    $lower = $this->service->getConditionsForArch($this->plan, 'lower');

    expect($upper)->toHaveCount(2)
        ->and($lower)->toHaveCount(1);
});
