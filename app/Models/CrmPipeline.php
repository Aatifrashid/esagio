<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrmPipeline extends Model
{
    use BelongsToClinic, HasFactory;

    protected $fillable = [
        'name', 'description', 'is_default', 'sort_order', 'clinic_id',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function stages(): HasMany
    {
        return $this->hasMany(CrmPipelineStage::class)->orderBy('sort_order');
    }
}
