<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Filament\Resources\BrandResource\RelationManagers;
use App\Models\Brand;
use Filament\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ColorPicker;
use Illuminate\Support\Str;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Marques';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = "Ma Boutique";

    protected static ?string $activeNavigationIcon = "heroicon-o-check-badge";

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
                            TextInput::make('name')->label("Nom de la marque")
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
                                    ->label('Slug de la marque')
                                    ->disabled()
                                    ->dehydrated()
                                    ->unique(Brand::class, 'slug', ignoreRecord:true),

                            TextInput::make('url')
                                    ->label("URL du site web")
                                    ->required()
                                    ->unique()
                                    ->columnSpan('full'),
                            MarkdownEditor::make('description')
                                    ->label('Description')
                                    ->columnSpan('full')


                        ])->columns(2)
                    ]),
                Group::make()
                    ->schema([
                        Section::make("Status")
                            ->schema([
                                Toggle::make('is_visible')
                                    ->label("Visible sur le site")
                                    ->helperText("Si cette option est désactivée, la marque ne sera pas visible pour les utilisateurs.")
                                    ->default(true),
                            ]),

                            Group::make()
                                ->schema([
                                    Section::make("Couleur")
                                        ->schema([
                                            ColorPicker::make('primary_color')
                                                ->label("Couleur de la marque")
                                        ])
                                ])
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('url')
                    ->searchable()
                    ->label('URL du site web')
                    ->sortable(),

                ColorColumn::make('primary_color')
                    ->label('Couleur')
                    ->sortable(),

                IconColumn::make('is_visible')
                    ->label('Visibilité')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Date de mise à jour')
                    ->date()
                    ->sortable(),

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
            RelationManagers\ProductsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}
