<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreatmentTemplate extends Model
{
    use BelongsToClinic, HasFactory;

    protected $fillable = [
        'category_id', 'code', 'name', 'description_short', 'description_long',
        'procedure_steps', 'recovery_info', 'materials_options', 'guarantee_text',
        'legal_disclaimer', 'standard_duration_days', 'requires_imaging',
        'default_animation_clip_ids', 'default_before_after_ids', 'is_active',
        'usage_count', 'clinic_id',
    ];

    protected $casts = [
        'procedure_steps' => 'array',
        'materials_options' => 'array',
        'default_animation_clip_ids' => 'array',
        'default_before_after_ids' => 'array',
        'requires_imaging' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(TreatmentCategory::class, 'category_id');
    }
}
