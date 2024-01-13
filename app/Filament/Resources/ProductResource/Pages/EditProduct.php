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
            Actions\Action::make('view')
                ->label('Voir dans la boutique')
                ->url(fn (): string => route('product.show', $this->record->id))
                ->openUrlInNewTab()
        ];
    }


    protected function mutateFormDataBeforeSave(array $data): array
    {



        return $data;
    }
}
