<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnimationClip extends Model
{
    use BelongsToClinic, HasFactory;

    protected $fillable = [
        'code', 'name', 'description', 'treatment_category_id', 'procedure_type',
        'tooth_positions', 'jaw', 'duration_seconds', 'video_path', 'thumbnail_path',
        'sort_order', 'clinic_id',
    ];

    protected $casts = [
        'tooth_positions' => 'array',
    ];

    public function treatmentCategory(): BelongsTo
    {
        return $this->belongsTo(TreatmentCategory::class, 'treatment_category_id');
    }
}
