<?php

namespace App\Models\Concerns;

use App\Models\Clinic;
use App\Scopes\ClinicScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToClinic
{
    public static function bootBelongsToClinic(): void
    {
        static::addGlobalScope(new ClinicScope);

        static::creating(function ($model) {
            if (! $model->clinic_id && auth()->check() && ! auth()->user()->isSuperAdmin()) {
                $model->clinic_id = auth()->user()->clinic_id;
            }
        });
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }
}
