<?php

namespace App\Filament\Clinic\Resources\TeamMemberResource\Pages;

use App\Filament\Clinic\Resources\TeamMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTeamMembers extends ListRecords
{
    protected static string $resource = TeamMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add Team Member'),
        ];
    }
}
