<?php

namespace App\Filament\Clinic\Resources\TreatmentTemplateResource\Pages;

use App\Filament\Clinic\Resources\TreatmentTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTreatmentTemplate extends EditRecord
{
    protected static string $resource = TreatmentTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
