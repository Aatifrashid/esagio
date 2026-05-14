<?php

namespace App\Services\Gdpr;

use App\Models\Patient;
use Illuminate\Support\Facades\Storage;

class DataExporter
{
    public function exportPatientData(Patient $patient): string
    {
        $data = [
            'patient' => $patient->only([
                'first_name', 'last_name', 'email', 'phone',
                'date_of_birth', 'gender', 'address', 'medical_notes',
                'created_at', 'updated_at',
            ]),
            'treatment_plans' => $patient->treatmentPlans()->with([
                'items', 'sections', 'comments', 'events', 'toothCharts',
            ])->get()->toArray(),
            'video_consultations' => $patient->videoConsultations()->get()->toArray(),
            'crm_activities' => $patient->crmActivities()->get()->toArray(),
            'exported_at' => now()->toIso8601String(),
        ];

        $path = "gdpr-exports/patient-{$patient->id}-".now()->format('Y-m-d-His').'.json';
        Storage::put($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return $path;
    }

    public function deletePatientData(Patient $patient): void
    {
        $patient->treatmentPlans()->each(function ($plan) {
            $plan->comments()->delete();
            $plan->attachments()->delete();
            $plan->events()->delete();
            $plan->toothCharts()->delete();
            $plan->items()->delete();
            $plan->sections()->delete();
            $plan->delete();
        });

        $patient->videoConsultations()->delete();
        $patient->crmActivities()->delete();

        $patient->update([
            'first_name' => '[deleted]',
            'last_name' => '[deleted]',
            'email' => "deleted-{$patient->id}@removed.local",
            'phone' => null,
            'address' => null,
            'medical_notes' => null,
            'date_of_birth' => null,
        ]);
    }
}
