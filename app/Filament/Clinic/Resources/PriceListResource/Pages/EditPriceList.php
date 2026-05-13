<?php

namespace App\Filament\Clinic\Resources\PriceListResource\Pages;

use App\Filament\Clinic\Resources\PriceListResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPriceList extends EditRecord
{
    protected static string $resource = PriceListResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
