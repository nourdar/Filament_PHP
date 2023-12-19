<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
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

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = "Ma Boutique";

    protected static ?string $navigationLabel = 'Commandes';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Détails de la commande')
                        ->schema([
                            TextInput::make('order_number')
                                ->label('Numéro de commande')
                                ->default("Commande#".random_int(10000, 99999))
                                ->disabled()
                                ->dehydrated()
                                ->required(),

                            Select::make('customer_id')
                                ->label('Nom du client')
                                ->relationship('customer', 'name')
                                ->required()
                                ->searchable(),

                            Select::make('type')
                                ->label('Statut de la Commande')
                                ->options([
                                    'pending' => OrderStatus::PENDING->value,
                                    'processing' => OrderStatus::PROCESSING->value,
                                    'completed' => OrderStatus::COMPLETED->value,
                                    'declined' => OrderStatus::DECLINED->value,
                                ])
                                ->required()
                                ->searchable()->columnSpan('full'),

                            MarkdownEditor::make('description')
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
                                        ->options(Product::query()->pluck('name', 'id'))
                                        ->required(),

                                    TextInput::make('quantity')
                                        ->label('Quantité')
                                        ->default(1)
                                        ->numeric()
                                        ->required(),

                                    TextInput::make('unit_price')
                                        ->label('Prix Unitaire')
                                        ->numeric()
                                        ->dehydrated()
                                        ->disabled()
                                        ->required(),

                                        ])->columns(3)

                        ]),
                ])->columnSpan('full')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('customer.name')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),

                TextColumn::make('status')
                    ->searchable()
                    ->sortable(),

                    TextColumn::make('total_price')
                    ->searchable()
                    ->sortable()
                    ->summarize([
                        Sum::make()->money(),
                    ]),

                    TextColumn::make('created_at')
                        ->label('Date de Commande')
                        ->date(),
            ])
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
