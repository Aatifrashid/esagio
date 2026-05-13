<?php

namespace App\Filament\Clinic\Resources\BeforeAfterCaseResource\Pages;

use App\Filament\Clinic\Resources\BeforeAfterCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBeforeAfterCases extends ListRecords
{
    protected static string $resource = BeforeAfterCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
