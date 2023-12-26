<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\Product;
use App\Models\Settings;
use Filament\Forms\Form;
use App\Enums\OrderStatus;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use App\Http\Controllers\AlgeriaCities;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Wizard\Step;
use App\Http\Controllers\YalidineController;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Http\Controllers\DeliveryController;
use App\Models\Delivery;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Commandes';

    protected static ?int $navigationSort = 0;

    protected static ?string $navigationGroup = "Ma Boutique";

    protected static  ?string $label = 'Commandes';

    protected static ?string $activeNavigationIcon = "heroicon-o-check-badge";


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'placed')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status', 'placed')
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



                            Section::make('')->schema([

                                Placeholder::make('product_name')
                                ->label('Produit')
                                ->content(function ($record){

                                    return $record->items[0]->product->name ;
                                }),

                                Placeholder::make('product_price')
                                ->label('Prix Produit')
                                ->content(function ($record){
                                    return number_format(( $record->items[0]['unit_price'] ), 0).' DZD';
                                }),

                                Placeholder::make('product_qte')
                                ->label('Qantite')
                                ->content(function ($record){
                                    return $record->items[0]['quantity'];
                                }),

                                Placeholder::make('shipping_price')
                                ->label('Coût de livraison')
                                ->content(function ($record){
                                    return number_format( $record->shipping_price , 0).' DZD';
                                }),



                            Placeholder::make('total_price')
                                        ->label('Prix Total')
                                        ->content(function ($record){
                                            return number_format(($record->items[0]['quantity'] * $record->items[0]['unit_price'] ) + $record->shipping_price , 0).' DZD';
                                        }),

                                        Placeholder::make('shipping_type')
                                        ->label('Type de livraison')
                                        ->content(function ($record){
                                            return  $record->shipping_type ;
                                        }),

                                        Placeholder::make('wilaya')
                                        ->label('Wilaya')
                                        ->content(function ($record){
                                            return (new AlgeriaCities())->get_wilaya_name($record->customer->address);
                                            // return  (new AlgeriaCities())->get_all_wilayas()[$record->customer->address];


                                        }),

                                        Placeholder::make('commune')
                                        ->label('Commune')
                                        ->content(function ($record){
                                            return  $record->customer->city ;
                                        }),

                                        Placeholder::make('tracking')
                                        ->label('Tracking')
                                        ->content(function ($record){
                                            return  $record->tracking ;
                                        }),


                            ])->columns(4),



                            TextInput::make('shipping_price')
                                ->label('Coût de livraison')
                                ->numeric()
                                ->dehydrated(),

                            TextInput::make('tracking')
                            ->label('Tracking'),

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

                    // Step::make('Articles de la Commande')
                    //     ->schema([
                    //         Repeater::make('items')
                    //             ->label("Articles")
                    //             ->relationship()
                    //             ->schema([


                    //                 Select::make('product_id')
                    //                     ->label('Produit')
                    //                     ->placeholder('Selectionner un produit')
                    //                     ->options(Product::query()->pluck('name', 'id'))
                    //                     ->required()
                    //                     ->reactive()
                    //                     ->afterStateUpdated(fn($state, Forms\Set $set) =>
                    //                         $set('unit_price', Product::find($state)?->price ?? 0)
                    //                     ),

                    //                 TextInput::make('quantity')
                    //                     ->label('Quantité')
                    //                     ->default(1)
                    //                     ->minValue(1)
                    //                     ->numeric()
                    //                     ->live()
                    //                     ->dehydrated()
                    //                     ->required(),

                    //                 TextInput::make('unit_price')
                    //                     ->label('Prix Unitaire')
                    //                     ->numeric()
                    //                     ->dehydrated()
                    //                     ->required(),

                    //                 Placeholder::make('total_price')
                    //                     ->label('Prix Total')
                    //                     ->content(function ($get){
                    //                         return number_format($get('quantity') * $get('unit_price'), 2).' DZD';
                    //                     })

                    //             ])
                    //             ->maxItems(1)
                    //             ->columns(4)

                    //     ]),

                        // Step::make('Livraison')
                        // ->schema([
                        //     TextInput::make('order.customer.address')->label('Wilaya'),
                        //     TextInput::make('order.customer.city')->label('Commune'),


                        // ]),
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
                    'Commande passée' => 'gray',
                    'En cours de traitement' => 'warning',
                    'Confirmé' => 'success',
                    'payé' => 'success',
                    'Livré' => 'success',
                    'Retour' => 'danger',
                    'Annulé' => 'danger',
                })
                ->getStateUsing( function ($record){
                    switch ($record->status) {
                        case 'placed':
                             return "Commande passée";
                            break;
                        case 'processing':
                            return "En cours de traitement";
                            break;
                        case 'confirmed':
                            return "Confirmé";
                            break;
                        case 'paid':
                            return "payé";
                            break;
                        case 'shipped':
                            return "Livré";
                            break;
                        case 'back':
                            return "Retour";
                            break;
                        case 'declined':
                            return "Annulé";
                            break;

                    }
                 })
                ->searchable()
                ->sortable(),


                TextColumn::make('customer.name')
                    ->label('Nom du client')

                    ->searchable()
                    ->toggleable()
                    ->sortable(),

                    ViewColumn::make('customer.phone')
                        ->label('Contact')
                        ->view('Admin.customer-phone-column'),

                    // TextColumn::make('customer.phone')
                    // ->searchable()
                    //     ->label('Contact'),


                        TextColumn::make('customer.address')
                        ->getStateUsing(function($record){

                            // dd (new AlgeriaCities())->get_wilaya_name('setif');

                            $wilaya = (new AlgeriaCities())->get_wilaya_name($record->customer->address);
                            return  $wilaya.' - '. $record->customer->city;
                        })
                        ->label('Wilaya')
                        ->searchable()
                        ->toggleable()
                        ->sortable(),

                        // TextColumn::make('customer.city')
                        // ->label('Commune')
                        // ->searchable()
                        // ->toggleable()
                        // ->sortable(),

                        TextColumn::make('shipping_type')
                        ->label('Livraison')
                        ->searchable()
                        ->badge()
                        ->color(fn ($record): string => match ($record->shipping_type) {
                            'desk' => 'warning',
                            'home' => 'success',
                        })
                        ->getStateUsing( function ($record){

                            $item = $record->shipping_type == 'home' ? 'home' : 'desk';

                            switch ($record->shipping_type) {
                                case 'home':
                                     $item = "Domicile";
                                    break;
                                case 'desk':
                                    $item =  "Stop Desk";
                                    break;
                            }
                            if(!empty($record->tracking)){
                                $item .= ' | '.$record->tracking;
                            }
                            return $item;
                         })
                        ->toggleable()
                        ->sortable(),

                        TextColumn::make('order.items')
                        ->label('Produit')
                        ->getStateUsing(function($record){

                            $product = $record?->items[0]?->product?->name;

                            if($record?->items[0]?->options){
                                foreach($record?->items[0]?->options as $key => $value){

                                    if(is_string($value) && is_string($key)){

                                        $product .=' '.$key.' : '.$value;
                                    }
                                }
                                // dd( $product = $record?->items[0]?->product);
                            }
                            return $product;
                        })
                        ->toggleable()
                        ->sortable(),

                        // TextColumn::make('item.unit_price')
                        // ->label('Prix de produit')
                        // ->getStateUsing(function($record){
                        //     return $record?->items[0]?->product?->price;
                        // })
                        // ->toggleable()
                        // ->sortable(),


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
                    Tables\Actions\Action::make('add_to_delivery')
                        ->label('Ajouter a Yalidine')
                        ->icon('heroicon-o-truck')
                        ->action(function($record){
                            $delivery = new DeliveryController();

                            $delivery->add_order_to_yalidine($record);

                        }),
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
                    Tables\Actions\BulkAction::make('add_to_delivery')
                    ->label('Ajouter a Yalidine')
                    ->icon('heroicon-o-truck')
                    ->action(function($records){

                        $delivery = new DeliveryController();

                        foreach($records as $record){
                            $delivery->add_order_to_yalidine($record);
                        }

                    }),
                    ExportBulkAction::make()->label('Exporté Excel'),
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
