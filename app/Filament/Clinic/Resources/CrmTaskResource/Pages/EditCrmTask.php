<?php

namespace App\Filament\Clinic\Resources\CrmTaskResource\Pages;

use App\Filament\Clinic\Resources\CrmTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCrmTask extends EditRecord
{
    protected static string $resource = CrmTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
