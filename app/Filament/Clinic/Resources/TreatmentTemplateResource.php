<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\TreatmentTemplateResource\Pages;
use App\Models\TreatmentCategory;
use App\Models\TreatmentTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TreatmentTemplateResource extends Resource
{
    protected static ?string $model = TreatmentTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Treatment Plans';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identity')->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->options(TreatmentCategory::pluck('name', 'id'))
                    ->searchable()
                    ->nullable(),
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
                Forms\Components\Toggle::make('requires_imaging')
                    ->label('Requires Imaging')
                    ->default(false),
                Forms\Components\TextInput::make('standard_duration_days')
                    ->label('Duration (days)')
                    ->numeric()
                    ->nullable(),
            ])->columns(2),

            Forms\Components\Section::make('Descriptions')->schema([
                Forms\Components\Textarea::make('description_short')
                    ->label('Short Description')
                    ->rows(2),
                Forms\Components\Textarea::make('description_long')
                    ->label('Long Description')
                    ->rows(5),
                Forms\Components\Textarea::make('recovery_info')
                    ->label('Recovery Information')
                    ->rows(3),
                Forms\Components\Textarea::make('guarantee_text')
                    ->label('Guarantee Text')
                    ->rows(2),
                Forms\Components\Textarea::make('legal_disclaimer')
                    ->label('Legal Disclaimer')
                    ->rows(3),
            ]),

            Forms\Components\Section::make('Procedure Steps')->schema([
                Forms\Components\Repeater::make('procedure_steps')
                    ->schema([
                        Forms\Components\TextInput::make('step')
                            ->label('Step')
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(2),
                    ])
                    ->addActionLabel('Add Step')
                    ->orderColumn('order')
                    ->defaultItems(0),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
                Tables\Columns\TextColumn::make('usage_count')
                    ->label('Uses')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->options(TreatmentCategory::pluck('name', 'id')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTreatmentTemplates::route('/'),
            'create' => Pages\CreateTreatmentTemplate::route('/create'),
            'edit' => Pages\EditTreatmentTemplate::route('/{record}/edit'),
        ];
    }
}
