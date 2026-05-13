<?php

namespace App\Filament\Super\Resources\SystemTemplateResource\Pages;

use App\Filament\Super\Resources\SystemTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSystemTemplate extends EditRecord
{
    protected static string $resource = SystemTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
