<?php

namespace App\Filament\Clinic\Resources\PatientResource\Pages;

use App\Filament\Clinic\Resources\PatientResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPatient extends ViewRecord
{
    protected static string $resource = PatientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
