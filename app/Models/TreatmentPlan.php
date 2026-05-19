<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TreatmentPlan extends Model
{
    use BelongsToClinic, HasFactory, SoftDeletes;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'reference_number',
        'title',
        'status',
        'created_by',
        'assigned_to',
        'signed_by_dentist_id',
        'currency',
        'exchange_rate_snapshot',
        'language',
        'valid_until',
        'sent_at',
        'viewed_at',
        'viewed_count',
        'last_viewed_at',
        'accepted_at',
        'declined_at',
        'decline_reason',
        'patient_signature',
        'patient_signed_at',
        'patient_ip_at_signing',
        'patient_user_agent_at_signing',
        'total_amount',
        'deposit_amount',
        'deposit_paid_at',
        'deposit_payment_intent_id',
        'public_token',
        'public_password',
        'pdf_path',
        'version',
        'notes_internal',
        'notes_to_patient',
        'video_consultation_room_id',
        'template_used_id',
        'is_template',
        'template_name',
    ];

    protected static function booted(): void
    {
        static::creating(function (TreatmentPlan $plan) {
            if (empty($plan->reference_number)) {
                $plan->reference_number = 'TP-' . strtoupper(Str::random(8));
            }
            if (empty($plan->public_token)) {
                $plan->public_token = Str::uuid();
            }
            if (empty($plan->created_by)) {
                $plan->created_by = auth()->id();
            }
        });
    }

    protected $casts = [
        'valid_until' => 'date',
        'sent_at' => 'datetime',
        'viewed_at' => 'datetime',
        'last_viewed_at' => 'datetime',
        'accepted_at' => 'datetime',
        'declined_at' => 'datetime',
        'patient_signed_at' => 'datetime',
        'deposit_paid_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'exchange_rate_snapshot' => 'decimal:6',
        'is_template' => 'boolean',
        'viewed_count' => 'integer',
        'version' => 'integer',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function signedByDentist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_by_dentist_id');
    }

    public function templateUsed(): BelongsTo
    {
        return $this->belongsTo(TreatmentPlan::class, 'template_used_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(TreatmentPlanItem::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(TreatmentPlanOption::class);
    }

    public function phases(): HasMany
    {
        return $this->hasMany(TreatmentPlanPhase::class)->orderBy('sort_order');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(TreatmentPlanSection::class)->orderBy('sort_order');
    }

    public function toothCharts(): HasMany
    {
        return $this->hasMany(TreatmentPlanToothChart::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(TreatmentPlanEvent::class)->orderBy('created_at');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TreatmentPlanComment::class)->orderBy('created_at');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TreatmentPlanAttachment::class);
    }

    public function videoConsultation(): HasOne
    {
        return $this->hasOne(VideoConsultationSession::class);
    }

    public function send(): void
    {
        if (! in_array($this->status, ['draft', 'viewed'])) {
            throw new \InvalidArgumentException("Cannot send a plan with status '{$this->status}'.");
        }

        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
            'public_token' => $this->public_token ?? Str::uuid(),
        ]);
    }

    public function recordView(string $ipAddress, string $userAgent): void
    {
        $query = static::where('id', $this->id);

        if (! $this->viewed_at) {
            $query->update([
                'viewed_count' => \Illuminate\Support\Facades\DB::raw('viewed_count + 1'),
                'last_viewed_at' => now(),
                'status' => 'viewed',
                'viewed_at' => now(),
            ]);
        } else {
            $query->update([
                'viewed_count' => \Illuminate\Support\Facades\DB::raw('viewed_count + 1'),
                'last_viewed_at' => now(),
            ]);
        }

        $this->refresh();
    }

    public function accept(string $signature, string $ipAddress, string $userAgent): void
    {
        if (! in_array($this->status, ['sent', 'viewed'])) {
            throw new \InvalidArgumentException("Cannot accept a plan with status '{$this->status}'.");
        }

        $this->update([
            'status' => 'accepted',
            'accepted_at' => now(),
            'patient_signature' => $signature,
            'patient_signed_at' => now(),
            'patient_ip_at_signing' => $ipAddress,
            'patient_user_agent_at_signing' => $userAgent,
        ]);
    }

    public function decline(string $reason): void
    {
        if (! in_array($this->status, ['sent', 'viewed'])) {
            throw new \InvalidArgumentException("Cannot decline a plan with status '{$this->status}'.");
        }

        $this->update([
            'status' => 'declined',
            'declined_at' => now(),
            'decline_reason' => $reason,
        ]);
    }

    public function recalculateTotal(): void
    {
        $total = $this->items()->where('is_optional', false)->sum('line_total');
        $this->update(['total_amount' => $total]);
    }

    public function incrementVersionOnEdit(): void
    {
        if (in_array($this->status, ['sent', 'viewed', 'declined'])) {
            $this->increment('version');
        }
    }
}
