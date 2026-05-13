<?php

namespace App\Filament\Clinic\Resources\PatientResource\Pages;

use App\Filament\Clinic\Resources\PatientResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePatient extends CreateRecord
{
    protected static string $resource = PatientResource::class;
}
