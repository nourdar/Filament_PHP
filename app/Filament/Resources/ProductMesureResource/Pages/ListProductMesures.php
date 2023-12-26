<?php

namespace App\Filament\Resources\ProductMesureResource\Pages;

use App\Filament\Resources\ProductMesureResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductMesures extends ListRecords
{
    protected static string $resource = ProductMesureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
