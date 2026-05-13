<?php

namespace App\Filament\Clinic\Resources\LegalBlockResource\Pages;

use App\Filament\Clinic\Resources\LegalBlockResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLegalBlocks extends ListRecords
{
    protected static string $resource = LegalBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
