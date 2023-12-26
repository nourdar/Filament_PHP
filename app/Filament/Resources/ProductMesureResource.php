<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProductMesure;
use Filament\Resources\Resource;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductMesureResource\Pages;
use App\Filament\Resources\ProductMesureResource\RelationManagers;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;

class ProductMesureResource extends Resource
{
    protected static ?string $model = ProductMesure::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil';

    protected static ?int $navigationSort = 2;

    protected static  ?string $navigationLabel = 'Mesures';

    protected static  ?string $label = 'Mesures des produits';

    protected static ?string $navigationGroup = "Ma Boutique";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('mesure')->label('Mesure - Par example : Taille, Couleur ...etc'),

                Repeater::make('options')->schema([
                    TextInput::make('option'),
                ]) ->defaultItems(3)
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mesure')
                    ->searchable(),

                TextColumn::make('options.option')
                ->default(function($record){
                    $mesures = '';
                    foreach($record?->options as $option) {

                        $mesures .= $option['option'].' - ';
                    }
                    $mesures = rtrim($mesures, '- ');
                    return $mesures;
                })


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
            'index' => Pages\ListProductMesures::route('/'),
            'create' => Pages\CreateProductMesure::route('/create'),
            'edit' => Pages\EditProductMesure::route('/{record}/edit'),
        ];
    }
}
