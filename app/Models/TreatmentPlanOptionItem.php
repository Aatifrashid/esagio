<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreatmentPlanOptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'treatment_plan_option_id',
        'treatment_template_id',
        'name',
        'description',
        'quantity',
        'unit_price',
        'line_total',
        'material_id',
        'variant_name',
        'position',
        'notes',
        'included_animation_clip_ids',
        'included_before_after_ids',
        'tooth_positions',
        'procedure_phase',
        'populated_fields',
        'is_optional',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
        'position' => 'integer',
        'included_animation_clip_ids' => 'array',
        'included_before_after_ids' => 'array',
        'tooth_positions' => 'array',
        'populated_fields' => 'array',
        'is_optional' => 'boolean',
    ];

    public function option(): BelongsTo
    {
        return $this->belongsTo(TreatmentPlanOption::class, 'treatment_plan_option_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(TreatmentTemplate::class, 'treatment_template_id');
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
