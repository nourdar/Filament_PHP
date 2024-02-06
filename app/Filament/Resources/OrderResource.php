<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\Settings;
use Filament\Forms\Form;
use App\Enums\OrderStatus;
use Filament\Tables\Table;
use App\Models\ProductMesure;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use App\Http\Controllers\AlgeriaCities;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\HelperController;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Wizard\Step;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\YalidineController;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\CustomerRelationManager;

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
                                ->default(random_int(10000, 99999999))
                                ->disabled()
                                ->dehydrated()
                                ->required(),

                            Select::make('status')
                                ->label('Statut de la Commande')
                                ->placeholder('Selectionner le status')
                                ->default('placed')
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

                            Select::make('customer_id')
                                ->label('Nom du client')
                                ->placeholder('Rechercher un client')
                                ->relationship('customer', 'name')
                                ->required()

                                ->getSearchResultsUsing(fn (string $search): array => Customer::where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%")->limit(50)->pluck('name', 'id')->toArray())
                                ->createOptionForm([
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


                                    ]),
                                    Section::make([

                                        Select::make('address')
                                            ->label('Wilaya')
                                            ->searchable()
                                            ->reactive()
                                            ->options(function () {
                                                return (new AlgeriaCities())->get_all_wilayas();
                                            }),

                                        Select::make('city')
                                            ->label('Commune')
                                            ->searchable()
                                            ->hidden(fn ($get) => $get('address') === null)
                                            ->options(function ($get) {
                                                return (new AlgeriaCities())->get_all_communes($get('address'));
                                            }),


                                    ])

                                ])
                                ->searchable(),



                            // Section::make('')->schema([

                            //     Placeholder::make('product_name')
                            //         ->label('Produits')

                            //         ->content(function ($record) {
                            //             return new HtmlString(HelperController::get_product_name_from_form_record($record));
                            //         }),

                            //     Placeholder::make('product_price')
                            //         ->label('Prix Produit')
                            //         ->content(function ($record) {
                            //             if (!isset($record?->items[0])) {
                            //                 return '';
                            //             }
                            //             return number_format(($record->items[0]['unit_price']), 0) . ' DZD';
                            //         }),

                            //     Placeholder::make('product_qte')
                            //         ->label('Qantite')
                            //         ->content(function ($record) {
                            //             if (!isset($record?->items[0])) {
                            //                 return '';
                            //             }
                            //             return $record->items[0]['quantity'];
                            //         }),

                            //     Placeholder::make('shipping_price')
                            //         ->label('Coût de livraison')
                            //         ->content(function ($record) {
                            //             return number_format($record?->shipping_price, 0) . ' DZD';
                            //         }),



                            //     Placeholder::make('total_price')
                            //         ->label('Prix Total')
                            //         ->content(function ($record) {
                            //             if (!isset($record?->items[0])) {
                            //                 return '';
                            //             }
                            //             return number_format(($record->items[0]['quantity'] * $record->items[0]['unit_price']) + $record->shipping_price, 0) . ' DZD';
                            //         }),

                            //     Placeholder::make('shipping_type')
                            //         ->label('Type de livraison')
                            //         ->content(function ($record) {
                            //             return  $record?->shipping_type;
                            //         }),

                            //     Placeholder::make('wilaya')
                            //         ->label('Wilaya')
                            //         ->content(function ($record) {
                            //             return (new AlgeriaCities())->get_wilaya_name($record?->customer?->address);
                            //             // return  (new AlgeriaCities())->get_all_wilayas()[$record->customer->address];


                            //         }),

                            //     Placeholder::make('commune')
                            //         ->label('Commune')
                            //         ->content(function ($record) {
                            //             return  $record?->customer?->city;
                            //         }),

                            //     Placeholder::make('tracking')
                            //         ->label('Tracking')
                            //         ->content(function ($record) {
                            //             return  $record?->tracking;
                            //         }),


                            // ])->columns(4),


                            Select::make('transport_provider')
                                ->label('Transporteur')

                                ->options(function () {
                                    $options = [];
                                    $settings =  Settings::first();
                                    foreach ($settings->transport as $transport) {

                                        if ($transport['is_active'] && !empty($transport['provider'])) {
                                            array_push($options, $transport['provider']);
                                        }
                                    }
                                    return $options;
                                })
                                ->searchable(),

                            TextInput::make('total_price')
                                ->label('Total de la commande')
                                ->numeric()
                                ->dehydrated(),

                            Checkbox::make('is_free_shipping')
                                ->label('Livraison Gratuite ?'),

                            TextInput::make('shipping_price')
                                ->label('Coût de livraison')
                                ->numeric()
                                ->dehydrated(),

                            TextInput::make('tracking')
                                ->label('Tracking'),





                            Select::make('shipping_type')
                                ->label('Type de livraison')
                                ->default('desk')
                                ->options([
                                    'desk' => 'Stop Desk',
                                    'home' => 'Domicile',

                                ])
                                ->required()
                                ->searchable(),

                            MarkdownEditor::make('notes')
                                ->columnSpan('full')

                        ])->columns(3),

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
                                        ->searchable()
                                        ->reactive()
                                        ->afterStateUpdated(
                                            fn ($state, Forms\Set $set) =>
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

                                    Repeater::make('options')->schema(function () {

                                        return HelperController::get_product_mesures_options();
                                    })

                                        ->defaultItems(1)
                                        ->deletable(false)
                                        ->addable(true)
                                        ->columnSpan('full')
                                        ->columns(3)

                                        ->reorderable(false)
                                        ->maxItems(1),

                                    // Placeholder::make('total_price')
                                    //     ->label('Prix Total')
                                    //     ->content(function ($get) {
                                    //         return number_format($get('quantity') * $get('unit_price'), 2) . ' DZD';
                                    //     })

                                ])


                                ->columns(3)

                        ]),

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
                        default => '',
                    })
                    ->getStateUsing(function ($record) {
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
                    ->getStateUsing(function ($record) {

                        // dd (new AlgeriaCities())->get_wilaya_name('setif');

                        $wilaya = (new AlgeriaCities())->get_wilaya_name($record?->customer?->address);
                        return  new HtmlString($wilaya . ' <br> | ' . $record?->customer?->city);
                    })
                    ->label('Address')
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
                    ->color(fn ($record): string => match ($record?->shipping_type) {
                        'desk' => 'warning',
                        'home' => 'success',
                        default => '',
                    })
                    ->getStateUsing(function ($record) {

                        $item = $record?->shipping_type == 'home' ? 'home' : 'desk';

                        switch ($record?->shipping_type) {
                            case 'home':
                                $item = "Domicile";
                                break;
                            case 'desk':
                                $item =  "Stop Desk";
                                break;
                        }

                        if (!empty($record?->transport_provider)) {
                            $item .= '<br> Cout de livraison : ' . number_format($record?->shipping_price, 0);
                        }

                        if (!empty($record?->transport_provider)) {
                            $item .= '<br> | ' . strtoupper($record?->transport_provider);
                        }

                        if (!empty($record?->tracking)) {
                            $item .= '<br> | ' . $record?->tracking;
                        }
                        return new HtmlString($item);
                    })
                    ->toggleable()
                    ->sortable(),

                TextColumn::make('order.items')
                    ->label('Produit')
                    ->getStateUsing(function ($record) {
                        return HelperController::get_product_name_from_form_record($record);
                    })
                    // ->limit(40)
                    ->html()
                    ->toggleable()
                    ->sortable(),

                TextColumn::make('total_price')
                    ->label('Total')
                    // ->getStateUsing(function ($record) {
                    //     return $record?->items[0]?->product?->price;
                    // })
                    ->toggleable()
                    ->sortable(),


            ])->defaultSort('created_at', 'desc')



            ->filters([
                //
            ])
            ->actions([



                ActionGroup::make([


                    Tables\Actions\Action::make('add_to_yalidine')
                        ->label('Ajouter a Yalidine')
                        ->color('danger')
                        ->visible(DeliveryController::check_active('yalidine'))
                        ->icon('heroicon-o-truck')
                        ->action(function ($record) {
                            $delivery = new DeliveryController();
                            $delivery->add_order_to_yalidine($record);
                        }),

                    Tables\Actions\Action::make('add_to_noest')
                        ->label('Ajouter a Nord et west')
                        ->color('secondary')
                        ->visible(DeliveryController::check_active('nord_et_west'))
                        ->icon('heroicon-o-truck')
                        ->action(function ($record) {
                            $delivery = new DeliveryController();
                            $delivery->add_order_to_noest($record);
                        }),


                    Tables\Actions\Action::make('add_to_zrexpress')
                        ->label('Ajouter a ZR-Express')
                        ->color('warning')
                        ->visible(DeliveryController::check_active('zrexpress'))
                        ->icon('heroicon-o-truck')
                        ->action(function ($record) {
                            $delivery = new DeliveryController();

                            $delivery->add_order_to_zrexpress($record);
                        }),
                    Tables\Actions\EditAction::make()
                        ->label('Modifier'),

                    Tables\Actions\DeleteAction::make()
                        ->label('Supprimer'),


                    Tables\Actions\Action::make('activities')
                        ->label('Historique')
                        ->icon('heroicon-o-document')
                        ->url(fn ($record) => OrderResource::getUrl('activities', ['record' => $record])),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('add_to_delivery')
                        ->label('Ajouter a Yalidine')
                        ->icon('heroicon-o-truck')
                        ->color('danger')
                        ->visible(function () {
                            $settings = Settings::first();

                            if (isset($settings['transport'])) {
                                foreach ($settings['transport'] as $transport) {
                                    if (isset($transport['provider'])) {
                                        if (strtolower($transport['provider']) == 'yalidine' &&  $transport['is_active']) {
                                            return true;
                                        }
                                    }
                                }
                            }
                        })
                        ->action(function ($records) {

                            $delivery = new DeliveryController();

                            foreach ($records as $record) {
                                $delivery->add_order_to_yalidine($record);
                            }
                        }),


                    Tables\Actions\BulkAction::make('add_to_zrexpress')
                        ->label('Ajouter a ZR-Express')
                        ->color('warning')
                        ->icon('heroicon-o-truck')
                        ->visible(function () {
                            $settings = Settings::first();

                            if (isset($settings['transport'])) {
                                foreach ($settings['transport'] as $transport) {
                                    if (isset($transport['provider'])) {
                                        if (strtolower($transport['provider']) == 'zrexpress' &&  $transport['is_active']) {
                                            return true;
                                        }
                                    }
                                }
                            }
                        })
                        ->action(function ($records) {

                            $delivery = new DeliveryController();

                            foreach ($records as $record) {
                                $delivery->add_order_to_zrexpress($record);
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
            CustomerRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'activities' => Pages\OrderActivities::route('/{record}/activities'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
