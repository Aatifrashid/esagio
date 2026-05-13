<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\AnimationClipResource\Pages;
use App\Models\AnimationClip;
use App\Models\TreatmentCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AnimationClipResource extends Resource
{
    protected static ?string $model = AnimationClip::class;

    protected static ?string $navigationIcon = 'heroicon-o-film';

    protected static ?string $navigationGroup = 'Content Library';

    protected static ?string $navigationLabel = 'Animation Clips';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([

                Forms\Components\Section::make('Clip Details')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->helperText('Short unique identifier, e.g. IMPL-UL-001'),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('treatment_category_id')
                            ->label('Treatment Category')
                            ->options(fn () => TreatmentCategory::pluck('name', 'id'))
                            ->searchable(),

                        Forms\Components\Select::make('procedure_type')
                            ->options([
                                'diagnosis' => 'Diagnosis',
                                'extraction' => 'Extraction',
                                'surgical' => 'Surgical',
                                'implant' => 'Implant',
                                'root_canal' => 'Root Canal',
                                'crown' => 'Crown',
                                'bridge' => 'Bridge',
                                'veneer' => 'Veneer',
                                'filling' => 'Filling',
                                'whitening' => 'Whitening',
                                'general' => 'General',
                            ])
                            ->required(),

                        Forms\Components\Select::make('jaw')
                            ->options([
                                'upper' => 'Upper',
                                'lower' => 'Lower',
                                'both' => 'Both',
                            ])
                            ->nullable(),

                        Forms\Components\TagsInput::make('tooth_positions')
                            ->label('Tooth Positions (FDI numbers)')
                            ->placeholder('e.g. 11, 12, 21')
                            ->helperText('Leave empty to apply to all teeth'),

                        Forms\Components\TextInput::make('duration_seconds')
                            ->label('Duration (seconds)')
                            ->numeric()
                            ->minValue(0)
                            ->step(0.1),

                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columnSpan(1),

                Forms\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\TextInput::make('video_path')
                            ->label('Video Path')
                            ->helperText('Relative storage path to the video file')
                            ->maxLength(500),

                        Forms\Components\TextInput::make('thumbnail_path')
                            ->label('Thumbnail Path')
                            ->helperText('Relative storage path to the thumbnail image')
                            ->maxLength(500),
                    ])
                    ->columnSpan(1),

            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('treatmentCategory.name')
                    ->label('Category')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('procedure_type')
                    ->label('Type')
                    ->colors([
                        'primary' => 'implant',
                        'success' => fn ($state) => in_array($state, ['crown', 'veneer', 'bridge']),
                        'warning' => fn ($state) => in_array($state, ['extraction', 'surgical']),
                        'danger' => 'root_canal',
                        'secondary' => fn ($state) => in_array($state, ['filling', 'whitening', 'general', 'diagnosis']),
                    ]),

                Tables\Columns\TextColumn::make('jaw')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('duration_seconds')
                    ->label('Duration')
                    ->suffix(' s')
                    ->sortable(),

                Tables\Columns\IconColumn::make('video_path')
                    ->label('Has Video')
                    ->boolean()
                    ->getStateUsing(fn (AnimationClip $r) => (bool) $r->video_path),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('procedure_type')
                    ->options([
                        'diagnosis' => 'Diagnosis',
                        'extraction' => 'Extraction',
                        'surgical' => 'Surgical',
                        'implant' => 'Implant',
                        'root_canal' => 'Root Canal',
                        'crown' => 'Crown',
                        'bridge' => 'Bridge',
                        'veneer' => 'Veneer',
                        'filling' => 'Filling',
                        'whitening' => 'Whitening',
                        'general' => 'General',
                    ]),

                Tables\Filters\SelectFilter::make('treatment_category_id')
                    ->label('Category')
                    ->options(fn () => TreatmentCategory::pluck('name', 'id')),

                Tables\Filters\SelectFilter::make('jaw')
                    ->options([
                        'upper' => 'Upper',
                        'lower' => 'Lower',
                        'both' => 'Both',
                    ]),
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
            ->defaultSort('sort_order', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnimationClips::route('/'),
            'create' => Pages\CreateAnimationClip::route('/create'),
            'edit' => Pages\EditAnimationClip::route('/{record}/edit'),
        ];
    }
}
