<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\CrmTaskResource\Pages;
use App\Models\CrmTask;
use App\Models\Patient;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CrmTaskResource extends Resource
{
    protected static ?string $model = CrmTask::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $navigationGroup = 'CRM';

    protected static ?string $navigationLabel = 'Tasks';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('description')
                ->rows(3)
                ->columnSpanFull(),
            Forms\Components\Select::make('patient_id')
                ->label('Patient')
                ->options(fn () => Patient::query()
                    ->selectRaw("id, CONCAT(first_name, ' ', last_name) as name")
                    ->pluck('name', 'id'))
                ->searchable(),
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
                ->default('medium')
                ->required(),
            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'in_progress' => 'In Progress',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                ])
                ->default('pending')
                ->required()
                ->live()
                ->afterStateUpdated(function ($state, Forms\Set $set) {
                    if ($state === 'completed') {
                        $set('completed_at', now()->toDateTimeString());
                    }
                }),
            Forms\Components\DateTimePicker::make('completed_at')
                ->label('Completed At')
                ->visible(fn (Forms\Get $get) => $get('status') === 'completed'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('patient.first_name')
                    ->label('Patient')
                    ->getStateUsing(fn (CrmTask $record) => $record->patient?->first_name.' '.$record->patient?->last_name),
                Tables\Columns\TextColumn::make('assignedUser.name')
                    ->label('Assigned To'),
                Tables\Columns\TextColumn::make('due_date')
                    ->date('d M Y')
                    ->sortable()
                    ->color(fn (CrmTask $record) => $record->due_date?->isPast() && $record->status !== 'completed' ? 'danger' : null),
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
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('priority')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                        'urgent' => 'Urgent',
                    ]),
                Tables\Filters\SelectFilter::make('assigned_to')
                    ->label('Assigned To')
                    ->options(fn () => User::pluck('name', 'id')),
                Tables\Filters\Filter::make('overdue')
                    ->label('Overdue Only')
                    ->query(fn (Builder $query) => $query
                        ->whereNotNull('due_date')
                        ->where('due_date', '<', now())
                        ->whereNotIn('status', ['completed', 'cancelled'])),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('mark_completed')
                        ->label('Mark Completed')
                        ->icon('heroicon-o-check')
                        ->action(fn ($records) => $records->each->update([
                            'status' => 'completed',
                            'completed_at' => now(),
                        ])),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('due_date', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCrmTasks::route('/'),
            'create' => Pages\CreateCrmTask::route('/create'),
            'edit' => Pages\EditCrmTask::route('/{record}/edit'),
        ];
    }
}
