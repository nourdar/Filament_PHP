<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Stock;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProductMesure;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\StockResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StockResource\RelationManagers;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class StockResource extends Resource
{
    protected static ?string $model = Stock::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $navigationLabel = 'Stock';

    protected static ?int $navigationSort = 8;

    protected static ?string $navigationGroup = "Ma Boutique";

    protected static  ?string $label = 'Stock';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Stock')->schema([
                    Select::make('move_type')
                        ->label('Mouvement')

                        ->options([
                            'in'    => 'Reception',
                            'out'    => 'Sortir',
                        ])
                        ->default('in')
                        ->required()
                        ->searchable(),

                    Select::make('type')
                        ->label('Type de stock')

                        ->options([
                            'shipping'    => 'Shipping',
                            'physique'    => 'Physique',
                        ])
                        ->default('physique')
                        ->required()
                        ->searchable(),

                ])->columns(2),
                Section::make('Produit')->schema([
                    Select::make('product_id')
                        ->label('Produit')
                        ->placeholder('Selectionner un produit')
                        ->options(Product::query()->pluck('name', 'id'))
                        ->required()
                        ->searchable(),

                    TextInput::make('quantity')->label('quantite'),

                ])->columns(2),



                Repeater::make('mesures')->schema(function ($state) {
                    // dd($state);
                    $columns = [];

                    $mesures = ProductMesure::all();

                    foreach ($mesures as $mesure) {

                        $column =  Select::make($mesure->mesure)
                            ->searchable()
                            ->options(collect($mesure->options)->pluck('option', 'option'));

                        array_push($columns, $column);
                    }

                    return $columns;
                })
                    ->columns(3)
                    ->columnSpan('full')
                    ->defaultItems(1)
                    ->deletable(false)
                    ->addable(true)
                    ->reorderable(false)
                    ->maxItems(1),



                // Forms\Components\TextInput::make('product_id')
                //     ->required()
                //     ->maxLength(255),
                // Forms\Components\TextInput::make('product_name')
                //     ->required()
                //     ->maxLength(255),
                // Forms\Components\TextInput::make('order_id')
                //     ->required()
                //     ->maxLength(255),
                // Forms\Components\TextInput::make('order_item_id')
                //     ->required()
                //     ->maxLength(255),
                // Forms\Components\TextInput::make('type')
                //     ->required()
                //     ->maxLength(255)
                //     ->default('shipped'),
                // Forms\Components\TextInput::make('move_type')
                //     ->maxLength(255),
                // Forms\Components\TextInput::make('qte')
                //     ->maxLength(250),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('product.name')
                    ->label('Produit')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('order_id')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('order_item_id')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('move_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('qte')
                    ->searchable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListStocks::route('/'),
            'create' => Pages\CreateStock::route('/create'),
            // 'edit' => Pages\EditStock::route('/{record}/edit'),
        ];
    }
}
