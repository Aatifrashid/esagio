<?php

namespace App\Services\VideoConsultation;

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\TreatmentPlan;
use App\Models\VideoConsultationSession;
use App\Support\FeatureGates;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class ConsultationManager
{
    public function __construct(
        private DailyCoClient $dailyClient
    ) {}

    public function schedule(
        Clinic $clinic,
        Patient $patient,
        Carbon $scheduledFor,
        int $durationMinutes = 30,
        ?int $conductedBy = null,
        ?TreatmentPlan $plan = null
    ): VideoConsultationSession {
        if (! FeatureGates::clinicCanUseVideoMinutes($clinic, $durationMinutes)) {
            throw new \RuntimeException('Video minute limit reached for this billing period.');
        }

        $roomName = 'esagio-'.$clinic->slug.'-'.uniqid();

        $room = $this->dailyClient->createRoom([
            'name' => $roomName,
            'max_participants' => 4,
        ]);

        $clinicToken = $this->dailyClient->generateToken($roomName, [
            'is_owner' => true,
            'user_name' => 'Clinician',
            'enable_recording' => true,
        ]);

        $patientToken = $this->dailyClient->generateToken($roomName, [
            'is_owner' => false,
            'user_name' => $patient->first_name.' '.$patient->last_name,
        ]);

        return VideoConsultationSession::create([
            'clinic_id' => $clinic->id,
            'treatment_plan_id' => $plan?->id,
            'patient_id' => $patient->id,
            'scheduled_for' => $scheduledFor,
            'duration_minutes' => $durationMinutes,
            'daily_room_url' => $room['url'] ?? "https://{$clinic->slug}.daily.co/{$roomName}",
            'daily_room_token_patient' => $patientToken,
            'daily_room_token_clinic' => $clinicToken,
            'status' => 'scheduled',
            'conducted_by' => $conductedBy,
        ]);
    }

    public function start(VideoConsultationSession $session): VideoConsultationSession
    {
        $session->update(['status' => 'in_progress']);

        return $session;
    }

    public function complete(VideoConsultationSession $session, ?string $notes = null, ?int $actualMinutes = null): VideoConsultationSession
    {
        $session->update([
            'status' => 'completed',
            'notes' => $notes,
            'actual_duration_minutes' => $actualMinutes ?? $session->duration_minutes,
        ]);

        $session->clinic->increment('video_minutes_used_this_month', $actualMinutes ?? $session->duration_minutes);

        return $session;
    }

    public function cancel(VideoConsultationSession $session): VideoConsultationSession
    {
        $session->update(['status' => 'cancelled']);

        if ($session->daily_room_url) {
            $roomName = basename(parse_url($session->daily_room_url, PHP_URL_PATH));
            $this->dailyClient->deleteRoom($roomName);
        }

        return $session;
    }

    public function markMissed(VideoConsultationSession $session): VideoConsultationSession
    {
        $session->update(['status' => 'missed']);

        return $session;
    }

    public function grantRecordingConsent(VideoConsultationSession $session): VideoConsultationSession
    {
        $session->update(['recording_consent_given' => true]);

        return $session;
    }

    public function getUpcoming(Clinic $clinic, int $limit = 10): Collection
    {
        return VideoConsultationSession::where('clinic_id', $clinic->id)
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->where('scheduled_for', '>=', now()->subHours(1))
            ->orderBy('scheduled_for')
            ->limit($limit)
            ->with(['patient', 'conductedByUser'])
            ->get();
    }

    public function canJoin(VideoConsultationSession $session): bool
    {
        if ($session->status === 'in_progress') {
            return true;
        }

        if ($session->status !== 'scheduled') {
            return false;
        }

        return $session->scheduled_for->diffInMinutes(now()) <= 5;
    }
}
