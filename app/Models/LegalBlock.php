<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalBlock extends Model
{
    use BelongsToClinic, HasFactory;

    protected $fillable = [
        'code', 'name', 'content', 'language', 'jurisdiction', 'is_default', 'clinic_id',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];
}
