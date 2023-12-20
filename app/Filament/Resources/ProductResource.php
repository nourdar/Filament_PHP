<?php

namespace App\Filament\Resources;

use App\Enums\ProductType;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Str;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $navigationLabel = 'Produits';

    protected static ?string $navigationGroup = 'Ma Boutique';

    protected static ?int $navigationSort = 0;

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
                                ->label('Nom')
                                ->required()
                                ->live(onBlur:true)
                                ->unique()
                                ->afterStateUpdated(function(string $operation, $state, Forms\Set $set) {
                                    // dd($operation);
                                    if($operation !== 'create'){
                                        return;
                                    }

                                    $set('slug', Str::slug($state));
                                }),
                            TextInput::make('slug')
                                ->label('Slug du produit')
                                ->required()
                                ->disabled()
                                ->dehydrated()
                                ->unique(Product::class, 'slug', ignoreRecord:true),
                            MarkdownEditor::make('description')
                                ->label('Description')
                                ->columnSpan('full')
                        ])->columns(2),

                    Step::make('Prix et Quantités')
                        ->schema([
                            TextInput::make('sku')
                                ->label('SKU (Unité de gestion des stocks)')
                                ->unique()
                                ->required(),

                            TextInput::make('price')
                                ->minValue(1)
                                ->numeric()
                                ->label('Prix')
                                ->rules('regex:/^\d{1,6}(\.\d{0,2})?$/')
                                ->required(),
                            TextInput::make('quantity')
                                ->numeric()
                                ->label('Quantité')
                                ->minValue(0)
                                ->maxValue(100)
                                ->required(),

                            Select::make('type')->options([
                                'deliverable' => ProductType::DELIVERABLE->value,
                                'downloadable' => ProductType::DOWNLOADABLE->value,
                            ]),

                        ])->columns(2),

                    Step::make('Status du Produit')
                        ->schema([
                            Toggle::make('is_visible')
                                ->helperText("Si ce champ est désactivé, le produit ne sera pas visible sur la page d'accueil.")
                                ->label('Visibilité')
                                ->default(true)
                                ->required(),
                            Toggle::make('is_featured')
                                ->label('En vedette')
                                ->helperText("Les produits en vedette apparaissent sur la page d'accueil.
                                                Si vous souhaitez promouvoir un produit, cochez cette case-ci."),
                            DatePicker::make('published_at')
                                ->label('Date de Publication')
                                ->default(now()),
                        ]),

                    Step::make('Image')
                        ->schema([
                            FileUpload::make('image')
                                ->directory('form-attachments')
                                ->preserveFilenames()
                                ->image()
                                ->imageEditor(),

                        ]),

                    Step::make('Marque et Catégories')
                        ->schema([
                            Select::make('brand_id')
                                ->placeholder('Selectionner une marque')
                                ->label('Marque')
                                ->required()
                                ->relationship('brand', 'name'),

                            Select::make('category_id')
                                ->placeholder('Selectionner une catégorie')
                                ->label('Catégories')
                                ->multiple()
                                ->required()
                                ->relationship('categories', 'name')
                        ])

                ])->columnSpan('full')

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
                IconColumn::make('is_visible')
                    ->label('Visibilité')
                    ->boolean()
                    ->toggleable(),
                TextColumn::make('price')
                    ->label('Prix')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('quantity')
                    ->label('Quantité')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('published_at')
                    ->label('Date de publication')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('type'),
            ])
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
