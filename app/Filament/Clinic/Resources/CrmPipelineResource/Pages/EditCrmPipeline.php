<?php

namespace App\Filament\Clinic\Resources\CrmPipelineResource\Pages;

use App\Filament\Clinic\Resources\CrmPipelineResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCrmPipeline extends EditRecord
{
    protected static string $resource = CrmPipelineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
