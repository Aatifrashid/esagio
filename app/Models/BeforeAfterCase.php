<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeforeAfterCase extends Model
{
    use BelongsToClinic, HasFactory;

    protected $fillable = [
        'title', 'description', 'patient_age_range', 'patient_gender',
        'treatment_duration_days', 'before_image_path', 'after_image_path',
        'additional_images', 'is_featured', 'consent_obtained', 'tags',
        'treatment_value', 'clinic_id',
    ];

    protected $casts = [
        'additional_images' => 'array',
        'is_featured' => 'boolean',
        'consent_obtained' => 'boolean',
        'treatment_value' => 'decimal:2',
    ];
}
