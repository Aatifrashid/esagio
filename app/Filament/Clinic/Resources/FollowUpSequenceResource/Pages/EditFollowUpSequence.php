<?php

namespace App\Filament\Clinic\Resources\FollowUpSequenceResource\Pages;

use App\Filament\Clinic\Resources\FollowUpSequenceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFollowUpSequence extends EditRecord
{
    protected static string $resource = FollowUpSequenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
