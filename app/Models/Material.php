<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use BelongsToClinic, HasFactory;

    protected $fillable = [
        'name', 'category', 'brand', 'description', 'country_of_origin',
        'warranty_years', 'is_premium', 'image_path', 'clinic_id',
    ];

    protected $casts = [
        'is_premium' => 'boolean',
    ];
}
