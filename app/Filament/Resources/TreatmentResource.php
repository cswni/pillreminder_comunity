<?php

namespace App\Filament\Resources;

use App\Enums\TreatmentFirstRoute;
use App\Enums\TreatmentFrequency;
use App\Enums\TreatmentLocation;
use App\Enums\TreatmentVialType;
use App\Filament\Resources\TreatmentResource\Pages;
use App\Filament\Resources\TreatmentResource\RelationManagers;
use App\Models\Treatment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\Facades\Auth;

class TreatmentResource extends Resource
{
    protected static ?string $model = Treatment::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'My Treatments';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('General Information')
                        ->schema([
                            Forms\Components\Select::make('user_id')
                                ->relationship('user', 'name')
                                ->default(Auth::user()->id)
                                ->required(),
                            Forms\Components\Select::make('medicine_id')
                                ->relationship('medicine', 'name')
                                ->required(),
                            Forms\Components\TextInput::make('dosage')
                                ->required(),
                        ]),
                    Wizard\Step::make('Schedule')
                        ->schema([
                            Forms\Components\DateTimePicker::make('start_date')
                                ->required(),
                            Forms\Components\DateTimePicker::make('end_date')
                                ->required(),
                            Forms\Components\TextInput::make('frequency')
                                ->datalist(TreatmentFrequency::all())
                                ->required()
                                ->numeric()
                                ->maxValue(TreatmentFrequency::maxHours())
                                ->minValue(TreatmentFrequency::minHours())
                                ->helperText(TreatmentFrequency::helperText())
                        ]),
                    Wizard\Step::make('Vial Information')
                        ->schema([
                            Forms\Components\Select::make('vial_type')
                                ->options(TreatmentVialType::class)
                                ->live()
                                ->afterStateUpdated(fn(Set $set, $state) => $state !== TreatmentVialType::Injection->value && $set('location', ''))
                                ->required(),
                            Forms\Components\TextInput::make('custom_vial_type')
                                ->visible(fn(Get $get): bool => $get('vial_type') === TreatmentVialType::Other->value),
                            Forms\Components\Select::make('location')
                                ->visible(fn(Get $get): bool => $get('vial_type') === TreatmentVialType::Injection->value)
                                ->options(TreatmentLocation::class)
                                ->live()
                                ->required(),
                            Forms\Components\Select::make('first_route')
                                ->options(TreatmentFirstRoute::class)
                                ->required(fn(Get $get): bool => $get('location') === TreatmentLocation::Arms->value || $get('location') === TreatmentLocation::Legs->value)
                                ->visible(fn(Get $get): bool => $get('location') === TreatmentLocation::Arms->value || $get('location') === TreatmentLocation::Legs->value),
                            Forms\Components\TextInput::make('custom_location')
                                ->live()
                                ->visible(fn(Get $get): bool => $get('location') === TreatmentLocation::Other->value),
                        ]),
                    Wizard\Step::make('Additional Information')
                        ->schema([
                            Forms\Components\Toggle::make('alternate_route')
                                ->required(),
                            Forms\Components\Toggle::make('notify_feedback')
                                ->required(),
                            Forms\Components\Toggle::make('notify_pain')
                                ->required(),
                            Forms\Components\Toggle::make('is_active')
                            ->default(true)
                                ->required(),
                        ]),
                ])->skippable()
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('medicine.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dosage')
                    ->searchable(),
                Tables\Columns\TextColumn::make('frequency')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vial_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('custom_vial_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('custom_location')
                    ->searchable(),
                Tables\Columns\IconColumn::make('alternate_route')
                    ->boolean(),
                Tables\Columns\TextColumn::make('first_route')
                    ->searchable(),
                Tables\Columns\IconColumn::make('notify_feedback')
                    ->boolean(),
                Tables\Columns\IconColumn::make('notify_pain')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTreatments::route('/'),
            'create' => Pages\CreateTreatment::route('/create'),
            'edit' => Pages\EditTreatment::route('/{record}/edit'),
        ];
    }
}
