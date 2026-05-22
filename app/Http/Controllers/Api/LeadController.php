<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\CrmActivity;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    /**
     * Ingest a lead from an external source.
     *
     * POST /api/leads
     *
     * Required: api_key (clinic's API key), first_name
     * Optional: last_name, email, phone, whatsapp_number, source,
     *           utm_source, utm_medium, utm_campaign, utm_term, utm_content,
     *           landing_page, referrer_url, lead_channel, notes, tags[],
     *           country_of_residence, city, gender
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'api_key' => 'required|string',
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'whatsapp_number' => 'nullable|string|max:50',
            'source' => 'nullable|string|max:100',
            'utm_source' => 'nullable|string|max:255',
            'utm_medium' => 'nullable|string|max:255',
            'utm_campaign' => 'nullable|string|max:255',
            'utm_term' => 'nullable|string|max:255',
            'utm_content' => 'nullable|string|max:255',
            'landing_page' => 'nullable|url|max:500',
            'referrer_url' => 'nullable|url|max:500',
            'lead_channel' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:2000',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'country_of_residence' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'gender' => 'nullable|in:male,female,other',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Authenticate via clinic API key
        $clinic = Clinic::where('api_key', $request->api_key)->first();

        if (! $clinic) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid API key.',
            ], 401);
        }

        // Auto-detect source from UTM if not explicitly provided
        $source = $request->source;
        $leadChannel = $request->lead_channel;

        if (! $source && $request->utm_source) {
            $source = $this->detectSourceFromUtm($request->utm_source, $request->utm_medium);
        }

        if (! $leadChannel) {
            $leadChannel = $this->detectChannelFromSource($source, $request->utm_source);
        }

        // Check for duplicate by email or phone within this clinic
        $existing = null;
        if ($request->email) {
            $existing = Patient::where('clinic_id', $clinic->id)
                ->where('email', $request->email)
                ->first();
        }
        if (! $existing && $request->phone) {
            $existing = Patient::where('clinic_id', $clinic->id)
                ->where('phone', $request->phone)
                ->first();
        }

        if ($existing) {
            // Update UTM data on existing patient and log activity
            $existing->update(array_filter([
                'utm_source' => $request->utm_source,
                'utm_medium' => $request->utm_medium,
                'utm_campaign' => $request->utm_campaign,
                'utm_term' => $request->utm_term,
                'utm_content' => $request->utm_content,
                'landing_page' => $request->landing_page,
                'referrer_url' => $request->referrer_url,
            ]));

            CrmActivity::create([
                'patient_id' => $existing->id,
                'clinic_id' => $clinic->id,
                'user_id' => null,
                'type' => 'note',
                'subject' => 'Returning lead via ' . ($leadChannel ?? $source ?? 'API'),
                'description' => $this->buildActivityDescription($request),
                'occurred_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Existing contact updated.',
                'patient_id' => $existing->id,
                'reference_code' => $existing->reference_code,
                'is_new' => false,
            ]);
        }

        // Create new patient
        $patient = Patient::create([
            'clinic_id' => $clinic->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name ?? '',
            'email' => $request->email,
            'phone' => $request->phone,
            'whatsapp_number' => $request->whatsapp_number,
            'source' => $source ?? 'api',
            'lead_channel' => $leadChannel ?? 'api',
            'utm_source' => $request->utm_source,
            'utm_medium' => $request->utm_medium,
            'utm_campaign' => $request->utm_campaign,
            'utm_term' => $request->utm_term,
            'utm_content' => $request->utm_content,
            'landing_page' => $request->landing_page,
            'referrer_url' => $request->referrer_url,
            'country_of_residence' => $request->country_of_residence,
            'city' => $request->city,
            'gender' => $request->gender,
            'notes' => $request->notes,
            'tags' => $request->tags,
            'status' => 'new',
        ]);

        // Log the lead creation as an activity
        CrmActivity::create([
            'patient_id' => $patient->id,
            'clinic_id' => $clinic->id,
            'user_id' => null,
            'type' => 'note',
            'subject' => 'New lead via ' . ($leadChannel ?? $source ?? 'API'),
            'description' => $this->buildActivityDescription($request),
            'occurred_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lead created successfully.',
            'patient_id' => $patient->id,
            'reference_code' => $patient->reference_code,
            'is_new' => true,
        ], 201);
    }

    private function detectSourceFromUtm(?string $utmSource, ?string $utmMedium): string
    {
        $utmSource = strtolower($utmSource ?? '');
        $utmMedium = strtolower($utmMedium ?? '');

        if (str_contains($utmSource, 'facebook') || str_contains($utmSource, 'fb')) {
            return $utmMedium === 'cpc' ? 'facebook_ads' : 'social_media';
        }
        if (str_contains($utmSource, 'instagram') || str_contains($utmSource, 'ig')) {
            return $utmMedium === 'cpc' ? 'instagram_ads' : 'social_media';
        }
        if (str_contains($utmSource, 'google')) {
            return $utmMedium === 'cpc' ? 'google_ads' : 'website';
        }
        if (str_contains($utmSource, 'tiktok')) {
            return $utmMedium === 'cpc' ? 'tiktok_ads' : 'social_media';
        }
        if ($utmMedium === 'email') {
            return 'email_campaign';
        }
        if ($utmMedium === 'referral') {
            return 'referral';
        }

        return 'website';
    }

    private function detectChannelFromSource(?string $source, ?string $utmSource): string
    {
        return match ($source) {
            'facebook_ads' => 'facebook_ads',
            'instagram_ads' => 'instagram_ads',
            'google_ads' => 'google_ads',
            'tiktok_ads' => 'tiktok_ads',
            'email_campaign' => 'email',
            'website' => 'website_form',
            'referral' => 'referral',
            'social_media' => 'social_media',
            default => $utmSource ? 'website_form' : 'api',
        };
    }

    private function buildActivityDescription(Request $request): string
    {
        $parts = [];

        if ($request->utm_source) {
            $parts[] = 'Source: ' . $request->utm_source;
        }
        if ($request->utm_medium) {
            $parts[] = 'Medium: ' . $request->utm_medium;
        }
        if ($request->utm_campaign) {
            $parts[] = 'Campaign: ' . $request->utm_campaign;
        }
        if ($request->landing_page) {
            $parts[] = 'Landing page: ' . $request->landing_page;
        }
        if ($request->referrer_url) {
            $parts[] = 'Referrer: ' . $request->referrer_url;
        }

        return $parts ? implode("\n", $parts) : 'Lead submitted via API.';
    }
}
