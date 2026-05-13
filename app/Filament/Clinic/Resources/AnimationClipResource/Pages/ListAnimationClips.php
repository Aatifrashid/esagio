<?php

namespace App\Filament\Clinic\Resources\AnimationClipResource\Pages;

use App\Filament\Clinic\Resources\AnimationClipResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnimationClips extends ListRecords
{
    protected static string $resource = AnimationClipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
