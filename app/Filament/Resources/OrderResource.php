<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Http\Controllers\AlgeriaCities;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Commandes';

    protected static ?int $navigationSort = 0;

    protected static ?string $navigationGroup = "Ma Boutique";

    protected static ?string $activeNavigationIcon = "heroicon-o-check-badge";


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'placed')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status', 'processing')
                    ->count() > 5
                    ? 'warning'
                    : 'primary';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Détails de la commande')
                        ->schema([
                            TextInput::make('order_number')
                                ->label('Numéro de commande')
                                ->default(random_int(10000, 99999))
                                ->disabled()
                                ->dehydrated()
                                ->required(),

                            Select::make('customer_id')
                                ->label('Nom du client')
                                ->placeholder('Rechercher un client')
                                ->relationship('customer', 'name')
                                ->required()
                                ->searchable(),

                            TextInput::make('shipping_price')
                                ->label('Coût de livraison')
                                ->numeric()
                                ->dehydrated()
                                ->required(),

                            Select::make('status')
                                ->label('Statut de la Commande')
                                ->placeholder('Selectionner le status')
                                ->options([
                                    'placed' => OrderStatus::PLACED->value,
                                    'confirmed' => OrderStatus::CONFIRMED->value,
                                    'processing' => OrderStatus::PROCESSING->value,
                                    'shipped' => OrderStatus::SHIPPED->value,
                                    'paid' => OrderStatus::PAID->value,
                                    'declined' => OrderStatus::DECLINED->value,
                                    'back' => OrderStatus::BACK->value,
                                ])
                                ->required()
                                ->searchable(),

                            MarkdownEditor::make('notes')
                            ->columnSpan('full')

                        ])->columns(2),

                    Step::make('Articles de la Commande')
                        ->schema([
                            Repeater::make('items')
                                ->label("Articles")
                                ->relationship()
                                ->schema([
                                    Select::make('product_id')
                                        ->label('Produit')
                                        ->placeholder('Selectionner un produit')
                                        ->options(Product::query()->pluck('name', 'id'))
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(fn($state, Forms\Set $set) =>
                                            $set('unit_price', Product::find($state)?->price ?? 0)
                                        ),

                                    TextInput::make('quantity')
                                        ->label('Quantité')
                                        ->default(1)
                                        ->minValue(1)
                                        ->numeric()
                                        ->live()
                                        ->dehydrated()
                                        ->required(),

                                    TextInput::make('unit_price')
                                        ->label('Prix Unitaire')
                                        ->numeric()
                                        ->dehydrated()
                                        ->required(),

                                    Placeholder::make('total_price')
                                        ->label('Prix Total')
                                        ->content(function ($get){
                                            return number_format($get('quantity') * $get('unit_price'), 2).' DZD';
                                        })

                                ])

                                ->columns(4)

                        ]),
                ])
                ->skippable()
                ->columnSpan('full')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make('order_number')
                //     ->label('Numéro de commande')
                //     ->searchable()
                //     ->sortable(),


                TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'placed' => 'gray',
                    'processing' => 'warning',
                    'confirmed' => 'success',
                    'paid' => 'success',
                    'shipped' => 'success',
                    'back' => 'danger',
                    'declined' => 'danger',
                })
                ->searchable()
                ->sortable(),


                TextColumn::make('customer.name')
                    ->label('Nom du client')

                    ->searchable()
                    ->toggleable()
                    ->sortable(),

                    TextColumn::make('customer.phone')
                    ->searchable()
                        ->label('Contact'),


                        TextColumn::make('customer.address')
                        ->getStateUsing(function($record){

                            return  (new AlgeriaCities())->get_all_wilayas()[$record->customer->address];
                        })
                        ->label('Wilaya')
                        ->searchable()
                        ->toggleable()
                        ->sortable(),

                        TextColumn::make('customer.city')
                        ->label('Commune')
                        ->searchable()
                        ->toggleable()
                        ->sortable(),

                        TextColumn::make('shipping_type')
                        ->label('Livraison')
                        ->searchable()
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'desk' => 'warning',
                            'home' => 'success',
                        })
                        ->toggleable()
                        ->sortable(),

                        TextColumn::make('order.items')
                        ->label('Produit')
                        ->getStateUsing(function($record){
                            return $record?->items[0]?->product?->name;
                        })
                        ->toggleable()
                        ->sortable(),

                        TextColumn::make('item.unit_price')
                        ->label('Prix de produit')
                        ->getStateUsing(function($record){
                            return $record?->items[0]?->product?->price;
                        })
                        ->toggleable()
                        ->sortable(),


                        TextColumn::make('shipping_price')
                        ->label('Prix de livraison')

                        ->toggleable()
                        ->sortable(),




            ])->defaultSort('created_at', 'desc')



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
                    ExportBulkAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
