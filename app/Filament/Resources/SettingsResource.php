<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Settings;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\SettingsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SettingsResource\RelationManagers;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class SettingsResource extends Resource
{
    protected static ?string $model = Settings::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')->schema([
                    TextInput::make('name')->label('Nom de la boutique'),
                    FileUpload::make('logo')
                    ->directory('form-attachments')
                    ->image()
                    ->imageEditor(),

                ])->columns(2),

                Section::make('')->schema([
                    MarkdownEditor::make('description')->label('Description'),
                    Textarea::make('keywords')->label('Keywords'),

                ])->columns(2),

                Section::make('')->schema([
                    Textarea::make('address')->label('Address'),

                    TextInput::make('email')
                    ->email()
                    ->label('E-mail'),

                    Repeater::make('telephone')->schema([
                        TextInput::make('phone')->label('Numero de telephone'),
                    ])->addActionLabel('Ajouter un numero de telephone'),


                ])->columns(2),


                Section::make('Réseaux Sociaux')->schema([


                    TextInput::make('facebook_page')->label('Page Facebook'),
                    TextInput::make('instagram_page')->label('Page Instagram'),
                    TextInput::make('pinterest_page')->label('Page Pinterest'),
                    TextInput::make('twitter_page')->label('Page Twitter'),



                ]),

                Section::make('')->schema([

                    Repeater::make('transport')->schema([
                        Select::make('provider')->label('Transporteur') ->options([
                          'Yalidine' => 'Yalidine' ,

                        ]),
                        TextInput::make('api_key')->label('Transport API Key'),
                        TextInput::make('api_token')->label('Transport API Token'),


                    ])
                    ->addable(false)
                    ->defaultItems(1),



                    Textarea::make('head_code')->label('Head Code (Facebook, Goolgle ...etc)'),



                ]),
                Section::make('')->schema([
                    Repeater::make('slides')->schema([
                        FileUpload::make('slide')
                        ->directory('form-attachments')
                        ->image()
                        ->imageEditor(),
                    ])->addActionLabel('Ajouter un Slide'),



                ]),








            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo'),
                TextColumn::make('name')
            ])
            ->filters([
                //
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSettings::route('/create'),
            'edit' => Pages\EditSettings::route('/{record}/edit'),
        ];

    }

    public static function canCreate(): bool
    {
        if(Settings::all()->count() < 1) {
            return true;
        }

       return false;
    }
}
