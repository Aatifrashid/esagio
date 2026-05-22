<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\PatientResource\Pages;
use App\Filament\Clinic\Resources\PatientResource\RelationManagers;
use App\Models\CrmPipelineStage;
use App\Models\Patient;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = null;

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\Section::make('Personal Details')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('last_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('whatsapp_number')
                            ->label('WhatsApp Number')
                            ->tel()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('country_of_residence')
                            ->label('Country')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('city')
                            ->maxLength(100),
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->label('Date of Birth'),
                        Forms\Components\Select::make('gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                                'other' => 'Other',
                                'prefer_not_to_say' => 'Prefer not to say',
                            ]),
                    ])
                    ->columnSpan(1),

                Forms\Components\Section::make('CRM Details')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'new' => 'New',
                                'contacted' => 'Contacted',
                                'qualified' => 'Qualified',
                                'proposal_sent' => 'Proposal Sent',
                                'won' => 'Won',
                                'lost' => 'Lost',
                                'on_hold' => 'On Hold',
                            ])
                            ->required()
                            ->default('new'),
                        Forms\Components\Select::make('source')
                            ->options([
                                'website' => 'Website',
                                'referral' => 'Referral',
                                'social_media' => 'Social Media',
                                'google_ads' => 'Google Ads',
                                'facebook_ads' => 'Facebook Ads',
                                'instagram_ads' => 'Instagram Ads',
                                'tiktok_ads' => 'TikTok Ads',
                                'walk_in' => 'Walk In',
                                'email_campaign' => 'Email Campaign',
                                'api' => 'API / Integration',
                                'other' => 'Other',
                            ]),
                        Forms\Components\Select::make('assigned_to')
                            ->label('Assigned To')
                            ->options(fn () => User::pluck('name', 'id'))
                            ->searchable(),
                        Forms\Components\TextInput::make('deal_value')
                            ->label('Deal Value')
                            ->numeric()
                            ->prefix('£'),
                        Forms\Components\Select::make('pipeline_stage_id')
                            ->label('Pipeline Stage')
                            ->options(fn () => CrmPipelineStage::with('pipeline')
                                ->whereHas('pipeline')
                                ->get()
                                ->mapWithKeys(fn ($stage) => [$stage->id => $stage->pipeline->name.' - '.$stage->name]))
                            ->searchable(),
                        Forms\Components\TagsInput::make('tags')
                            ->separator(','),
                        Forms\Components\Textarea::make('notes')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(1),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Name')
                    ->getStateUsing(fn (Patient $record) => $record->first_name.' '.$record->last_name)
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(['first_name']),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('phone')
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'new',
                        'warning' => 'contacted',
                        'primary' => 'qualified',
                        'info' => 'proposal_sent',
                        'success' => 'won',
                        'danger' => 'lost',
                        'gray' => 'on_hold',
                    ]),
                Tables\Columns\TextColumn::make('pipelineStage.name')
                    ->label('Stage')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('assignedUser.name')
                    ->label('Assigned To')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('deal_value')
                    ->money('GBP')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('source')
                    ->label('Lead Source')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'website' => 'Website',
                        'referral' => 'Referral',
                        'social_media' => 'Social Media',
                        'google_ads' => 'Google Ads',
                        'facebook_ads' => 'Facebook Ads',
                        'instagram_ads' => 'Instagram Ads',
                        'tiktok_ads' => 'TikTok Ads',
                        'walk_in' => 'Walk In',
                        'email_campaign' => 'Email',
                        'api' => 'API',
                        default => ucwords(str_replace('_', ' ', $state ?? '--')),
                    })
                    ->color(fn (?string $state) => match ($state) {
                        'google_ads' => 'info',
                        'facebook_ads', 'instagram_ads' => 'primary',
                        'tiktok_ads' => 'danger',
                        'website' => 'success',
                        'referral' => 'warning',
                        'walk_in' => 'gray',
                        'email_campaign' => 'info',
                        'social_media' => 'primary',
                        'api' => 'gray',
                        default => 'gray',
                    })
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('last_contacted_at')
                    ->label('Last Contacted')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tags')
                    ->badge()
                    ->separator(',')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'qualified' => 'Qualified',
                        'proposal_sent' => 'Proposal Sent',
                        'won' => 'Won',
                        'lost' => 'Lost',
                        'on_hold' => 'On Hold',
                    ]),
                Tables\Filters\SelectFilter::make('assigned_to')
                    ->label('Assigned To')
                    ->options(fn () => User::pluck('name', 'id')),
                Tables\Filters\SelectFilter::make('source')
                    ->options([
                        'website' => 'Website',
                        'referral' => 'Referral',
                        'social_media' => 'Social Media',
                        'google_ads' => 'Google Ads',
                        'facebook_ads' => 'Facebook Ads',
                        'instagram_ads' => 'Instagram Ads',
                        'tiktok_ads' => 'TikTok Ads',
                        'walk_in' => 'Walk In',
                        'email_campaign' => 'Email Campaign',
                        'api' => 'API / Integration',
                        'other' => 'Other',
                    ]),
                Tables\Filters\Filter::make('has_treatment_plan')
                    ->label('Has Treatment Plan')
                    ->query(fn (Builder $query) => $query->whereHas('treatmentPlans')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('create_plan')
                    ->label('Create Plan')
                    ->icon('heroicon-o-document-plus')
                    ->url(fn (Patient $record) => route('plan.builder', ['plan' => $record->id]))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('log_activity')
                    ->label('Log Activity')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->form([
                        Forms\Components\Select::make('type')
                            ->options([
                                'call' => 'Call',
                                'email' => 'Email',
                                'meeting' => 'Meeting',
                                'note' => 'Note',
                                'whatsapp' => 'WhatsApp',
                                'other' => 'Other',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('subject')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->rows(3),
                        Forms\Components\Select::make('outcome')
                            ->options([
                                'positive' => 'Positive',
                                'neutral' => 'Neutral',
                                'negative' => 'Negative',
                                'no_answer' => 'No Answer',
                            ]),
                        Forms\Components\DateTimePicker::make('occurred_at')
                            ->default(now())
                            ->required(),
                    ])
                    ->action(function (Patient $record, array $data): void {
                        $record->activities()->create(array_merge($data, [
                            'user_id' => auth()->id(),
                            'clinic_id' => $record->clinic_id,
                        ]));

                        $record->update(['last_contacted_at' => now()]);
                    })
                    ->modalWidth('lg'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('change_status')
                        ->label('Change Status')
                        ->icon('heroicon-o-arrow-path')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->options([
                                    'new' => 'New',
                                    'contacted' => 'Contacted',
                                    'qualified' => 'Qualified',
                                    'proposal_sent' => 'Proposal Sent',
                                    'won' => 'Won',
                                    'lost' => 'Lost',
                                    'on_hold' => 'On Hold',
                                ])
                                ->required(),
                        ])
                        ->action(fn ($records, array $data) => $records->each->update(['status' => $data['status']])),
                    Tables\Actions\BulkAction::make('assign_user')
                        ->label('Assign User')
                        ->icon('heroicon-o-user-plus')
                        ->form([
                            Forms\Components\Select::make('assigned_to')
                                ->label('Assign To')
                                ->options(fn () => User::pluck('name', 'id'))
                                ->required(),
                        ])
                        ->action(fn ($records, array $data) => $records->each->update(['assigned_to' => $data['assigned_to']])),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ActivitiesRelationManager::class,
            RelationManagers\TasksRelationManager::class,
            RelationManagers\PlansRelationManager::class,
            RelationManagers\ConversationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'view' => Pages\ViewPatient::route('/{record}'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }
}
