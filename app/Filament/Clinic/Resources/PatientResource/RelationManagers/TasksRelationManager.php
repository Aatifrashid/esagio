<?php

namespace App\Filament\Clinic\Resources\PatientResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('description')
                ->rows(3)
                ->columnSpanFull(),
            Forms\Components\Select::make('assigned_to')
                ->label('Assign To')
                ->options(fn () => User::pluck('name', 'id'))
                ->searchable(),
            Forms\Components\DatePicker::make('due_date'),
            Forms\Components\Select::make('priority')
                ->options([
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'high' => 'High',
                    'urgent' => 'Urgent',
                ])
                ->default('medium'),
            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'in_progress' => 'In Progress',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                ])
                ->default('pending'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('assignedUser.name')
                    ->label('Assigned To'),
                Tables\Columns\TextColumn::make('due_date')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('priority')
                    ->colors([
                        'secondary' => 'low',
                        'primary' => 'medium',
                        'warning' => 'high',
                        'danger' => 'urgent',
                    ]),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'pending',
                        'primary' => 'in_progress',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
