<?php

namespace App\Livewire\PlanBuilder;

use App\Models\TreatmentPlan;
use App\Services\PlanBuilder\AutoSaveManager;
use App\Services\PlanBuilder\BuilderStateMachine;
use Livewire\Attributes\On;
use Livewire\Component;

class Builder extends Component
{
    public TreatmentPlan $plan;

    public int $currentStep = 1;

    public bool $showPreview = true;

    public bool $showCommandPalette = false;

    public string $searchQuery = '';

    public string $saveStatus = 'saved';

    public ?string $lastSavedAt = null;

    public array $stepLabels = [
        1 => 'Patient',
        2 => 'Diagnosis',
        3 => 'Treatment Plan',
        4 => 'Visuals',
        5 => 'Content',
        6 => 'Video Consultation',
        7 => 'Settings',
        8 => 'Review and Send',
    ];

    public function mount(TreatmentPlan $plan): void
    {
        $this->plan = $plan->load([
            'patient',
            'items',
            'phases',
            'sections',
            'toothCharts',
            'videoConsultation',
            'createdBy',
            'assignedTo',
        ]);

        $this->lastSavedAt = $plan->updated_at?->toIso8601String();
    }

    public function goToStep(int $step): void
    {
        if ($step >= 1 && $step <= 8) {
            $this->currentStep = $step;
        }
    }

    public function nextStep(): void
    {
        if ($this->currentStep < 8) {
            $this->currentStep++;
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    #[On('plan-updated')]
    public function autoSave(array $data = []): void
    {
        $this->saveStatus = 'saving';

        try {
            $manager = app(AutoSaveManager::class);
            $this->plan = $manager->save($this->plan, $data ?: ['title' => $this->plan->title]);
            $this->saveStatus = 'saved';
            $this->lastSavedAt = now()->toIso8601String();
        } catch (\Throwable $e) {
            $this->saveStatus = 'error';
        }
    }

    public function forceSave(): void
    {
        $this->autoSave();
    }

    public function togglePreview(): void
    {
        $this->showPreview = ! $this->showPreview;
    }

    public function openCommandPalette(): void
    {
        $this->showCommandPalette = true;
    }

    public function closeCommandPalette(): void
    {
        $this->showCommandPalette = false;
        $this->searchQuery = '';
    }

    public function sendPlan(): void
    {
        $stateMachine = app(BuilderStateMachine::class);

        if (! $stateMachine->canTransition($this->plan, 'sent')) {
            $this->addError('send', 'This plan cannot be sent in its current state.');

            return;
        }

        $this->plan = $stateMachine->transition($this->plan, 'sent');
        $this->dispatch('plan-sent');
        $this->goToStep(8);
    }

    public function render()
    {
        return view('livewire.plan-builder.builder')
            ->layout('layouts.app');
    }
}
