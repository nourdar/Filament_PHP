<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Enums\ProductType;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\ProductMesure;
use Filament\Resources\Resource;
use function Laravel\Prompts\text;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\HelperController;
use Filament\Forms\Components\Wizard\Step;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $navigationLabel = 'Produits';

    protected static ?string $navigationGroup = 'Ma Boutique';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    protected static int $globalSearchResultLimit = 10;

    protected static ?string $activeNavigationIcon = "heroicon-o-check-badge";


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() < 5
            ? 'warning'
            : 'primary';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'slug', 'description'];
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'Marque' => $record->brand->name,
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['brand']);
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Détails Préalable')
                        ->schema([
                            TextInput::make('name')
                                ->label('Designation')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                    // dd($operation);
                                    if ($operation !== 'create') {
                                        return;
                                    }

                                    $set('slug', Str::slug($state));
                                }),
                            Hidden::make('slug')
                                ->label('Slug du produit')
                                ->required()
                                ->disabled()
                                ->dehydrated()
                                ->unique(Product::class, 'slug', ignoreRecord: true),

                            TextInput::make('price')
                                ->minValue(1)
                                ->numeric()
                                ->label('Prix')
                                ->rules('regex:/^\d{1,6}(\.\d{0,2})?$/')
                                ->required(),

                            TextInput::make('old_price')
                                ->minValue(1)
                                ->numeric()
                                ->label('Ancien Prix')
                                ->rules('regex:/^\d{1,6}(\.\d{0,2})?$/'),


                            Hidden::make('quantity')
                                ->label('Quantite')
                                ->default(1),

                            Hidden::make('type')->default('deliverable'),
                            Hidden::make('description'),


                            RichEditor::make('description')
                                ->label('Description')

                                ->columnSpan('full'),

                            Repeater::make('translations')->schema([


                                RichEditor::make('description_arabic')
                                    ->label('الوصف باللغة العربية')

                                    ->columnSpan('full'),

                                TextInput::make('landing_image_link')->label('Landing page Image Link'),

                                FileUpload::make('image_landing')
                                    ->label('Landing page Image')
                                    ->directory('form-attachments')
                                    ->image(),

                            ])
                                ->columnSpan('full')
                                ->defaultItems(1)
                                ->maxItems(1)
                                ->reorderableWithButtons()
                                // ->addable(false)
                                ->deletable(false)

                        ])->columns(3),

                    // Step::make('Prix et Quantités')
                    //     ->schema([
                    // TextInput::make('sku')
                    //     ->label('SKU (Unité de gestion des stocks)')
                    //     ->unique(),


                    // Select::make('type')->options([
                    //     'deliverable' => ProductType::DELIVERABLE->value,
                    //     'downloadable' => ProductType::DOWNLOADABLE->value,
                    // ]),

                    // ])->columns(2),

                    Step::make('Status du Produit')
                        ->schema([
                            Toggle::make('is_visible')
                                ->helperText("Si ce champ est désactivé, le produit ne sera pas visible sur la page d'accueil.")
                                ->label('Visibilité')
                                ->default(true)
                                ->required(),



                            Repeater::make('mesures')->schema(function () {
                                return HelperController::get_product_mesures_options(true);
                            })
                                ->defaultItems(1)
                                ->deletable(false)
                                ->addable(true)
                                ->reorderable(false)
                                ->maxItems(1),





                            // Section::make('')->schema(function($state){

                            //     $columns = [];

                            //     $mesures = ProductMesure::all();

                            //     foreach($mesures as $mesure){

                            //         dd($state);

                            //             foreach(collect($mesure->options)->pluck('option', 'option') as $key => $value)
                            //             {
                            //                 $column =  Checkbox::make($key)->default(function() use($state, $mesure){
                            //                     if(isset($state['mesures'][$mesure->mesure])){
                            //                       return true;
                            //                     }
                            //                 });
                            //                 array_push($columns, $column);
                            //             }
                            //     }
                            //     // dd($columns);
                            //     return $columns;
                            // })->columns(3),


                            // Toggle::make('is_featured')
                            //     ->label('En vedette')
                            //     ->helperText("Les produits en vedette apparaissent sur la page d'accueil.
                            //                     Si vous souhaitez promouvoir un produit, cochez cette case-ci."),
                            Hidden::make('published_at')->default(now()),
                            // DatePicker::make('published_at')
                            //     ->label('Date de Publication')
                            //     ->default(now()),
                        ]),

                    Step::make('Image')
                        ->schema([
                            FileUpload::make('image')
                                ->directory('form-attachments')

                                ->imageEditor(),

                            FileUpload::make('images')
                                ->label('Autre images')
                                ->directory('form-attachments')

                                ->multiple()
                                ->imageEditor(),

                            Repeater::make('videos')->schema([
                                TextInput::make('link')->label('Lien YouTube'),
                            ]),

                        ]),

                    Step::make('Marque et Catégories')
                        ->schema([
                            Select::make('brand_id')
                                ->placeholder('Selectionner une marque')
                                ->label('Marque')
                                ->relationship('brand', 'name'),

                            Select::make('category_id')
                                ->placeholder('Selectionner une catégorie')
                                ->label('Catégories')
                                ->multiple()

                                ->relationship('categories', 'name')
                        ])

                ])
                    ->skippable()
                    ->columnSpan('full')

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('brand.name')
                    ->label('Nom de la marque')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                ToggleColumn::make('is_visible')
                    ->label('Visibilité')

                    ->toggleable(),
                TextColumn::make('price')
                    ->label('Prix')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('old_price')
                    ->label('Ancien Prix')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                // TextColumn::make('quantity')
                //     ->label('Quantité')
                //     ->searchable()
                //     ->sortable()
                //     ->toggleable(),
                TextColumn::make('published_at')
                    ->label('Date de publication')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                // TextColumn::make('type'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('is_visible')
                    ->label("Visibilité")
                    ->placeholder("Choisir un mode")
                    ->boolean()
                    ->trueLabel("Seulement les produits visibles")
                    ->falseLabel("Seulement les produits masqués")
                    ->native(false),
                SelectFilter::make('brand')
                    ->label("Marque")
                    ->placeholder("Selectionner une marque")
                    ->relationship('brand', 'name'),

            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->label('Modifier'),
                    Tables\Actions\Action::make('view')
                        ->label('Voir dans la boutique')
                        ->url(fn ($record): string => route('product.show', $record))
                        ->openUrlInNewTab()

                        ->icon('heroicon-o-link'),
                    Tables\Actions\DeleteAction::make()
                        ->label('Supprimer'),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->paginated([10, 25, 50, 100, 'Toutes']);
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
