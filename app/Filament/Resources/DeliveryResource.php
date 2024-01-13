<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Delivery;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use App\Http\Controllers\AlgeriaCities;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\DeliveryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DeliveryResource\RelationManagers;

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
                Forms\Components\TextInput::make('company_name')
                    ->maxLength(50),
                Forms\Components\TextInput::make('wilaya_id')
                    ->maxLength(50),
                Forms\Components\TextInput::make('commune_id')
                    ->maxLength(50),
                Forms\Components\TextInput::make('wilaya_name')
                    ->maxLength(50),
                Forms\Components\TextInput::make('commune_name')
                    ->maxLength(50),
                Forms\Components\TextInput::make('is_wilaya')
                    ->maxLength(50),
                Forms\Components\TextInput::make('is_commune')
                    ->maxLength(50),
                Forms\Components\TextInput::make('is_center')
                    ->maxLength(50),
                Forms\Components\TextInput::make('home')
                    ->maxLength(50),
                Forms\Components\TextInput::make('desk')
                    ->maxLength(50),
                Forms\Components\TextInput::make('retour')
                    ->maxLength(50),
                Forms\Components\TextInput::make('is_deliverable')
                    ->maxLength(50),
                Forms\Components\TextInput::make('has_stop_desk')
                    ->maxLength(50),
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('company_name')
                    ->label('Transporteur')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('wilaya_id')
                //     ->label('Code Wilaya')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('commune_id')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('wilaya_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('commune_name')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('is_wilaya')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('is_commune')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('is_center')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('home')
                    ->label('Domicile')
                    ->searchable(),
                Tables\Columns\TextColumn::make('desk')
                    ->label('Stop Desk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('retour')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('is_deliverable')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('has_stop_desk')
                //     ->searchable(),
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
                //         return collect((new AlgeriaCities())->get_all_communs_options());
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
            // 'edit' => Pages\EditDelivery::route('/{record}/edit'),
        ];
    }
}
