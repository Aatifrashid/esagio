<?php

namespace App\Filament\Super\Resources;

use App\Filament\Super\Resources\ClinicResource\Pages;
use App\Models\Clinic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ClinicResource extends Resource
{
    protected static ?string $model = Clinic::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'Clinics';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Clinic Details')->schema([
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\TextInput::make('slug')->required()->maxLength(100)->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('email')->email()->maxLength(255),
                Forms\Components\TextInput::make('phone')->tel()->maxLength(50),
                Forms\Components\TextInput::make('country')->maxLength(100),
                Forms\Components\TextInput::make('registration_number')->label('Registration Number')->maxLength(100),
                Forms\Components\TextInput::make('vat_number')->label('VAT Number')->maxLength(100),
                Forms\Components\Textarea::make('address')->rows(2),
            ])->columns(2),

            Forms\Components\Section::make('Plan & Status')->schema([
                Forms\Components\Select::make('plan_tier')
                    ->options([
                        'free' => 'Free',
                        'starter' => 'Starter',
                        'professional' => 'Professional',
                        'agency' => 'Agency',
                    ])
                    ->required()
                    ->default('free'),
                Forms\Components\Toggle::make('is_active')->default(true),
                Forms\Components\DateTimePicker::make('trial_ends_at')->label('Trial Ends'),
                Forms\Components\DateTimePicker::make('suspended_at')->label('Suspended At'),
                Forms\Components\TextInput::make('suspended_reason')->label('Suspension Reason')->maxLength(255),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\BadgeColumn::make('plan_tier')
                    ->colors([
                        'secondary' => 'free',
                        'primary' => 'starter',
                        'success' => 'professional',
                        'warning' => 'agency',
                    ]),
                Tables\Columns\IconColumn::make('is_active')->label('Active')->boolean(),
                Tables\Columns\TextColumn::make('users_count')
                    ->label('Users')
                    ->counts('users')
                    ->sortable(),
                Tables\Columns\TextColumn::make('plans_count')
                    ->label('Plans')
                    ->counts('treatmentPlans')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('plan_tier')
                    ->options([
                        'free' => 'Free',
                        'starter' => 'Starter',
                        'professional' => 'Professional',
                        'agency' => 'Agency',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
            ])
            ->actions([
                Tables\Actions\Action::make('suspend')
                    ->label('Suspend')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Clinic $record) => $record->is_active)
                    ->action(fn (Clinic $record) => $record->update(['is_active' => false, 'suspended_at' => now()])),
                Tables\Actions\Action::make('activate')
                    ->label('Activate')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Clinic $record) => ! $record->is_active)
                    ->action(fn (Clinic $record) => $record->update(['is_active' => true, 'suspended_at' => null, 'suspended_reason' => null])),
                Tables\Actions\Action::make('change_tier')
                    ->label('Change Tier')
                    ->icon('heroicon-o-arrow-path')
                    ->form([
                        Forms\Components\Select::make('plan_tier')
                            ->options([
                                'free' => 'Free',
                                'starter' => 'Starter',
                                'professional' => 'Professional',
                                'agency' => 'Agency',
                            ])
                            ->required(),
                    ])
                    ->action(fn (Clinic $record, array $data) => $record->update(['plan_tier' => $data['plan_tier']])),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClinics::route('/'),
            'create' => Pages\CreateClinic::route('/create'),
            'edit' => Pages\EditClinic::route('/{record}/edit'),
        ];
    }
}
