<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\FollowUpSequenceResource\Pages;
use App\Models\FollowUpSequence;
use App\Models\FollowUpStep;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FollowUpSequenceResource extends Resource
{
    protected static ?string $model = FollowUpSequence::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    protected static ?string $navigationGroup = 'Automation';

    protected static ?string $navigationLabel = 'Follow-up Sequences';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Sequence Details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('e.g. Post-Plan Reminder Sequence'),
                    Forms\Components\Select::make('trigger_event')
                        ->options(FollowUpSequence::TRIGGERS)
                        ->required(),
                    Forms\Components\Toggle::make('is_active')
                        ->default(true)
                        ->label('Active'),
                ]),

            Forms\Components\Section::make('Steps')
                ->schema([
                    Forms\Components\Repeater::make('steps')
                        ->relationship()
                        ->schema([
                            Forms\Components\TextInput::make('delay_hours')
                                ->label('Delay (hours)')
                                ->numeric()
                                ->required()
                                ->minValue(1)
                                ->placeholder('e.g. 24'),
                            Forms\Components\Select::make('channel')
                                ->options(FollowUpStep::CHANNELS)
                                ->default(FollowUpStep::CHANNEL_EMAIL)
                                ->required(),
                            Forms\Components\TextInput::make('subject')
                                ->maxLength(255)
                                ->placeholder('Email subject line')
                                ->visible(fn (Forms\Get $get) => $get('channel') === 'email'),
                            Forms\Components\Textarea::make('body_template')
                                ->required()
                                ->rows(4)
                                ->helperText('Available placeholders: {{patient_name}}, {{plan_title}}, {{plan_link}}, {{clinic_name}}, {{plan_total}}')
                                ->columnSpanFull(),
                        ])
                        ->columns(3)
                        ->orderColumn('sort_order')
                        ->defaultItems(1)
                        ->addActionLabel('Add Step')
                        ->collapsible()
                        ->cloneable(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('trigger_event')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => FollowUpSequence::TRIGGERS[$state] ?? $state),
                Tables\Columns\TextColumn::make('steps_count')
                    ->counts('steps')
                    ->label('Steps'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('trigger_event')
                    ->options(FollowUpSequence::TRIGGERS),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFollowUpSequences::route('/'),
            'create' => Pages\CreateFollowUpSequence::route('/create'),
            'edit' => Pages\EditFollowUpSequence::route('/{record}/edit'),
        ];
    }
}
