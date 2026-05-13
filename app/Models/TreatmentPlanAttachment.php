<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreatmentPlanAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'treatment_plan_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size_bytes',
        'description',
        'is_visible_to_patient',
        'uploaded_by',
    ];

    protected $casts = [
        'file_size_bytes' => 'integer',
        'is_visible_to_patient' => 'boolean',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(TreatmentPlan::class, 'treatment_plan_id');
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
