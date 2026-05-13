<?php

namespace App\Filament\Super\Resources\SystemCategoryResource\Pages;

use App\Filament\Super\Resources\SystemCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSystemCategory extends EditRecord
{
    protected static string $resource = SystemCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
