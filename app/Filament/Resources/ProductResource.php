<?php

namespace App\Filament\Resources;

use App\Enums\ProductType;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Str;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
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

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $navigationLabel = 'Produis';
    protected static ?string $navigationGroup = 'Ma Boutique';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                TextInput::make('name')
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
                                        ->required()
                                        ->disabled()
                                        ->dehydrated()
                                        ->unique(Product::class, 'slug', ignoreRecord:true),
                                MarkdownEditor::make('description')
                                ->columnSpan('full')
                                ])->columns(2),

                                Section::make()
                                    ->schema([
                                        TextInput::make('sku')
                                            ->label('SKU (Stock Keeping Unit)')
                                            ->unique()
                                            ->required(),

                                        TextInput::make('price')
                                            ->minValue(1)
                                            ->numeric()
                                            ->rules('regex:/^\d{1,6}(\.\d{0,2})?$/')
                                            ->required(),
                                        TextInput::make('quantity')
                                            ->numeric()
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->required(),

                                        Select::make('type')->options([
                                            'deliverable' => ProductType::DELIVERABLE->value,
                                            'downloadable' => ProductType::DOWNLOADABLE->value,
                                        ])
                                    ])->columns(2),
                            ]),



                            Group::make()
                                ->schema([
                                    Section::make('Status')
                                        ->schema([
                                            Toggle::make('is_visible')
                                                ->helperText("If disable, the product will not be visible on the frontend.")
                                                ->label('Visibility')
                                                ->default(true)
                                                ->required(),
                                            Toggle::make('is_featured')
                                                ->label('Featured')
                                                ->helperText('Featured products will be shown on the homepage and in featured section.'),
                                            DatePicker::make('published_at')
                                                ->label('Date of Publishing')
                                                ->default(now()),
                                        ]),

                                    Section::make('Image')
                                        ->schema([
                                            FileUpload::make('image')
                                                ->directory('form-attachments')
                                                ->preserveFilenames()
                                                ->image()
                                                ->imageEditor(),

                                        ])->collapsible(),

                                    Section::make('Associations')
                                        ->schema([
                                           Select::make('brand_id')
                                                ->relationship('brand', 'name')
                                        ])
                                ]),
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
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                IconColumn::make('is_visible')
                    ->boolean()
                    ->toggleable(),
                TextColumn::make('price')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('quantity')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('published_at')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('type'),
            ])
            ->filters([
                TernaryFilter::make('is_visible')
                    ->label("Visibility")
                    ->boolean()
                    ->trueLabel("Only Visible Products")
                    ->falseLabel("Only Hidden Products")
                    ->native(false),
                SelectFilter::make('brand')
                    ->relationship('brand', 'name'),

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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
