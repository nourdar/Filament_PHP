<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Delivery;
use App\Models\Settings;
use Illuminate\Http\Request;
use Filament\Notifications\Notification;
use App\Http\Requests\StoreDeliveryRequest;
use App\Http\Requests\UpdateDeliveryRequest;
use Illuminate\Support\HtmlString;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function get_yalididne_delivery_fees($wilaya)
    {
        $yalidine = new YalidineController();

        return $yalidine->getDeliveryFees($wilaya);
    }

    public function calculate_delivery_fees($wilaya)
    {
        $settings =  Settings::first();

        if (isset($settings->transport)) {
            foreach ($settings->transport as $transporteur) {
                if (isset($transporteur['is_principal']) && $transporteur['is_principal']) {
                    $fees = Delivery::where('company_name', $transporteur['provider'])
                        ->where('wilaya_id', intval($wilaya))
                        ->get();
                    if (isset($fees[0])) {

                        $provider = $transporteur['provider'] == 'zrexpress' ? 'ZR-EXPRESS' : $transporteur['provider'];
                        $result = [
                            'home_fee' => intval($fees[0]['home']),
                            'desk_fee' => intval($fees[0]['desk']),
                            'provider' => strtoupper($provider)
                        ];


                        return response()->json($result);
                    }
                }
            }
        }
    }


    public function add_order_to_yalidine($record)
    {

        if (!$this->validation($record)['status']) {
            return Notification::make()
                ->title('لم يتم تسجيل الطلبية')
                ->body($this->validation($record)['message'])
                ->danger()
                ->send();
        }



        $settings =  Settings::first();

        $yalidine = new YalidineController();

        $product = HelperController::get_product_name_from_form_record($record);

        $product = strip_tags($product);


        // set the delivery type

        $is_stopdesk = ($record->shipping_type == 'desk') ? true : false;

        // Set wilaya depart
        if (!$settings->wilaya_depart) {

            return Notification::make()
                ->title('الرجاء الذهاب الى الاعدادات وتحديد ولاية الانطلاق')
                ->danger()
                ->send();
        } else {
            $wilaya_depart = (new AlgeriaCities())->get_wilaya_name($settings->wilaya_depart);
            $wilaya_arrive = (new AlgeriaCities())->get_wilaya_name($record->customer->address);
        }



        $delivery = Delivery::where('company_name', 'yalidine')->where('wilaya_name',  $wilaya_arrive)->get();



        if (empty($record->shipping_price)) {
            $deliveryPrice = $delivery[0][$record->shipping_type];
        } else {
            $deliveryPrice = $record->shipping_price;
        }

        if ($record->is_free_shipping) {
            $deliveryPrice = 0;
        }

        $totalPrice = ($record->items[0]['unit_price'] * $record->items[0]['quantity']) + $deliveryPrice;

        $centerId = $yalidine->get_center_id($wilaya_arrive);

        $centerId = array_change_key_case($centerId->toArray());

        $to_commune_name = $record->customer->city;

        if (isset($centerId[strtolower($record->customer->city)])) {
            $centerId = $centerId[strtolower($record->customer->city)];
        } elseif (isset($centerId[strtolower($wilaya_arrive)])) {
            $centerId = $centerId[strtolower($wilaya_arrive)];
            $to_commune_name = $wilaya_arrive;
        } else {

            $centerId = 0;
        }


        $parcels = [[
            "order_id" => $record->id,
            "from_wilaya_name" =>  $wilaya_depart,
            "firstname" => $record->customer->name,
            "familyname" => '',
            "contact_phone" => $this->phone_cell($record->customer->phone),
            "address" => $record->customer->city,
            "to_commune_name" => $to_commune_name,
            "to_wilaya_name" => $wilaya_arrive,
            "product_list" => $product,
            "price" => $totalPrice,
            "do_insurance" => false,
            "declared_value" => 0,
            // "height" => 10,
            // "width" => 20,
            // "length" => 30,
            // "weight" => 6,
            "freeshipping" => 1,
            "is_stopdesk" => $is_stopdesk,
            "stopdesk_id" => $centerId,
            "has_exchange" => false,
        ]];



        $addParcel = $yalidine->createParcels($parcels);

        if (!isset($addParcel['error'])) {



            $order = Order::find($record->id);
            $order->tracking =  $addParcel[$record->id]['tracking'];
            $order->transport_provider = 'yalidine';
            $order->shipping_price = $deliveryPrice;
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


    public function add_order_to_zrexpress($record)
    {

        if (!$this->validation($record)['status']) {
            return Notification::make()
                ->title('لم يتم تسجيل الطلبية')
                ->body($this->validation($record)['message'])
                ->danger()
                ->send();
        }

        $settings =  Settings::first();

        $zrExpress = new ZrExpressController();

        $product = HelperController::get_product_name_from_form_record($record);

        $product = strip_tags($product);



        // set the delivery type

        $is_stopdesk = ($record->shipping_type == 'desk') ? true : false;

        // Set wilaya depart
        if (!$settings->wilaya_depart) {

            return Notification::make()
                ->title('الرجاء الذهاب الى الاعدادات وتحديد ولاية الانطلاق')
                ->danger()
                ->send();
        } else {
            $wilaya_depart = (new AlgeriaCities())->get_wilaya_name($settings->wilaya_depart);
            $wilaya_arrive = (new AlgeriaCities())->get_wilaya_name($record->customer->address);
        }


        $delivery = Delivery::where('company_name', 'zrexpress')->where('wilaya_name',  $wilaya_arrive)->get();


        if (empty($record->shipping_price)) {
            $deliveryPrice = $delivery[0][$record->shipping_type];
        } else {
            $deliveryPrice = $record->shipping_price;
        }

        if ($record->is_free_shipping) {
            $deliveryPrice = 0;
        }


        $totalPrice = ($record->items[0]['unit_price'] * $record->items[0]['quantity']) + $deliveryPrice;


        // $centerId = $yalidine->get_center_id($wilaya_arrive);

        // $centerId = array_change_key_case($centerId->toArray());

        $to_commune_name = $record->customer->city;

        // if (isset($centerId[strtolower($record->customer->city)])) {
        //     $centerId = $centerId[strtolower($record->customer->city)];
        // } elseif (isset($centerId[strtolower($wilaya_arrive)])) {
        //     $centerId = $centerId[strtolower($wilaya_arrive)];
        //     $to_commune_name = $wilaya_arrive;
        // } else {

        //     $centerId = 0;
        // }


        $tracking = 'ZR-' . rand(22, 999999);
        $colis = [
            "Colis" =>
            [
                [
                    "Tracking" => $tracking,
                    "TypeLivraison" => $is_stopdesk, // Domicile : 0 & Stopdesk : 1
                    "TypeColis" => "0", // Echange : 1
                    "Confrimee" => true, // 1 pour les colis Confirmer directement en pret a expedier
                    "Client" => $record->customer->name,
                    "MobileA" => $this->phone_cell($record->customer->phone),
                    "MobileB" => $this->phone_cell($record->customer->phone2),
                    "Adresse" => $record->customer->city,
                    "IDWilaya" => $wilaya_arrive,
                    "Commune" => $to_commune_name,
                    "Total" => $totalPrice,
                    "Note" => $record->notes,
                    "TProduit" =>  $product,
                    "id_Externe" => $record->id,  // Votre ID ou Tracking
                    "Source" => "test source"
                ]
            ]
        ];




        $addColi = $zrExpress->post('add_colis', $colis);


        if (isset($addColi['COUNT']) && $addColi['COUNT'] > 0) {



            $order = Order::find($record->id);
            $order->tracking = $tracking;
            $order->shipping_price = $deliveryPrice;

            $order->transport_provider = 'zrexpress';
            $order->save();

            $body = ($record->shipping_type == 'desk') ? 'الرجاء التاكد من صحة مكتب  من موقعهم' : '';
            return Notification::make()
                ->title('تمت الاضافة بنجاح')
                ->body($body)
                ->success()
                ->send();
        } else {
            return Notification::make()
                ->title('لم تتم الاضافة')
                ->body('Erreur 1239')
                ->danger()
                ->send();
        }
    }


    public function add_order_to_noest($record)
    {

        if (!$this->validation($record)['status']) {
            return Notification::make()
                ->title('لم يتم تسجيل الطلبية')
                ->body($this->validation($record)['message'])
                ->danger()
                ->send();
        }

        $settings =  Settings::first();

        $noest = new NoestController();

        $product = HelperController::get_product_name_from_form_record($record);

        $product = strip_tags($product);



        // set the delivery type

        $is_stopdesk = ($record->shipping_type == 'desk') ? 1 : 0;

        // Set wilaya depart
        if (!$settings->wilaya_depart) {

            return Notification::make()
                ->title('الرجاء الذهاب الى الاعدادات وتحديد ولاية الانطلاق')
                ->danger()
                ->send();
        } else {
            $wilaya_depart = (new AlgeriaCities())->get_wilaya_name($settings->wilaya_depart);
            $wilaya_arrive = (new AlgeriaCities())->get_wilaya_code($record->customer->address);
        }


        $delivery = Delivery::where('company_name', 'nord_et_west')->where('wilaya_name',  $wilaya_arrive)->get();


        if (empty($record->shipping_price)) {
            $deliveryPrice = $delivery[0][$record->shipping_type];
        } else {
            $deliveryPrice = $record->shipping_price;
        }

        if ($record->is_free_shipping) {
            $deliveryPrice = 0;
        }


        $totalPrice = ($record->items[0]['unit_price'] * $record->items[0]['quantity']) + $deliveryPrice;



        $to_commune_name = (new AlgeriaCities())->get_wilaya_name($record->customer->address);


        $tracking = 'N-' . rand(22, 999999);


        $coli = [
            "tracking" => $tracking,
            "client" =>  $record->customer->name,
            "phone" => $this->phone_cell($record->customer->phone),
            "phone_2" => $this->phone_cell($record->customer->phone2),
            "adresse" => $record->customer->city,
            "wilaya_id" => $wilaya_arrive,
            "commune" =>  strtolower($to_commune_name),
            "montant" => $totalPrice,
            "remarque" => $record->notes,
            "produit" =>  $product,
            "stop_desk" =>  $is_stopdesk, // 1 stop desk, 0 domicile
            "type_id" => 1, // 1 Livraison , 2 echange, 3 pick up
            "poids" => 1,
            "stock" => 0,
            "quantite" => 1,
        ];






        $addColi = $noest->create($coli);




        if (isset($addColi['success']) && isset($addColi['tracking'])) {



            $order = Order::find($record->id);
            $order->tracking = $addColi['tracking'];
            $order->shipping_price = $deliveryPrice;

            $order->transport_provider = 'nord_et_west';
            $order->save();

            $body = ($record->shipping_type == 'desk') ? 'الرجاء التاكد من صحة المكتب  والبلدية من موقعهم' : '';
            return Notification::make()
                ->title('تمت الاضافة بنجاح')
                ->body($body)
                ->success()
                ->send();
        } else {
            return Notification::make()
                ->title('لم تتم الاضافة')
                ->body('Erreur 1212')
                ->danger()
                ->send();
        }
    }

    public function validation($record)
    {
        $result = [
            'status' => false,
            'message' => ''
        ];
        if (!isset($record->items[0])) {
            $result['message'] =  'الرجاء اضافة منتج للطلبية';
            return $result;
        }


        $result['status']  = true;

        return $result;
    }
    public function phone_cell($phone)
    {
        $phone =  str_replace('+213', '0', $phone);

        return $phone;
    }

    public function yalidine_webhook(Request $request)
    {
        $subscribe = $request->subscribe;
        $crc_token = $request->crc_token;

        $data = [
            'subscribe' => $subscribe,
            'crc_token' => $crc_token,
        ];

        return response()->json($data, 200);
    }

    public static function check_active($transport_provider)
    {

        $settings = Settings::first();

        if (isset($settings['transport'])) {
            foreach ($settings['transport'] as $transport) {

                if (isset($transport['provider'])) {
                    if (strtolower($transport['provider']) == strtolower($transport_provider) && $transport['is_active']) {
                        return true;
                    }
                }
            }
        }
        return false;
    }


    public function seeder()
    {

        $this->yalidine_seeder();
        // $this->zrexpress_seeder();
        // $this->noest_seeder();


        dd('done');
    }



    public function yalidine_seeder()
    {

        $yalidine = new YalidineController();

        $yalidine::$api_id =  '24819200986753884357';
        $yalidine::$api_token =  'GfOvjYrK4gMWxs3iyahgbTDNCaEtEBLS5PkB20qAc7bUQ4fdQ2eXVepAUdDcJnX6';

        $yalidineWilayas = $yalidine->get_all_wilayas()['data'];


        $yalidineCommunesRequest = $yalidine->get_all_communes();
        $yalidineCommunes = $yalidineCommunesRequest['data'];

        $i = 2;

        while ($yalidineCommunesRequest['has_more']) {
            $yalidineCommunesRequest = $yalidine->get_all_communes($i);
            array_push($yalidineCommunes, ...$yalidineCommunesRequest['data']);
            $i++;
        }

        $i = 2;



        $yalidineCentresRequest = $yalidine->get_all_centers();
        $yalidineCentres = $yalidineCentresRequest['data'];

        while ($yalidineCentresRequest['has_more']) {
            $yalidineCentresRequest = $yalidine->get_all_centers($i);
            array_push($yalidineCentres, ...$yalidineCentresRequest['data']);
            $i++;
        }

        $i = 2;

        $deliveryFees = $yalidine->getAllDeliveryFees();



        foreach ($yalidineWilayas as $wilaya) {

            Delivery::updateOrCreate([
                'company_name' => 'yalidine',
                'wilaya_id' => $wilaya['id'],
                'wilaya_name' => $wilaya['name'],
                'is_wilaya' => true,
                'zone' => $wilaya['zone'],
                'is_deliverable' => $wilaya['is_deliverable'],

            ]);
        }

        foreach ($deliveryFees['data'] as $delivery) {
            if (is_array($delivery)) {

                Delivery::where('company_name', 'yalidine')->where('is_wilaya', true)->where('wilaya_id', $delivery['wilaya_id'])->update([
                    'home' => $delivery['home_fee'],
                    'desk' => $delivery['desk_fee'],
                    'retour' => '350',
                ]);
            }
        }



        foreach ($yalidineCommunes as $commune) {

            Delivery::updateOrCreate([
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

        foreach ($yalidineCentres as $centre) {
            // dd($centre);
            Delivery::updateOrCreate([
                'company_name' => 'yalidine',
                'wilaya_id' => $centre['wilaya_id'],
                'wilaya_name' => $centre['wilaya_name'],
                'commune_id' => $centre['commune_id'],
                'commune_name' => $centre['commune_name'],
                'center_id' => $centre['center_id'],
                'center_name' => $centre['name'],
                'center_address' => $centre['address'],
                'center_gps' => $centre['gps'],
                'is_center' => true,
            ]);
        }
    }

    public function zrexpress_seeder()
    {

        $zr = new ZrExpressController();
        $zrPricing = $zr->get_tarifs();

        foreach ($zrPricing as $zr) {
            // dd($zr);
            Delivery::updateOrCreate([
                'company_name' => 'zrexpress',
                'wilaya_id' => $zr['IDWilaya'],
                'wilaya_name' => $zr['Wilaya'],
                'home' => $zr['Domicile'],
                'desk' => $zr['Stopdesk'],
                'retour' => $zr['Annuler'],
                'is_wilaya' => true,
            ]);
        }
    }

    public function noest_seeder()
    {

        $zr = new ZrExpressController();
        $zrPricing = $zr->get_tarifs();

        foreach ($zrPricing as $zr) {
            // dd($zr);
            Delivery::updateOrCreate([
                'company_name' => 'nord_et_west',
                'wilaya_id' => $zr['IDWilaya'],
                'wilaya_name' => $zr['Wilaya'],
                'home' => '800',
                'desk' => '350',
                'retour' => '300',
                'is_wilaya' => true,
            ]);
        }
    }
}
