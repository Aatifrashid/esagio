<?php

namespace App\Filament\Clinic\Pages;

use Filament\Pages\Page;

class WhatsappSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-device-phone-mobile';

    protected static ?string $navigationLabel = 'WhatsApp';

    protected static ?string $title = 'WhatsApp Connection';

    protected static ?string $navigationGroup = 'Integrations';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.clinic.pages.whatsapp-settings';
}
