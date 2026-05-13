<?php

namespace App\Filament\Clinic\Resources\CrmActivityResource\Pages;

use App\Filament\Clinic\Resources\CrmActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCrmActivities extends ListRecords
{
    protected static string $resource = CrmActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
