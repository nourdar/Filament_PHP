<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Models\Stock;
use Filament\Actions;
use App\Models\Product;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\OrderResource;
use App\Http\Controllers\DeliveryController;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Supprimer cette commande'),
            Actions\Action::make('activities')
                ->icon('heroicon-o-document')
                ->label('Historique')
                ->url(fn ($record) => OrderResource::getUrl('activities', ['record' => $this->record])),

            Actions\Action::make('add_to_yalidine')
                ->label('Ajouter a Yalidine')
                ->color('danger')
                ->visible(DeliveryController::check_active('yalidine'))
                ->icon('heroicon-o-truck')
                ->action(function ($record) {
                    $delivery = new DeliveryController();
                    $delivery->add_order_to_yalidine($record);
                }),

            Actions\Action::make('add_to_zrexpress')
                ->label('Ajouter a ZR-Express')
                ->color('warning')
                ->visible(DeliveryController::check_active('zrexpress'))
                ->icon('heroicon-o-truck')
                ->action(function ($record) {
                    $delivery = new DeliveryController();

                    $delivery->add_order_to_zrexpress($record);
                }),


        ];
    }



    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($this->record->status == $data['status']) {
            return $data;
        }


        if ($this->record?->items) {

            foreach ($this->record?->items as $item) {

                $productName = $item->product->name;

                if ($item->options) {
                    foreach ($item->options as $key => $value) {

                        if (is_string($value) && is_string($key)) {

                            $productName .= ' ' . $key . ' : ' . $value;
                        }
                    }
                    // dd( $product = $record?->items[0]?->product);
                }


                $stockRecord = [
                    'product_id' => $item->product->id,
                    'product_name'  => $productName,
                    'order_id'  => $this->record->id,
                    'order_item_id'  => $item->id,
                    'type'  => 'real',
                    'move_type'  => 'in',
                    'qte'  =>  $item->product->quantity,
                ];

                $stockCount = Stock::where('product_id', $item->product->id)->count();

                if ($stockCount == 0) {

                    Stock::create($stockRecord);

                    // Notification::make('')
                    //     ->title('Repture de stock')
                    //     ->description('Y\' a 0 stock de : ' . $item->product->name);
                    // return $data;
                }

                if ($data['status'] == 'confirmed' or $data['status'] == 'processing') {

                    $stockRecord['type'] = 'shipped';
                    $stockRecord['mov_TYPE'] = 'in';
                    Stock::create($stockRecord);

                    $stockRecord['type'] = 'real';
                    $stockRecord['mov_TYPE'] = 'out';
                    Stock::create($stockRecord);

                    Product::where('id', $item->product->id)->update([
                        'quantity' => $item->product->quantity - $item->quantity,
                    ]);
                }



                if ($data['status'] == 'back' or $data['status'] == 'declined') {

                    $stockRecord['type'] = 'shipped';
                    $stockRecord['mov_TYPE'] = 'out';
                    Stock::create($stockRecord);


                    $stockRecord['type'] = 'real';
                    $stockRecord['mov_TYPE'] = 'in';
                    Stock::create($stockRecord);



                    Product::where('id', $item->product->id)->update([
                        'quantity' => $item->product->quantity + $item->quantity,
                    ]);
                }



                if ($data['status'] == 'shipped' or $data['status'] == 'paid') {
                    $stockRecord['type'] = 'shipped';
                    $stockRecord['mov_type'] = 'out';
                    Stock::create($stockRecord);
                }
            }
        }

        return $data;
    }
}
