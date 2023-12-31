<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Actions;
use App\Models\ProductMesure;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ProductResource;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Supprimer ce produit'),
        ];
    }


    protected function mutateFormDataBeforeSave(array $data): array
    {


    // $mesures = ProductMesure::all();

    // $options = [];

    // foreach($mesures as $mesure){

    //     if(array_key_exists($mesure->mesure, $data) && !empty($data[$mesure->mesure])){

    //         $options[$mesure->mesure] = $data[$mesure->mesure];

    //         unset($data[$mesure->mesure]);

    //     }


    // }

    // $data['mesures'] = $options;




    return $data;
}
}

