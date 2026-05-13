<?php

namespace App\Filament\Clinic\Resources\ClinicTeamMemberResource\Pages;

use App\Filament\Clinic\Resources\ClinicTeamMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClinicTeamMember extends EditRecord
{
    protected static string $resource = ClinicTeamMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
