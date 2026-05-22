<?php

namespace App\Filament\Clinic\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LeadIntegrations extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';
    protected static string $view = 'filament.clinic.pages.lead-integrations';
    protected static ?string $navigationLabel = 'Lead Integrations';
    protected static ?string $navigationGroup = 'Integrations';
    protected static ?int $navigationSort = 5;
    protected static ?string $title = 'Lead Integrations';

    public string $apiKey = '';
    public string $webhookUrl = '';

    public function mount(): void
    {
        $clinic = Auth::user()?->clinic;

        if ($clinic) {
            try {
                if (! $clinic->api_key) {
                    $clinic->update(['api_key' => 'esa_' . Str::random(40)]);
                    $clinic->refresh();
                }
                $this->apiKey = $clinic->api_key;
            } catch (\Exception $e) {
                // Migration not yet run — show placeholder
                $this->apiKey = 'Run php artisan migrate to generate your API key';
            }
            $this->webhookUrl = url('/api/leads');
        }
    }

    public function regenerateKey(): void
    {
        $clinic = Auth::user()?->clinic;
        if ($clinic) {
            $clinic->update(['api_key' => 'esa_' . Str::random(40)]);
            $this->apiKey = $clinic->fresh()->api_key;
            Notification::make()->title('API key regenerated')->body('Update your integrations with the new key.')->warning()->send();
        }
    }

    public function copyToClipboard(string $text): void
    {
        $this->dispatch('copy-to-clipboard', text: $text);
    }
}
