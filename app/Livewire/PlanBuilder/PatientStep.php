<?php

namespace App\Livewire\PlanBuilder;

use App\Models\Patient;
use App\Models\TreatmentPlan;
use Livewire\Component;

class PatientStep extends Component
{
    public TreatmentPlan $plan;

    public string $searchTerm = '';

    public array $patients = [];

    public ?Patient $selectedPatient = null;

    public bool $showNewPatientForm = false;

    public string $newFirstName = '';

    public string $newLastName = '';

    public string $newEmail = '';

    public string $newPhone = '';

    public function mount(TreatmentPlan $plan): void
    {
        $this->plan = $plan;
        $this->selectedPatient = $plan->patient;
    }

    public function updatedSearchTerm(): void
    {
        $this->searchPatients();
    }

    public function searchPatients(): void
    {
        if (strlen($this->searchTerm) < 2) {
            $this->patients = [];

            return;
        }

        $term = '%'.$this->searchTerm.'%';

        $this->patients = Patient::where('clinic_id', $this->plan->clinic_id)
            ->where(function ($q) use ($term) {
                $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$term])
                    ->orWhere('email', 'like', $term)
                    ->orWhere('phone', 'like', $term)
                    ->orWhere('reference_code', 'like', $term);
            })
            ->limit(10)
            ->get(['id', 'first_name', 'last_name', 'email', 'phone', 'reference_code'])
            ->toArray();
    }

    public function selectPatient(int $id): void
    {
        $patient = Patient::where('clinic_id', $this->plan->clinic_id)->findOrFail($id);

        $this->plan->update(['patient_id' => $patient->id]);
        $this->plan->refresh();

        $this->selectedPatient = $patient;
        $this->searchTerm = '';
        $this->patients = [];

        $this->dispatch('plan-updated', ['patient_id' => $patient->id]);
    }

    public function createPatient(): void
    {
        $this->validate([
            'newFirstName' => 'required|string|max:100',
            'newLastName' => 'required|string|max:100',
            'newEmail' => 'nullable|email|max:200',
            'newPhone' => 'nullable|string|max:30',
        ]);

        $patient = Patient::create([
            'clinic_id' => $this->plan->clinic_id,
            'first_name' => $this->newFirstName,
            'last_name' => $this->newLastName,
            'email' => $this->newEmail ?: null,
            'phone' => $this->newPhone ?: null,
            'status' => 'lead',
            'created_by' => auth()->id(),
        ]);

        $this->selectPatient($patient->id);
        $this->showNewPatientForm = false;
        $this->reset(['newFirstName', 'newLastName', 'newEmail', 'newPhone']);
    }

    public function removePatient(): void
    {
        $this->plan->update(['patient_id' => null]);
        $this->selectedPatient = null;
        $this->dispatch('plan-updated', []);
    }

    public function render()
    {
        return view('livewire.plan-builder.patient-step');
    }
}
