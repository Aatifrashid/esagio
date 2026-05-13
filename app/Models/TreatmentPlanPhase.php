<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreatmentPlanPhase extends Model
{
    use HasFactory;

    protected $fillable = [
        'treatment_plan_id',
        'phase_number',
        'name',
        'description',
        'estimated_duration_days',
        'estimated_start_offset_days',
        'sort_order',
    ];

    protected $casts = [
        'phase_number' => 'integer',
        'estimated_duration_days' => 'integer',
        'estimated_start_offset_days' => 'integer',
        'sort_order' => 'integer',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(TreatmentPlan::class, 'treatment_plan_id');
    }
}
