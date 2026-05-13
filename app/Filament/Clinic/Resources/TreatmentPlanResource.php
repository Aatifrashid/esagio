<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\TreatmentPlanResource\Pages;
use App\Models\TreatmentPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TreatmentPlanResource extends Resource
{
    protected static ?string $model = TreatmentPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Treatment Plans';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Plan Details')->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('reference_number')
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\Select::make('patient_id')
                    ->relationship('patient', 'first_name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->first_name.' '.$record->last_name)
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('assigned_to')
                    ->relationship('assignedTo', 'name')
                    ->searchable()
                    ->nullable(),
                Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'sent' => 'Sent',
                        'viewed' => 'Viewed',
                        'accepted' => 'Accepted',
                        'declined' => 'Declined',
                    ])
                    ->default('draft')
                    ->required(),
                Forms\Components\TextInput::make('currency')
                    ->default('GBP')
                    ->maxLength(3),
                Forms\Components\DatePicker::make('valid_until')
                    ->label('Valid Until'),
                Forms\Components\Textarea::make('notes_internal')
                    ->label('Internal Notes')
                    ->rows(3),
                Forms\Components\Textarea::make('notes_to_patient')
                    ->label('Notes to Patient')
                    ->rows(3),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference_number')
                    ->label('Ref')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('patient.first_name')
                    ->label('Patient')
                    ->formatStateUsing(fn ($state, $record) => $record->patient?->first_name.' '.$record->patient?->last_name)
                    ->searchable(query: fn (Builder $query, string $search) => $query->whereHas('patient', fn ($q) => $q->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%")))
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'draft',
                        'primary' => 'sent',
                        'warning' => 'viewed',
                        'success' => 'accepted',
                        'danger' => 'declined',
                    ]),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('GBP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('assignedTo.name')
                    ->label('Assigned To')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'sent' => 'Sent',
                        'viewed' => 'Viewed',
                        'accepted' => 'Accepted',
                        'declined' => 'Declined',
                    ]),
                Tables\Filters\SelectFilter::make('assigned_to')
                    ->relationship('assignedTo', 'name')
                    ->label('Assigned To'),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Created From'),
                        Forms\Components\DatePicker::make('until')->label('Created Until'),
                    ])
                    ->query(fn (Builder $query, array $data) => $query
                        ->when($data['from'], fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
                        ->when($data['until'], fn ($q, $v) => $q->whereDate('created_at', '<=', $v))),
            ])
            ->actions([
                Tables\Actions\Action::make('builder')
                    ->label('Open Builder')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->url(fn (TreatmentPlan $record) => route('plan.builder', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('send')
                    ->label('Send')
                    ->icon('heroicon-o-paper-airplane')
                    ->requiresConfirmation()
                    ->visible(fn (TreatmentPlan $record) => in_array($record->status, ['draft', 'viewed']))
                    ->action(fn (TreatmentPlan $record) => $record->send()),
                Tables\Actions\Action::make('pdf')
                    ->label('Download PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (TreatmentPlan $record) => route('patient.plan.pdf', $record->public_token))
                    ->openUrlInNewTab()
                    ->visible(fn (TreatmentPlan $record) => (bool) $record->public_token),
                Tables\Actions\Action::make('clone')
                    ->label('Clone')
                    ->icon('heroicon-o-document-duplicate')
                    ->requiresConfirmation()
                    ->action(function (TreatmentPlan $record) {
                        $clone = $record->replicate(['reference_number', 'sent_at', 'viewed_at', 'accepted_at', 'declined_at', 'patient_signature', 'patient_signed_at', 'public_token', 'pdf_path']);
                        $clone->status = 'draft';
                        $clone->reference_number = 'TP-'.strtoupper(substr(uniqid(), -6));
                        $clone->save();
                    }),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTreatmentPlans::route('/'),
            'create' => Pages\CreateTreatmentPlan::route('/create'),
            'edit' => Pages\EditTreatmentPlan::route('/{record}/edit'),
        ];
    }
}
