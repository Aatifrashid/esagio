<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Services\Gdpr\DataExporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GdprController extends Controller
{
    public function __construct(private DataExporter $exporter) {}

    public function export(Request $request, Patient $patient)
    {
        $this->authorise($request);

        $path = $this->exporter->exportPatientData($patient);

        return Storage::download($path, "patient-{$patient->id}-data-export.json");
    }

    public function delete(Request $request, Patient $patient)
    {
        $this->authorise($request);

        $this->exporter->deletePatientData($patient);

        return redirect()->back()->with('success', 'Patient data has been anonymised.');
    }

    private function authorise(Request $request): void
    {
        $user = $request->user();

        if (! in_array($user->role, ['super_admin', 'clinic_owner'])) {
            abort(403, 'Only clinic owners can perform GDPR actions.');
        }
    }
}
