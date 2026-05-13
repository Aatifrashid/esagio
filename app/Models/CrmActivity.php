<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmActivity extends Model
{
    use BelongsToClinic, HasFactory;

    protected $fillable = [
        'patient_id', 'user_id', 'type', 'subject', 'description', 'outcome',
        'duration_minutes', 'occurred_at', 'follow_up_date', 'follow_up_description',
        'attachments', 'clinic_id',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
        'follow_up_date' => 'date',
        'attachments' => 'array',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
