<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Delivery;
use App\Models\Settings;
use Filament\Notifications\Notification;
use App\Http\Requests\StoreDeliveryRequest;
use App\Http\Requests\UpdateDeliveryRequest;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function get_yalididne_delivery_fees($wilaya)
    {
        $yalidine = new YalidineController();

       return $yalidine->getDeliveryFees($wilaya);

    }


public function add_order_to_yalidine($record){

    $settings =  Settings::first();

    $yalidine = new YalidineController();

    $product = $record?->items[0]?->product?->name;

    if($record?->items[0]?->options){
        foreach($record?->items[0]?->options as $key => $value){

            if(is_string($value) && is_string($key)){

                $product .=' '.$key.' : '.$value;
            }
        }

    }



    // set the delivery type

    $is_stopdesk = ($record->shipping_type == 'desk') ? 1 : 0;

    // Set wilaya depart
    if(!$settings->wilaya_depart){

       return Notification::make()
        ->title('الرجاء الذهاب الى الاعدادات وتحديد ولاية الانطلاق')
        ->danger()
        ->send();


    } else {
        $wilaya_depart = (new AlgeriaCities())->get_wilaya_name($settings->wilaya_depart);
        $wilaya_arrive = (new AlgeriaCities())->get_wilaya_name($record->customer->address);
    }

    $centerId = $yalidine->get_center_id($wilaya_arrive);

    $centerId = array_change_key_case($centerId->toArray());

    $to_commune_name = $record->customer->city;

    if(isset($centerId[strtolower($record->customer->city)])) {
        $centerId = $centerId[strtolower($record->customer->city)];
    } elseif(isset($centerId[strtolower($wilaya_arrive)])) {
        $centerId = $centerId[strtolower($wilaya_arrive)];
        $to_commune_name = $wilaya_arrive;

    } else {

        $centerId = 0;
    }


    $parcels =[ [
        "order_id" => $record->id,
        "from_wilaya_name"=>  $wilaya_depart,
        "firstname"=> $record->customer->name,
        "familyname"=>'',
        "contact_phone"=> $record->customer->phone,
        "address"=>$record->customer->city,
        "to_commune_name"=> $to_commune_name,
        "to_wilaya_name"=> $wilaya_arrive,
        "product_list"=> $product,
        "price"=> $record->items[0]['unit_price'],
        "do_insurance" => false,
        "declared_value" => 0,
        "height" => 10,
        "width" => 20,
        "length" => 30,
        "weight" => 6,
        "freeshipping" => 0,
        "is_stopdesk" => $is_stopdesk,
        "stopdesk_id" => $centerId,
        "has_exchange"=> false,
    ]];



    $addParcel = $yalidine->createParcels($parcels);

    if(!isset($addParcel['error'])){



        $order = Order::find($record->id);
        $order->tracking =  $addParcel[$record->id]['tracking'];
        $order->save();

        $body = ($record->shipping_type == 'desk') ? 'الرجاء التاكد من صحة مكتب ياليدين من موقعهم' : '';
        return Notification::make()
        ->title('تمت الاضافة بنجاح')
        ->body($body)
        ->success()
        ->send();

   } else {
    return Notification::make()
    ->title('لم تتم الاضافة')
    ->body($addParcel['error']['message'])
    ->danger()
    ->send();
   }
}

}
