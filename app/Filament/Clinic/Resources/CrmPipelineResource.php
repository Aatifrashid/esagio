<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\CrmPipelineResource\Pages;
use App\Models\CrmPipeline;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CrmPipelineResource extends Resource
{
    protected static ?string $model = CrmPipeline::class;

    protected static ?string $navigationIcon = 'heroicon-o-funnel';

    protected static ?string $navigationGroup = 'Clinic Setup';

    protected static ?string $navigationLabel = 'Pipeline Settings';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('description')
                ->rows(2),
            Forms\Components\Toggle::make('is_default')
                ->label('Set as Default Pipeline'),
            Forms\Components\Repeater::make('stages')
                ->relationship('stages')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\ColorPicker::make('colour')
                        ->default('#3B82F6'),
                    Forms\Components\TextInput::make('probability_percentage')
                        ->label('Probability (%)')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->default(0),
                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0),
                    Forms\Components\Toggle::make('is_won')
                        ->label('Won Stage'),
                    Forms\Components\Toggle::make('is_lost')
                        ->label('Lost Stage'),
                ])
                ->columns(3)
                ->defaultItems(0)
                ->addActionLabel('Add Stage')
                ->orderColumn('sort_order')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_default')
                    ->boolean()
                    ->label('Default'),
                Tables\Columns\TextColumn::make('stages_count')
                    ->counts('stages')
                    ->label('Stages'),
                Tables\Columns\TextColumn::make('created_at')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCrmPipelines::route('/'),
            'create' => Pages\CreateCrmPipeline::route('/create'),
            'edit' => Pages\EditCrmPipeline::route('/{record}/edit'),
        ];
    }
}
