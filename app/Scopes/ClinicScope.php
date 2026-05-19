<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Schema;

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
            $table = $model->getTable();
            $builder->where($table.'.clinic_id', $user->clinic_id);

            // Restricted roles only see records assigned to them
            if ($user->isRestrictedRole() && Schema::hasColumn($table, 'assigned_to')) {
                $builder->where(function (Builder $q) use ($table, $user) {
                    $q->where($table.'.assigned_to', $user->id)
                      ->orWhereNull($table.'.assigned_to');
                });
            }
        }
    }
}
