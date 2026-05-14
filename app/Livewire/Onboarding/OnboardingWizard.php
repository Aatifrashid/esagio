<?php

namespace App\Livewire\Onboarding;

use App\Models\Clinic;
use App\Models\ClinicTeamMember;
use App\Models\Patient;
use App\Models\TreatmentPlan;
use App\Models\TreatmentTemplate;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class OnboardingWizard extends Component
{
    use WithFileUploads;

    public int $currentStep = 1;

    public int $totalSteps = 5;

    public array $stepLabels = [
        1 => 'Clinic Profile',
        2 => 'Branding',
        3 => 'Team Members',
        4 => 'First Template',
        5 => 'First Patient',
    ];

    public array $optionalSteps = [2, 3];

    // Step 1: Clinic Profile
    #[Validate('required|string|max:255')]
    public string $clinicName = '';

    #[Validate('required|email|max:255')]
    public string $clinicEmail = '';

    #[Validate('required|string|max:50')]
    public string $clinicPhone = '';

    #[Validate('required|string|max:2')]
    public string $clinicCountry = 'GB';

    #[Validate('required|string|max:64')]
    public string $clinicTimezone = 'Europe/London';

    #[Validate('required|string|max:3')]
    public string $clinicCurrency = 'GBP';

    #[Validate('required|string|max:5')]
    public string $clinicLanguage = 'en';

    // Step 2: Branding
    public $logoUpload = null;

    #[Validate('nullable|string|max:7')]
    public string $primaryColour = '#0A2540';

    #[Validate('nullable|string|max:7')]
    public string $accentColour = '#E8663D';

    #[Validate('nullable|string|max:100')]
    public string $fontChoice = 'Inter';

    // Step 3: Team Members
    public array $teamMembers = [];

    // Step 4: First Template
    #[Validate('nullable|string|max:255')]
    public string $templateName = '';

    public ?int $selectedSystemTemplate = null;

    public array $systemTemplates = [];

    // Step 5: First Patient
    #[Validate('required_if:currentStep,5|string|max:255')]
    public string $patientFirstName = '';

    #[Validate('required_if:currentStep,5|string|max:255')]
    public string $patientLastName = '';

    #[Validate('nullable|email|max:255')]
    public string $patientEmail = '';

    #[Validate('nullable|string|max:50')]
    public string $patientPhone = '';

    #[Validate('nullable|date')]
    public ?string $patientDob = null;

    public function mount(): void
    {
        $clinic = $this->getClinic();

        if ($clinic->settings && isset($clinic->settings['onboarding_step'])) {
            $this->currentStep = min((int) $clinic->settings['onboarding_step'], $this->totalSteps);
        }

        // Pre-fill from existing clinic data
        $this->clinicName = $clinic->name ?? '';
        $this->clinicEmail = $clinic->email ?? '';
        $this->clinicPhone = $clinic->phone ?? '';
        $this->clinicCountry = $clinic->country ?? 'GB';
        $this->clinicTimezone = $clinic->timezone ?? 'Europe/London';
        $this->clinicCurrency = $clinic->currency_default ?? 'GBP';
        $this->clinicLanguage = $clinic->language_default ?? 'en';

        if ($clinic->branding) {
            $this->primaryColour = $clinic->branding->primary_colour ?? '#0A2540';
            $this->accentColour = $clinic->branding->accent_colour ?? '#E8663D';
            $this->fontChoice = $clinic->branding->font_family ?? 'Inter';
        }

        $this->systemTemplates = TreatmentTemplate::query()
            ->where('is_system', true)
            ->pluck('name', 'id')
            ->toArray();
    }

    public function getClinic(): Clinic
    {
        $user = auth()->user();

        if ($user->clinic) {
            return $user->clinic;
        }

        if ($user->clinic_id) {
            return Clinic::findOrFail($user->clinic_id);
        }

        $clinic = Clinic::create([
            'name' => $user->name . "'s Clinic",
            'slug' => \Str::slug($user->name . '-clinic-' . $user->id),
            'email' => $user->email,
        ]);

        $user->update(['clinic_id' => $clinic->id]);

        return $clinic;
    }

    public function nextStep(): void
    {
        $this->saveCurrentStep();

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
            $this->persistStep();
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function skipStep(): void
    {
        if (in_array($this->currentStep, $this->optionalSteps)) {
            if ($this->currentStep < $this->totalSteps) {
                $this->currentStep++;
                $this->persistStep();
            }
        }
    }

    public function goToStep(int $step): void
    {
        if ($step >= 1 && $step <= $this->totalSteps && $step <= $this->currentStep) {
            $this->currentStep = $step;
        }
    }

    public function addTeamMember(): void
    {
        if (count($this->teamMembers) < 3) {
            $this->teamMembers[] = [
                'first_name' => '',
                'last_name' => '',
                'role' => 'dentist',
                'photo' => null,
            ];
        }
    }

    public function removeTeamMember(int $index): void
    {
        unset($this->teamMembers[$index]);
        $this->teamMembers = array_values($this->teamMembers);
    }

    public function completeOnboarding(): void
    {
        $this->saveCurrentStep();

        $clinic = $this->getClinic();
        $settings = $clinic->settings ?? [];
        $settings['onboarding_completed_at'] = now()->toIso8601String();
        $settings['onboarding_step'] = $this->totalSteps;
        $clinic->update(['settings' => $settings]);

        $this->redirect(route('dashboard'));
    }

    protected function saveCurrentStep(): void
    {
        match ($this->currentStep) {
            1 => $this->saveClinicProfile(),
            2 => $this->saveBranding(),
            3 => $this->saveTeamMembers(),
            4 => $this->saveFirstTemplate(),
            5 => $this->saveFirstPatient(),
            default => null,
        };
    }

    protected function saveClinicProfile(): void
    {
        $this->validate([
            'clinicName' => 'required|string|max:255',
            'clinicEmail' => 'required|email|max:255',
            'clinicPhone' => 'required|string|max:50',
            'clinicCountry' => 'required|string|max:2',
            'clinicTimezone' => 'required|string|max:64',
            'clinicCurrency' => 'required|string|max:3',
            'clinicLanguage' => 'required|string|max:5',
        ]);

        $this->getClinic()->update([
            'name' => $this->clinicName,
            'email' => $this->clinicEmail,
            'phone' => $this->clinicPhone,
            'country' => $this->clinicCountry,
            'timezone' => $this->clinicTimezone,
            'currency_default' => $this->clinicCurrency,
            'language_default' => $this->clinicLanguage,
        ]);
    }

    protected function saveBranding(): void
    {
        $clinic = $this->getClinic();

        $data = [
            'primary_colour' => $this->primaryColour,
            'accent_colour' => $this->accentColour,
            'font_family' => $this->fontChoice,
        ];

        if ($this->logoUpload) {
            $this->validate(['logoUpload' => 'image|max:2048']);
            $path = $this->logoUpload->store("clinics/{$clinic->id}/branding", 'public');
            $data['logo_path'] = $path;
            $clinic->update(['logo_path' => $path]);
        }

        $clinic->branding()->updateOrCreate(
            ['clinic_id' => $clinic->id],
            $data
        );
    }

    protected function saveTeamMembers(): void
    {
        $clinic = $this->getClinic();

        foreach ($this->teamMembers as $index => $member) {
            if (empty($member['first_name'])) {
                continue;
            }

            ClinicTeamMember::create([
                'clinic_id' => $clinic->id,
                'first_name' => $member['first_name'],
                'last_name' => $member['last_name'] ?? '',
                'role' => $member['role'] ?? 'dentist',
                'sort_order' => $index,
                'is_active' => true,
            ]);
        }
    }

    protected function saveFirstTemplate(): void
    {
        if ($this->selectedSystemTemplate) {
            return; // System template selected, nothing to create
        }

        if (empty($this->templateName)) {
            return;
        }

        $clinic = $this->getClinic();

        TreatmentPlan::create([
            'clinic_id' => $clinic->id,
            'title' => $this->templateName,
            'is_template' => true,
            'template_name' => $this->templateName,
            'status' => 'draft',
            'currency' => $clinic->currency_default ?? 'GBP',
            'created_by' => auth()->id(),
        ]);
    }

    protected function saveFirstPatient(): void
    {
        $this->validate([
            'patientFirstName' => 'required|string|max:255',
            'patientLastName' => 'required|string|max:255',
            'patientEmail' => 'nullable|email|max:255',
            'patientPhone' => 'nullable|string|max:50',
            'patientDob' => 'nullable|date',
        ]);

        $clinic = $this->getClinic();

        Patient::withoutGlobalScopes()->create([
            'clinic_id' => $clinic->id,
            'first_name' => $this->patientFirstName,
            'last_name' => $this->patientLastName,
            'email' => $this->patientEmail ?: null,
            'phone' => $this->patientPhone ?: null,
            'date_of_birth' => $this->patientDob ?: null,
            'status' => 'active',
            'created_by' => auth()->id(),
        ]);
    }

    protected function persistStep(): void
    {
        $clinic = $this->getClinic();
        $settings = $clinic->settings ?? [];
        $settings['onboarding_step'] = $this->currentStep;
        $clinic->update(['settings' => $settings]);
    }

    public function render()
    {
        return view('livewire.onboarding.onboarding-wizard')
            ->layout('layouts.app', ['title' => 'Set Up Your Clinic']);
    }
}
