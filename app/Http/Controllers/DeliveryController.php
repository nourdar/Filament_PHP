<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Delivery;
use App\Models\Settings;
use Illuminate\Http\Request;
use Filament\Notifications\Notification;
use App\Http\Requests\StoreDeliveryRequest;
use App\Http\Requests\UpdateDeliveryRequest;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function seeder()
    {
        $yalidine = new YalidineController();
        $zr = new ZrExpressController();

        // $yalidineWilayas = $yalidine->get_all_wilayas()['data'];

        $yalidineCommunes = $yalidine->get_all_communes()['data'];
        $yalidineCentres = $yalidine->get_all_centers()['data'];

        $deliveryFees = $yalidine->getAllDeliveryFees();

        $zrPricing = $zr->get_tarifs();

        // foreach($yalidineWilayas as $wilaya){

        //     Delivery::create([
        //         'company_name' => 'yalidine',
        //         'wilaya_id' => $wilaya['id'],
        //         'wilaya_name' => $wilaya['name'],
        //          'is_wilaya' => true,
        //         'zone' => $wilaya['zone'],
        //         'is_deliverable' => $wilaya['is_deliverable'],

        //     ]);
        // }

        foreach($deliveryFees['data'] as $delivery){
            if(is_array($delivery)){

                Delivery::where('company_name', 'yalidine')->where('is_wilaya', true)->where('wilaya_id', $delivery['wilaya_id'])->update([
                    'home' => $delivery['home_fee'],
                    'desk' => $delivery['desk_fee'],
                    'retour' => '350',
                ]);
            }
        }


        foreach($yalidineCommunes as $commune){

            Delivery::create([
                'company_name' => 'yalidine',
                'wilaya_id' => $commune['wilaya_id'],
                'wilaya_name' => $commune['wilaya_name'],
                'commune_id' => $commune['id'],
                'commune_name' => $commune['name'],
                'has_stop_desk' => $commune['has_stop_desk'],
                'is_deliverable' => $commune['is_deliverable'],
                'is_commune' => true,

            ]);
        }

        foreach($yalidineCentres as $centre){
            // dd($centre);
            Delivery::create([
                'company_name' => 'yalidine',
                'wilaya_id' => $centre['wilaya_id'],
                'wilaya_name' => $centre['wilaya_name'],
                'commune_id' => $centre['commune_id'],
                'commune_name' => $centre['commune_name'],
                'centre_id' => $centre['center_id'],
                'centre_name' => $centre['name'],
                'center_address' => $centre['address'],
                'center_gps' => $centre['gps'],
                'is_center' => true,
            ]);
        }

        foreach($zrPricing as $zr){
            // dd($zr);
            Delivery::create([
                        'company_name' => 'zrexpress',
                        'wilaya_id' => $zr['IDWilaya'],
                        'wilaya_name' => $zr['Wilaya'],
                        'home' => $zr['Domicile'],
                        'desk' => $zr['Stopdesk'],
                        'retour' => $zr['Annuler'],
                        'is_wilaya' => true,
                    ]);
        }

        dd('done');

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


    public function yalidine_webhook(Request $request){
        $subscribe = $request->subscribe;
        $crc_token = $request->crc_token;

        $data = [
            'subscribe' => $subscribe,
            'crc_token' => $crc_token,
        ];

        return response()->json($data, 200);
    }
}
