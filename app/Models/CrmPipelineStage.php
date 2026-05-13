<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrmPipelineStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'crm_pipeline_id', 'name', 'colour', 'probability_percentage',
        'sort_order', 'is_won', 'is_lost',
    ];

    protected $casts = [
        'is_won' => 'boolean',
        'is_lost' => 'boolean',
    ];

    public function pipeline(): BelongsTo
    {
        return $this->belongsTo(CrmPipeline::class, 'crm_pipeline_id');
    }

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class, 'pipeline_stage_id');
    }
}
