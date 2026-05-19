<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Patient extends Model
{
    use BelongsToClinic, HasFactory, Notifiable, SoftDeletes;

    protected static function booted(): void
    {
        static::creating(function (Patient $patient) {
            if (empty($patient->reference_code)) {
                $patient->reference_code = 'P-' . strtoupper(substr(md5(uniqid()), 0, 8));
            }
        });
    }

    protected $fillable = [
        'reference_code', 'first_name', 'last_name', 'email', 'phone',
        'whatsapp_number', 'country_of_residence', 'city', 'date_of_birth',
        'gender', 'preferred_language', 'occupation', 'medical_history',
        'allergies', 'current_medications', 'dental_history_notes', 'source',
        'referred_by_patient_id', 'assigned_to', 'created_by', 'status',
        'notes', 'tags', 'pipeline_stage_id', 'last_contacted_at',
        'next_action_at', 'next_action_description', 'deal_value', 'clinic_id',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'medical_history' => 'array',
        'tags' => 'array',
        'last_contacted_at' => 'datetime',
        'next_action_at' => 'datetime',
        'deal_value' => 'decimal:2',
    ];

    public function referredBy(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'referred_by_patient_id');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(Patient::class, 'referred_by_patient_id');
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pipelineStage(): BelongsTo
    {
        return $this->belongsTo(CrmPipelineStage::class, 'pipeline_stage_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(CrmActivity::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(CrmTask::class);
    }

    public function treatmentPlans(): HasMany
    {
        return $this->hasMany(TreatmentPlan::class);
    }

    public function videoConsultations(): HasMany
    {
        return $this->hasMany(VideoConsultationSession::class);
    }

    public function crmActivities(): HasMany
    {
        return $this->hasMany(CrmActivity::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }
}
