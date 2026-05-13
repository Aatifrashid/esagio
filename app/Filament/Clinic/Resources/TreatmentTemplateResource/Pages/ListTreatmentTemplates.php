<?php

namespace App\Filament\Clinic\Resources\TreatmentTemplateResource\Pages;

use App\Filament\Clinic\Resources\TreatmentTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTreatmentTemplates extends ListRecords
{
    protected static string $resource = TreatmentTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
