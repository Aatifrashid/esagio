<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TreatmentPlanOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'treatment_plan_id',
        'option_label',
        'description',
        'is_recommended',
        'total_amount',
        'sort_order',
        'savings_vs_premium',
    ];

    protected $casts = [
        'is_recommended' => 'boolean',
        'total_amount' => 'decimal:2',
        'savings_vs_premium' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(TreatmentPlan::class, 'treatment_plan_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(TreatmentPlanOptionItem::class);
    }
}
