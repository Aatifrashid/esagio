<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoConsultationSession extends Model
{
    use BelongsToClinic, HasFactory;

    protected $fillable = [
        'clinic_id',
        'treatment_plan_id',
        'patient_id',
        'scheduled_for',
        'duration_minutes',
        'daily_room_url',
        'daily_room_token_patient',
        'daily_room_token_clinic',
        'status',
        'recording_url',
        'recording_consent_given',
        'notes',
        'conducted_by',
        'actual_duration_minutes',
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'duration_minutes' => 'integer',
        'recording_consent_given' => 'boolean',
        'actual_duration_minutes' => 'integer',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function treatmentPlan(): BelongsTo
    {
        return $this->belongsTo(TreatmentPlan::class);
    }

    public function conductedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'conducted_by');
    }
}
