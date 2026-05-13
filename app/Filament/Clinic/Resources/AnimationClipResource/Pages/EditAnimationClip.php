<?php

namespace App\Filament\Clinic\Resources\AnimationClipResource\Pages;

use App\Filament\Clinic\Resources\AnimationClipResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnimationClip extends EditRecord
{
    protected static string $resource = AnimationClipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
