<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ClinicScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (! auth()->check()) {
            return;
        }

        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return;
        }

        if ($user->clinic_id) {
            $builder->where($model->getTable().'.clinic_id', $user->clinic_id);
        }
    }
}
