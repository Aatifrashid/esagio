<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TreatmentPlanComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'treatment_plan_id',
        'author_type',
        'author_name',
        'author_id',
        'content',
        'is_internal',
        'parent_comment_id',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
        'author_id' => 'integer',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(TreatmentPlan::class, 'treatment_plan_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(TreatmentPlanComment::class, 'parent_comment_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TreatmentPlanComment::class, 'parent_comment_id');
    }
}
