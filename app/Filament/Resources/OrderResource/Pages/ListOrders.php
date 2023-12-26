<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Closure;
use Filament\Actions;
use Filament\Tables\Table;
use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Enregister une commande'),

        ];
    }

    // protected function makeTable(): Table
    // {
    //     return parent::makeTable()->recordUrl(null);
    // }
}
