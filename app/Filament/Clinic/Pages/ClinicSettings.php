<?php

namespace App\Filament\Clinic\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ClinicSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.clinic.pages.clinic-settings';

    protected static ?string $navigationLabel = 'Settings';

    protected static ?string $navigationGroup = 'Clinic Setup';

    protected static ?int $navigationSort = 99;

    public ?array $data = [];

    public function mount(): void
    {
        $clinic = Auth::user()?->clinic;

        if ($clinic) {
            $this->form->fill($clinic->toArray());
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Clinic Profile')->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->maxLength(50),
                    Forms\Components\Textarea::make('address')
                        ->rows(3),
                    Forms\Components\TextInput::make('registration_number')
                        ->label('Registration Number')
                        ->maxLength(100),
                    Forms\Components\TextInput::make('vat_number')
                        ->label('VAT Number')
                        ->maxLength(100),
                ])->columns(2),

                Forms\Components\Section::make('Default Settings')->schema([
                    Forms\Components\Select::make('currency_default')
                        ->label('Default Currency')
                        ->options([
                            'GBP' => 'GBP - Pound Sterling',
                            'EUR' => 'EUR - Euro',
                            'USD' => 'USD - US Dollar',
                            'TRY' => 'TRY - Turkish Lira',
                            'AED' => 'AED - UAE Dirham',
                        ])
                        ->required(),
                    Forms\Components\Select::make('language_default')
                        ->label('Default Language')
                        ->options([
                            'en' => 'English',
                            'de' => 'German',
                            'fr' => 'French',
                            'es' => 'Spanish',
                            'ar' => 'Arabic',
                            'tr' => 'Turkish',
                            'ru' => 'Russian',
                        ])
                        ->required(),
                    Forms\Components\Select::make('timezone')
                        ->options(
                            collect(timezone_identifiers_list())
                                ->mapWithKeys(fn ($tz) => [$tz => $tz])
                                ->toArray()
                        )
                        ->searchable()
                        ->required(),
                ])->columns(3),

                Forms\Components\Section::make('API & Integrations')
                    ->description('Use your API key to send leads from website forms, Facebook Ads, Google Ads, and other sources directly into your CRM.')
                    ->schema([
                        Forms\Components\TextInput::make('api_key')
                            ->label('API Key')
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Send leads to: POST https://esagio.com/api/leads with this API key. See docs for details.')
                            ->suffixAction(
                                Forms\Components\Actions\Action::make('regenerateApiKey')
                                    ->icon('heroicon-o-arrow-path')
                                    ->requiresConfirmation()
                                    ->modalHeading('Regenerate API Key')
                                    ->modalDescription('This will invalidate your current API key. Any integrations using the old key will stop working.')
                                    ->action(function () {
                                        $clinic = Auth::user()?->clinic;
                                        if ($clinic) {
                                            $clinic->update(['api_key' => 'esa_' . \Illuminate\Support\Str::random(40)]);
                                            $this->form->fill($clinic->fresh()->toArray());
                                            Notification::make()->title('API key regenerated')->success()->send();
                                        }
                                    })
                            ),
                    ])->columns(1),

                Forms\Components\Section::make('Branding')->schema([
                    Forms\Components\FileUpload::make('logo_path')
                        ->label('Logo')
                        ->image()
                        ->directory('clinic-logos')
                        ->columnSpanFull(),
                    Forms\Components\ColorPicker::make('primary_colour')
                        ->label('Primary Colour'),
                    Forms\Components\ColorPicker::make('secondary_colour')
                        ->label('Secondary Colour'),
                    Forms\Components\Select::make('font_family')
                        ->label('Font')
                        ->options([
                            'Inter' => 'Inter',
                            'Fraunces' => 'Fraunces',
                            'Roboto' => 'Roboto',
                            'Open Sans' => 'Open Sans',
                            'Lato' => 'Lato',
                        ]),
                ])->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $clinic = Auth::user()?->clinic;

        if (! $clinic) {
            return;
        }

        $data = $this->form->getState();

        $clinic->update($data);

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Action::make('save')
                ->label('Save Settings')
                ->submit('save'),
        ];
    }
}
