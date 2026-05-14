<?php

namespace App\Filament\Clinic\Resources\WebhookEndpointResource\Pages;

use App\Filament\Clinic\Resources\WebhookEndpointResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebhookEndpoints extends ListRecords
{
    protected static string $resource = WebhookEndpointResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
