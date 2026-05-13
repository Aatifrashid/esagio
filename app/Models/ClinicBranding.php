<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicBranding extends Model
{
    use BelongsToClinic, HasFactory;

    protected $fillable = [
        'cover_page_image', 'logo_dark', 'logo_light', 'primary_colour',
        'accent_colour', 'heading_font', 'body_font', 'footer_text',
        'social_links', 'accreditations', 'certifications_images', 'clinic_id',
    ];

    protected $casts = [
        'social_links' => 'array',
        'accreditations' => 'array',
        'certifications_images' => 'array',
    ];

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }
}
