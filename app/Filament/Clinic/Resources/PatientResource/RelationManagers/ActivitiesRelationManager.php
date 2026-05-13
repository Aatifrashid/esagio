<?php

namespace App\Filament\Clinic\Resources\PatientResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    public function form(Form $form): Form
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
            Forms\Components\DateTimePicker::make('occurred_at')
                ->default(now())
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('subject')
            ->columns([
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'call',
                        'success' => 'email',
                        'warning' => 'meeting',
                        'secondary' => 'note',
                        'info' => 'whatsapp',
                    ]),
                Tables\Columns\TextColumn::make('subject'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('By'),
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
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('occurred_at', 'desc');
    }
}
