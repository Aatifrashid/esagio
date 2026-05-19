<?php

namespace App\Filament\Clinic\Pages;

use Filament\Pages\Page;

class Inbox extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Inbox';

    protected static ?string $title = 'Conversations';

    protected static ?string $navigationGroup = 'CRM';

    protected static ?int $navigationSort = 6;

    protected static string $view = 'filament.clinic.pages.inbox';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
