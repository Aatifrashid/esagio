<?php

namespace App\Services\Integrations;

use App\Models\Patient;
use App\Models\TreatmentPlan;
use Illuminate\Support\Facades\Http;

class GoHighLevelConnector
{
    public function __construct(
        private string $apiKey,
        private string $locationId,
    ) {}

    public function syncPatient(Patient $patient): ?array
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiKey}",
            'Version' => '2021-07-28',
        ])->post('https://services.leadconnectorhq.com/contacts/', [
            'locationId' => $this->locationId,
            'firstName' => $patient->first_name,
            'lastName' => $patient->last_name,
            'email' => $patient->email,
            'phone' => $patient->phone,
            'source' => 'Esagio',
        ]);

        return $response->successful() ? $response->json() : null;
    }

    public function syncPlanStatus(TreatmentPlan $plan): ?array
    {
        if (! $plan->patient?->email) {
            return null;
        }

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiKey}",
            'Version' => '2021-07-28',
        ])->put('https://services.leadconnectorhq.com/contacts/lookup', [
            'locationId' => $this->locationId,
            'email' => $plan->patient->email,
            'customFields' => [
                ['key' => 'treatment_plan_status', 'value' => $plan->status],
                ['key' => 'treatment_plan_total', 'value' => (string) $plan->total_amount],
                ['key' => 'treatment_plan_ref', 'value' => $plan->reference_number],
            ],
        ]);

        return $response->successful() ? $response->json() : null;
    }
}
