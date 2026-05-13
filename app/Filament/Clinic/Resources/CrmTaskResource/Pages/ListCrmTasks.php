<?php

namespace App\Filament\Clinic\Resources\CrmTaskResource\Pages;

use App\Filament\Clinic\Resources\CrmTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCrmTasks extends ListRecords
{
    protected static string $resource = CrmTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
