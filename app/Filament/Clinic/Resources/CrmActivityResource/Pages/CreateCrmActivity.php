<?php

namespace App\Filament\Clinic\Resources\CrmActivityResource\Pages;

use App\Filament\Clinic\Resources\CrmActivityResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCrmActivity extends CreateRecord
{
    protected static string $resource = CrmActivityResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
