<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\CategoryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CategoryResource\RelationManagers;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Catégories';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationGroup = 'Ma Boutique';


    protected static ?string $activeNavigationIcon = "heroicon-o-check-badge";

    // protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 5
                    ? 'warning'
                    : 'primary';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make([
                            Select::make('parent_id')
                                ->label('Nom - Catégorie Parent')
                                ->placeholder('Selectionner la catégorie parent')
                                ->relationship('parent', 'name'),
                            TextInput::make('name')
                                ->label('Nom - Catégorie')
                                ->placeholder('Nom de la catégorie')
                                ->required()
                                ->live(onBlur:true)

                                ->afterStateUpdated(function(string $operation, $state, Forms\Set $set) {
                                    // dd($operation);
                                    if($operation !== 'create'){
                                        return;
                                    }

                                    $set('slug', Str::slug($state));
                                }),
                            TextInput::make('slug')
                                    ->required()
                                    ->label('Slug')
                                    ->disabled()
                                    ->dehydrated()
                                    ->unique(Category::class, 'slug', ignoreRecord:true),

                            Toggle::make('is_visible')
                                ->label("Visibilité")
                                ->default(true)
                                ->helperText("La visiblité de la catégorie détermine si elle est visible sur le site ou non.")
                        ])

                    ]),

                Group::make()
                    ->schema([

                        Section::make([

                            FileUpload::make('image')
                            ->directory('form-attachments')
                            ->image()
                            ->imageEditor(),

                            MarkdownEditor::make('description')
                            ->label('Description')
                            ->columnSpanFull()
                        ]),
                    ]),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                ->toggleable(),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Nom - Catégorie Parent')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom de Catégorie')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_visible')
                    ->label('Visibilité')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date de Création')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
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
            RelationManagers\ProductsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
