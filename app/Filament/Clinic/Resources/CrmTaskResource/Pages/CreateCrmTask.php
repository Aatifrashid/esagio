<?php

namespace App\Filament\Clinic\Resources\CrmTaskResource\Pages;

use App\Filament\Clinic\Resources\CrmTaskResource;
use App\Models\Appointment;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;

class CreateCrmTask extends CreateRecord
{
    protected static string $resource = CrmTaskResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }

    protected function afterCreate(): void
    {
        $task = $this->record;

        if ($task->due_date) {
            $dueDate = Carbon::parse($task->due_date);
            Appointment::create([
                'clinic_id' => $task->clinic_id,
                'patient_id' => $task->patient_id,
                'assigned_to' => $task->assigned_to ?? auth()->id(),
                'created_by' => auth()->id(),
                'title' => $task->title,
                'description' => $task->description,
                'starts_at' => $dueDate->copy()->setTime(9, 0),
                'ends_at' => $dueDate->copy()->setTime(9, 30),
                'status' => 'scheduled',
                'type' => 'other',
                'colour' => match ($task->priority) {
                    'urgent' => '#dc2626',
                    'high' => '#f59e0b',
                    'medium' => '#E8663D',
                    'low' => '#6b7280',
                    default => '#E8663D',
                },
            ]);
        }
    }
}
