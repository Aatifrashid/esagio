<?php

namespace App\Livewire\Crm;

use App\Models\CrmActivity;
use App\Models\CrmPipeline;
use App\Models\Patient;
use Livewire\Attributes\Url;
use Livewire\Component;

class PipelineKanban extends Component
{
    public CrmPipeline $pipeline;

    public array $stages = [];

    #[Url]
    public string $searchTerm = '';

    public function mount(CrmPipeline $pipeline): void
    {
        $this->pipeline = $pipeline;
        $this->loadStages();
    }

    public function loadStages(): void
    {
        $this->stages = $this->pipeline
            ->stages()
            ->with(['patients' => function ($query) {
                $query->with('assignedUser')
                    ->when($this->searchTerm, function ($q) {
                        $q->where(function ($inner) {
                            $inner->where('first_name', 'like', '%'.$this->searchTerm.'%')
                                ->orWhere('last_name', 'like', '%'.$this->searchTerm.'%')
                                ->orWhere('email', 'like', '%'.$this->searchTerm.'%');
                        });
                    });
            }])
            ->get()
            ->toArray();
    }

    public function updatedSearchTerm(): void
    {
        $this->loadStages();
    }

    public function movePatient(int $patientId, int $stageId): void
    {
        $patient = Patient::findOrFail($patientId);
        $oldStageId = $patient->pipeline_stage_id;

        $patient->update(['pipeline_stage_id' => $stageId]);

        $newStage = $this->pipeline->stages()->where('id', $stageId)->first();

        CrmActivity::create([
            'patient_id' => $patientId,
            'user_id' => auth()->id(),
            'clinic_id' => $patient->clinic_id,
            'type' => 'note',
            'subject' => 'Moved to stage: '.($newStage?->name ?? 'Unknown'),
            'description' => 'Patient moved from stage #'.$oldStageId.' to stage #'.$stageId.' via Kanban board.',
            'occurred_at' => now(),
        ]);

        $this->loadStages();
    }

    public function render()
    {
        return view('livewire.crm.pipeline-kanban');
    }
}
