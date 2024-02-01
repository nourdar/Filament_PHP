<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Delivery;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use App\Http\Controllers\AlgeriaCities;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\DeliveryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DeliveryResource\RelationManagers;
use App\Http\Controllers\HelperController;

class DeliveryResource extends Resource
{
    protected static ?string $model = Delivery::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationLabel = 'Livraison';

    protected static ?int $navigationSort = 9;

    protected static ?string $navigationGroup = "Ma Boutique";

    protected static  ?string $label = 'Livraison';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Livreur')->schema([
                    Forms\Components\Select::make('company_name')
                        ->searchable()
                        ->options(function () {
                            return HelperController::get_livreurs_options();
                        })
                        ->label('Livreur'),
                    Forms\Components\TextInput::make('wilaya_id')
                        ->label('Code Wilaya')
                        ->maxLength(50),
                    // Forms\Components\TextInput::make('commune_id')
                    //     ->maxLength(50),
                    Forms\Components\TextInput::make('wilaya_name')
                        ->label('Wilaya')
                        ->maxLength(50),
                    Forms\Components\TextInput::make('commune_name')
                        ->label('Commune')
                        ->maxLength(50),
                    Forms\Components\Toggle::make('is_wilaya')
                        ->label('est une wilaya ?'),
                    Forms\Components\Toggle::make('is_commune')
                        ->label('est une commune ?'),
                    Forms\Components\Toggle::make('is_center')
                        ->label('est un centre ?'),

                ])->columns(4),
                Section::make('Tarification')->schema([
                    Forms\Components\TextInput::make('home')
                        ->label('Domicile')
                        ->maxLength(50),
                    Forms\Components\TextInput::make('desk')
                        ->label('Stop Desk')
                        ->maxLength(50),
                    Forms\Components\TextInput::make('retour')
                        ->label('Retour')
                        ->maxLength(50),

                ])->columns(4),
                Section::make('Information supplémentaire')->schema([
                    Forms\Components\Toggle::make('is_deliverable')
                        ->label('Possiblité  de livraison ?'),
                    Forms\Components\Toggle::make('has_stop_desk')
                        ->label('Contien Stop Desk ?'),
                    Forms\Components\TextInput::make('zone')
                        ->maxLength(50),
                    Forms\Components\TextInput::make('center_id')
                        ->maxLength(50),
                    Forms\Components\TextInput::make('center_name')
                        ->maxLength(100),
                    Forms\Components\TextInput::make('center_address')
                        ->maxLength(200),
                    Forms\Components\TextInput::make('center_gps')
                        ->maxLength(100),

                ])->columns(4),





            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([


                Tables\Columns\TextColumn::make('company_name')
                    ->label('Transporteur')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('wilaya_id')
                    ->label('Code Wilaya')
                    ->sortable()
                    ->searchable(),
                // Tables\Columns\TextColumn::make('commune_id')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('wilaya_name')
                    ->label('wilaya')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('commune_name')
                    ->label('commune')
                    ->sortable()
                    ->searchable(),




                Tables\Columns\TextInputColumn::make('home')
                    ->label('Tarif Domicile')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextInputColumn::make('desk')
                    ->label('Tarif Stop Desk')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextInputColumn::make('retour')
                    ->label('Tarif Retour')
                    ->sortable()
                    ->searchable(),





                // Tables\Columns\TextColumn::make('wilaya_id')
                //     ->label('Code Wilaya')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('commune_id')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('wilaya_name')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('commune_name')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('is_wilaya')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('is_commune')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('is_center')
                //     ->searchable(),

                // Tables\Columns\TextColumn::make('is_deliverable')
                //     ->searchable(),
                Tables\Columns\ToggleColumn::make('has_stop_desk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('zone')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('center_id')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('center_name')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('center_address')
                    ->searchable(),

                ViewColumn::make('center_gps')
                    ->label('GPS')
                    ->view('Admin.Delivery-gps'),

                // Tables\Columns\TextColumn::make('center_gps')
                //     ->searchable(),
            ])
            ->filters([
                Filter::make('is_center')
                    ->label('Centres')
                    ->query(fn (Builder $query): Builder => $query->where('is_center', true)),
                Filter::make('is_deliverable')
                    ->label('Non Deliverable')
                    ->query(fn (Builder $query): Builder => $query->where('is_deliverable', false)),

                SelectFilter::make('wilaya_id')
                    ->label('Wilaya')
                    ->searchable()
                    ->multiple()
                    ->options(function () {
                        return collect((new AlgeriaCities())->get_all_wilayas());
                    }),

                // SelectFilter::make('commune_name')
                //     ->label('Commune')
                //     ->options(function () {
                //         return collect((new AlgeriaCities())->get_all_communes_options());
                //     })
                //     ->searchable()
                //     ->multiple(),


            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListDeliveries::route('/'),
            // 'create' => Pages\CreateDelivery::route('/create'),
            'edit' => Pages\EditDelivery::route('/{record}/edit'),
        ];
    }
}
