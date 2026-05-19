<?php

namespace App\Livewire\Crm;

use App\Models\CrmActivity;
use App\Models\CrmPipeline;
use App\Models\Patient;
use Livewire\Attributes\Url;
use Livewire\Component;

class OpportunitiesBoard extends Component
{
    #[Url]
    public ?int $selectedPipelineId = null;

    public string $viewMode = 'kanban';

    #[Url]
    public string $searchTerm = '';

    public array $stages = [];

    public array $pipelines = [];

    public int $totalOpportunities = 0;

    public function mount(): void
    {
        $clinicId = auth()->user()->clinic_id;

        $this->pipelines = CrmPipeline::where('clinic_id', $clinicId)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'is_default'])
            ->toArray();

        if (! $this->selectedPipelineId) {
            $default = collect($this->pipelines)->firstWhere('is_default', true);
            $this->selectedPipelineId = $default['id'] ?? ($this->pipelines[0]['id'] ?? null);
        }

        $this->loadStages();
    }

    public function loadStages(): void
    {
        if (! $this->selectedPipelineId) {
            $this->stages = [];
            $this->totalOpportunities = 0;

            return;
        }

        $pipeline = CrmPipeline::where('clinic_id', auth()->user()->clinic_id)
            ->findOrFail($this->selectedPipelineId);

        $this->stages = $pipeline
            ->stages()
            ->with(['patients' => function ($query) {
                $query->with('assignedUser')
                    ->when($this->searchTerm, function ($q) {
                        $q->where(function ($inner) {
                            $inner->where('first_name', 'like', '%' . $this->searchTerm . '%')
                                ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%')
                                ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
                        });
                    });
            }])
            ->get()
            ->toArray();

        $this->totalOpportunities = collect($this->stages)->sum(fn ($s) => count($s['patients'] ?? []));
    }

    public function switchPipeline($id): void
    {
        $this->selectedPipelineId = (int) $id;
        $this->loadStages();
    }

    public function updatedSearchTerm(): void
    {
        $this->loadStages();
    }

    public function movePatient(int $patientId, int $stageId): void
    {
        $patient = Patient::where('clinic_id', auth()->user()->clinic_id)->findOrFail($patientId);
        $oldStageId = $patient->pipeline_stage_id;

        $patient->update(['pipeline_stage_id' => $stageId]);

        $pipeline = CrmPipeline::where('clinic_id', auth()->user()->clinic_id)
            ->findOrFail($this->selectedPipelineId);

        $newStage = $pipeline->stages()->where('id', $stageId)->first();

        CrmActivity::create([
            'patient_id' => $patientId,
            'user_id' => auth()->id(),
            'clinic_id' => $patient->clinic_id,
            'type' => 'note',
            'subject' => 'Moved to stage: ' . ($newStage?->name ?? 'Unknown'),
            'description' => 'Patient moved from stage #' . $oldStageId . ' to stage #' . $stageId . ' via Opportunities board.',
            'occurred_at' => now(),
        ]);

        $this->loadStages();
    }

    public function render()
    {
        return view('livewire.crm.opportunities-board');
    }
}
