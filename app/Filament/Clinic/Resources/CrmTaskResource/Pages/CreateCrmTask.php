<?php

namespace App\Filament\Clinic\Resources\CrmTaskResource\Pages;

use App\Filament\Clinic\Resources\CrmTaskResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCrmTask extends CreateRecord
{
    protected static string $resource = CrmTaskResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }
}
