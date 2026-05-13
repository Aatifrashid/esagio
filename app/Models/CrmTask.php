<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CrmTask extends Model
{
    use BelongsToClinic, HasFactory;

    protected $fillable = [
        'patient_id', 'assigned_to', 'created_by', 'title', 'description',
        'due_date', 'priority', 'status', 'completed_at',
        'related_to_type', 'related_to_id', 'clinic_id',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function relatedTo(): MorphTo
    {
        return $this->morphTo('related_to');
    }
}
