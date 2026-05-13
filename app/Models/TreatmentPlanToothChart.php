<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreatmentPlanToothChart extends Model
{
    use HasFactory;

    protected $fillable = [
        'treatment_plan_id',
        'tooth_number',
        'conditions',
        'planned_treatments',
        'notes',
    ];

    protected $casts = [
        'conditions' => 'array',
        'planned_treatments' => 'array',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(TreatmentPlan::class, 'treatment_plan_id');
    }
}
