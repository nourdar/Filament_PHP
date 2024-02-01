<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use App\Http\Controllers\AlgeriaCities;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CustomerResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CustomerResource\RelationManagers;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    // protected static bool $isDiscovered = false;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Clients';

    protected static ?string $navigationGroup = 'Ma Boutique';


    protected static ?int $navigationSort = 5;


    protected static ?string $activeNavigationIcon = "heroicon-o-check-badge";


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make([
                            TextInput::make('name')
                                ->label('Nom')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->label('Adresse email')
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),


                            TextInput::make('phone')
                                ->label('Numéro de Téléphone')
                                ->tel()
                                ->maxLength(255),

                            TextInput::make('phone2')
                                ->label('Numéro de Téléphone 02')
                                ->tel()
                                ->maxLength(255),

                        ])
                    ]),

                Group::make()
                    ->schema([
                        Section::make([
                            TextInput::make('address')
                                ->label('Wilaya')
                                ->default(function ($record) {
                                    if (!empty($record)) {

                                        return (new AlgeriaCities())->get_all_wilayas()[$record?->address];
                                    }

                                    return $record;
                                })
                                ->maxLength(255),


                            TextInput::make('city')
                                ->label('Commune')
                                ->maxLength(255),




                        ])
                    ]),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Nom')
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Adresse email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Numéro de Téléphone')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->label('Date de Naissance')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Adresse')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('zip_code')
                    ->label('Code Postal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->searchable()
                    ->label('Ville')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date de création')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->label('Modifier'),
                    Tables\Actions\ViewAction::make()
                        ->label('Voir'),
                    Tables\Actions\DeleteAction::make()
                        ->label('Supprimer'),
                ])
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
