<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TreatmentCategory extends Model
{
    use BelongsToClinic, HasFactory;

    protected $fillable = [
        'name', 'slug', 'parent_id', 'description', 'icon', 'sort_order', 'clinic_id',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(TreatmentCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(TreatmentCategory::class, 'parent_id');
    }

    public function templates(): HasMany
    {
        return $this->hasMany(TreatmentTemplate::class, 'category_id');
    }
}
