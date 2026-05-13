<?php

namespace App\Filament\Clinic\Resources\TreatmentPlanResource\Pages;

use App\Filament\Clinic\Resources\TreatmentPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTreatmentPlan extends EditRecord
{
    protected static string $resource = TreatmentPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
