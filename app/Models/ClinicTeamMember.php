<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicTeamMember extends Model
{
    use BelongsToClinic, HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'title', 'role', 'specialisation', 'bio',
        'photo_path', 'qualifications', 'years_experience', 'languages_spoken',
        'sort_order', 'is_active', 'clinic_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
