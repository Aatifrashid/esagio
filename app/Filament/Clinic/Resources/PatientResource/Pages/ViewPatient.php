<?php

namespace App\Filament\Clinic\Resources\PatientResource\Pages;

use App\Filament\Clinic\Resources\PatientResource;
use App\Models\CrmActivity;
use App\Models\CrmTask;
use App\Models\Patient;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;

class ViewPatient extends Page
{
    protected static string $resource = PatientResource::class;

    protected static string $view = 'filament.clinic.resources.patient-resource.pages.view-patient';

    public Patient $patient;

    // Editable fields
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $phone = '';
    public string $whatsapp_number = '';
    public string $country_of_residence = '';
    public string $city = '';
    public ?string $date_of_birth = null;
    public string $gender = '';
    public string $status = 'new';
    public string $source = '';
    public ?int $assigned_to = null;
    public string $deal_value = '';
    public ?int $pipeline_stage_id = null;
    public string $notes = '';
    public string $tags_string = '';

    // Activity log form
    public string $activity_type = 'note';
    public string $activity_subject = '';
    public string $activity_description = '';
    public string $activity_outcome = '';

    // Task form
    public string $task_title = '';
    public string $task_description = '';
    public ?string $task_due_date = null;
    public string $task_priority = 'medium';

    public function mount(int|string $record): void
    {
        $this->patient = Patient::where('clinic_id', auth()->user()->clinic_id)
            ->with(['assignedUser', 'pipelineStage.pipeline', 'activities.user', 'tasks.assignedUser', 'treatmentPlans', 'conversations.messages'])
            ->findOrFail($record);

        $this->fillFromPatient();
    }

    protected function fillFromPatient(): void
    {
        $this->first_name = $this->patient->first_name ?? '';
        $this->last_name = $this->patient->last_name ?? '';
        $this->email = $this->patient->email ?? '';
        $this->phone = $this->patient->phone ?? '';
        $this->whatsapp_number = $this->patient->whatsapp_number ?? '';
        $this->country_of_residence = $this->patient->country_of_residence ?? '';
        $this->city = $this->patient->city ?? '';
        $this->date_of_birth = $this->patient->date_of_birth?->format('Y-m-d');
        $this->gender = $this->patient->gender ?? '';
        $this->status = $this->patient->status ?? 'new';
        $this->source = $this->patient->source ?? '';
        $this->assigned_to = $this->patient->assigned_to;
        $this->deal_value = $this->patient->deal_value ?? '';
        $this->pipeline_stage_id = $this->patient->pipeline_stage_id;
        $this->notes = $this->patient->notes ?? '';
        $this->tags_string = is_array($this->patient->tags) ? implode(', ', $this->patient->tags) : ($this->patient->tags ?? '');
    }

    public function saveDetails(): void
    {
        $this->patient->update([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email ?: null,
            'phone' => $this->phone ?: null,
            'whatsapp_number' => $this->whatsapp_number ?: null,
            'country_of_residence' => $this->country_of_residence ?: null,
            'city' => $this->city ?: null,
            'date_of_birth' => $this->date_of_birth ?: null,
            'gender' => $this->gender ?: null,
            'status' => $this->status,
            'source' => $this->source ?: null,
            'assigned_to' => $this->assigned_to,
            'deal_value' => $this->deal_value ?: null,
            'pipeline_stage_id' => $this->pipeline_stage_id,
            'notes' => $this->notes ?: null,
            'tags' => $this->tags_string ? array_map('trim', explode(',', $this->tags_string)) : null,
        ]);

        $this->patient->refresh();

        $this->dispatch('notify', type: 'success', message: 'Patient details saved.');
    }

    public function logActivity(): void
    {
        $this->validate([
            'activity_subject' => 'required|string|max:255',
            'activity_type' => 'required',
        ]);

        $this->patient->activities()->create([
            'user_id' => auth()->id(),
            'clinic_id' => $this->patient->clinic_id,
            'type' => $this->activity_type,
            'subject' => $this->activity_subject,
            'description' => $this->activity_description ?: null,
            'outcome' => $this->activity_outcome ?: null,
            'occurred_at' => now(),
        ]);

        $this->patient->update(['last_contacted_at' => now()]);
        $this->patient->load('activities.user');

        $this->activity_subject = '';
        $this->activity_description = '';
        $this->activity_outcome = '';
        $this->activity_type = 'note';

        $this->dispatch('notify', type: 'success', message: 'Activity logged.');
    }

    public function createTask(): void
    {
        $this->validate([
            'task_title' => 'required|string|max:255',
        ]);

        $this->patient->tasks()->create([
            'clinic_id' => $this->patient->clinic_id,
            'created_by' => auth()->id(),
            'assigned_to' => auth()->id(),
            'title' => $this->task_title,
            'description' => $this->task_description ?: null,
            'due_date' => $this->task_due_date ?: null,
            'priority' => $this->task_priority,
            'status' => 'pending',
        ]);

        $this->patient->load('tasks.assignedUser');

        $this->task_title = '';
        $this->task_description = '';
        $this->task_due_date = null;
        $this->task_priority = 'medium';

        $this->dispatch('notify', type: 'success', message: 'Task created.');
    }

    public function toggleTaskStatus(int $taskId): void
    {
        $task = $this->patient->tasks()->findOrFail($taskId);
        $task->update([
            'status' => $task->status === 'completed' ? 'pending' : 'completed',
        ]);
        $this->patient->load('tasks.assignedUser');
    }

    public function deleteActivity(int $activityId): void
    {
        $this->patient->activities()->where('id', $activityId)->delete();
        $this->patient->load('activities.user');
    }

    public function deleteTask(int $taskId): void
    {
        $this->patient->tasks()->where('id', $taskId)->delete();
        $this->patient->load('tasks.assignedUser');
    }

    public function getTitle(): string
    {
        return $this->patient->first_name . ' ' . $this->patient->last_name;
    }

    public function getHeading(): string
    {
        return '';
    }

    public function getUsers()
    {
        return User::where('clinic_id', auth()->user()->clinic_id)->pluck('name', 'id');
    }

    public function getStages()
    {
        return \App\Models\CrmPipelineStage::with('pipeline')
            ->whereHas('pipeline')
            ->get()
            ->mapWithKeys(fn ($stage) => [$stage->id => $stage->pipeline->name . ' - ' . $stage->name]);
    }
}
