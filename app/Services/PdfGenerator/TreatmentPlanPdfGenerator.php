<?php

namespace App\Services\PdfGenerator;

use App\Models\TreatmentPlan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TreatmentPlanPdfGenerator
{
    /**
     * Generate a PDF for the given treatment plan and return the storage path.
     *
     * @throws MpdfException
     */
    public function generate(TreatmentPlan $plan): string
    {
        $storagePath = $this->storagePath($plan);

        if (Storage::exists($storagePath)) {
            return $storagePath;
        }

        $html = $this->renderHtml($plan);
        $mpdf = $this->buildMpdf($plan);
        $mpdf->WriteHTML($html);

        $absolutePath = Storage::path($storagePath);
        $directory = dirname($absolutePath);

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $mpdf->Output($absolutePath, 'F');

        $plan->update(['pdf_path' => $storagePath]);

        return $storagePath;
    }

    /**
     * Stream the PDF directly to the browser.
     *
     * @throws MpdfException
     */
    public function stream(TreatmentPlan $plan): Response
    {
        $storagePath = $this->storagePath($plan);

        if (Storage::exists($storagePath)) {
            $absolutePath = Storage::path($storagePath);

            return new StreamedResponse(function () use ($absolutePath) {
                readfile($absolutePath);
            }, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$this->filename($plan).'"',
            ]);
        }

        $html = $this->renderHtml($plan);
        $mpdf = $this->buildMpdf($plan);
        $mpdf->WriteHTML($html);

        $absolutePath = Storage::path($storagePath);
        $directory = dirname($absolutePath);

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $mpdf->Output($absolutePath, 'F');

        $plan->update(['pdf_path' => $storagePath]);

        return new StreamedResponse(function () use ($absolutePath) {
            readfile($absolutePath);
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$this->filename($plan).'"',
        ]);
    }

    private function storagePath(TreatmentPlan $plan): string
    {
        $version = $plan->version ?? 1;

        return "plans/{$plan->id}/v{$version}.pdf";
    }

    private function filename(TreatmentPlan $plan): string
    {
        $ref = $plan->reference_number ?? $plan->id;

        return "treatment-plan-{$ref}.pdf";
    }

    private function renderHtml(TreatmentPlan $plan): string
    {
        $plan->loadMissing([
            'patient',
            'items.material',
            'sections',
            'toothCharts',
            'clinic.branding',
            'clinic.teamMembers',
        ]);

        $isFreeTier = in_array($plan->clinic->plan_tier ?? 'free', ['free', 'trial']);

        return View::make('pdf.treatment-plan', [
            'plan' => $plan,
            'clinic' => $plan->clinic,
            'branding' => $plan->clinic->branding,
            'patient' => $plan->patient,
            'items' => $plan->items,
            'sections' => $plan->sections,
            'toothCharts' => $plan->toothCharts,
            'teamMembers' => $plan->clinic->teamMembers->where('is_active', true),
            'isFreeTier' => $isFreeTier,
        ])->render();
    }

    /**
     * @throws MpdfException
     */
    private function buildMpdf(TreatmentPlan $plan): Mpdf
    {
        $branding = $plan->clinic->branding;
        $primaryColour = $branding?->primary_colour ?? $plan->clinic->primary_colour ?? '#1a73e8';
        $accentColour = $branding?->accent_colour ?? $plan->clinic->secondary_colour ?? '#4a90d9';

        $config = [
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 15,
            'margin_bottom' => 20,
            'margin_left' => 15,
            'margin_right' => 15,
            'tempDir' => storage_path('app/mpdf-tmp'),
        ];

        $mpdf = new Mpdf($config);
        $mpdf->SetTitle('Treatment Plan - '.($plan->reference_number ?? $plan->id));
        $mpdf->SetAuthor($plan->clinic->name ?? 'Esagio');

        $footerHtml = '<table width="100%" style="font-size:8pt; color:#888; border-top:1px solid #ddd; padding-top:4px;">'
            .'<tr>'
            .'<td width="50%">'.htmlspecialchars($plan->clinic->name ?? '').'</td>'
            .'<td width="50%" align="right">Page {PAGENO} of {nbpg}</td>'
            .'</tr>'
            .'</table>';

        if ($plan->clinic->plan_tier === 'free' || $plan->clinic->plan_tier === 'trial') {
            $footerHtml = '<table width="100%" style="font-size:8pt; color:#aaa; border-top:1px solid #eee; padding-top:4px;">'
                .'<tr>'
                .'<td width="50%">Created with Esagio - esagio.com</td>'
                .'<td width="50%" align="right">Page {PAGENO} of {nbpg}</td>'
                .'</tr>'
                .'</table>';
        }

        $mpdf->SetHTMLFooter($footerHtml);

        return $mpdf;
    }
}
