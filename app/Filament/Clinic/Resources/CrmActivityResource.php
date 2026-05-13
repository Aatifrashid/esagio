<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\CrmActivityResource\Pages;
use App\Models\CrmActivity;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CrmActivityResource extends Resource
{
    protected static ?string $model = CrmActivity::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'CRM';

    protected static ?string $navigationLabel = 'Activities';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
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
            Forms\Components\RichEditor::make('description')
                ->columnSpanFull(),
            Forms\Components\Select::make('outcome')
                ->options([
                    'positive' => 'Positive',
                    'neutral' => 'Neutral',
                    'negative' => 'Negative',
                    'no_answer' => 'No Answer',
                ]),
            Forms\Components\TextInput::make('duration_minutes')
                ->label('Duration (minutes)')
                ->numeric(),
            Forms\Components\Select::make('patient_id')
                ->label('Patient')
                ->options(fn () => Patient::query()
                    ->selectRaw("id, CONCAT(first_name, ' ', last_name) as name")
                    ->pluck('name', 'id'))
                ->searchable()
                ->required(),
            Forms\Components\DateTimePicker::make('occurred_at')
                ->default(now())
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'call',
                        'success' => 'email',
                        'warning' => 'meeting',
                        'secondary' => 'note',
                        'info' => 'whatsapp',
                    ]),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('patient.first_name')
                    ->label('Patient')
                    ->getStateUsing(fn (CrmActivity $record) => $record->patient?->first_name.' '.$record->patient?->last_name)
                    ->searchable(['patients.first_name', 'patients.last_name']),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Logged By'),
                Tables\Columns\TextColumn::make('occurred_at')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('outcome')
                    ->colors([
                        'success' => 'positive',
                        'secondary' => 'neutral',
                        'danger' => 'negative',
                        'warning' => 'no_answer',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'call' => 'Call',
                        'email' => 'Email',
                        'meeting' => 'Meeting',
                        'note' => 'Note',
                        'whatsapp' => 'WhatsApp',
                        'other' => 'Other',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('occurred_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCrmActivities::route('/'),
            'create' => Pages\CreateCrmActivity::route('/create'),
            'edit' => Pages\EditCrmActivity::route('/{record}/edit'),
        ];
    }
}
