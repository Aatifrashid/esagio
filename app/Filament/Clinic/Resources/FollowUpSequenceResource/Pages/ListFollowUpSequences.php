<?php

namespace App\Filament\Clinic\Resources\FollowUpSequenceResource\Pages;

use App\Filament\Clinic\Resources\FollowUpSequenceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFollowUpSequences extends ListRecords
{
    protected static string $resource = FollowUpSequenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
