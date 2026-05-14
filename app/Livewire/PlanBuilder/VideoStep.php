<?php

namespace App\Livewire\PlanBuilder;

use App\Models\ClinicTeamMember;
use App\Models\TreatmentPlan;
use App\Models\VideoConsultationSession;
use Carbon\Carbon;
use Livewire\Component;

class VideoStep extends Component
{
    public TreatmentPlan $plan;

    public string $scheduledDate = '';

    public string $scheduledTime = '';

    public int $duration = 30;

    public ?int $conductedBy = null;

    public ?VideoConsultationSession $existingSession = null;

    public function mount(TreatmentPlan $plan): void
    {
        $this->plan = $plan;
        $this->existingSession = $plan->videoConsultation;
        $this->conductedBy = auth()->id();

        if ($this->existingSession) {
            $dt = $this->existingSession->scheduled_for;
            $this->scheduledDate = $dt?->format('Y-m-d') ?? '';
            $this->scheduledTime = $dt?->format('H:i') ?? '';
            $this->duration = $this->existingSession->duration_minutes;
            $this->conductedBy = $this->existingSession->conducted_by;
        }
    }

    public function scheduleConsultation(): void
    {
        $this->validate([
            'scheduledDate' => 'required|date|after_or_equal:today',
            'scheduledTime' => 'required',
            'duration' => 'required|integer|min:15|max:120',
        ]);

        $scheduledFor = Carbon::parse($this->scheduledDate.' '.$this->scheduledTime);

        $data = [
            'clinic_id' => $this->plan->clinic_id,
            'treatment_plan_id' => $this->plan->id,
            'patient_id' => $this->plan->patient_id,
            'scheduled_for' => $scheduledFor,
            'duration_minutes' => $this->duration,
            'status' => 'scheduled',
            'conducted_by' => $this->conductedBy ?? auth()->id(),
        ];

        if ($this->existingSession) {
            $this->existingSession->update($data);
        } else {
            $this->existingSession = VideoConsultationSession::create($data);
        }

        $this->dispatch('plan-updated')->to(Builder::class);
        $this->dispatch('consultation-scheduled');
    }

    public function render()
    {
        $teamMembers = ClinicTeamMember::where('clinic_id', $this->plan->clinic_id)
            ->with('user')
            ->get();

        return view('livewire.plan-builder.video-step', [
            'teamMembers' => $teamMembers,
        ]);
    }
}
