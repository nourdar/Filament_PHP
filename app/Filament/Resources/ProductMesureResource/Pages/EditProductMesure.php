<?php

namespace App\Filament\Resources\ProductMesureResource\Pages;

use App\Filament\Resources\ProductMesureResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductMesure extends EditRecord
{
    protected static string $resource = ProductMesureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
