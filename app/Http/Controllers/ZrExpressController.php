<?php

namespace App\Http\Controllers;

use Psr7\Request;
use GuzzleHttp\Client;
use App\Models\Settings;
use GuzzleHttp\Psr7\Request as Hrequest;

class ZrExpressController extends Controller
{
    private $apiToken = 'bf96587ee875bb17ae216d8ecff414f75425a2bf1914ebecb2f09759fffbd8d6';
    private $apiKey = '2cbf4623f475456f81c8f0bc26c4438a';
    private $baseUrl = 'https://procolis.com/api_v1/';

    public function __construct()
    {

        $settings = Settings::first();

        if($settings?->transport) {

            foreach($settings?->transport as $transport) {

                if(strtolower($transport['provider']) == 'zrexpress') {
                    $this->apiToken = $transport['api_key'];
                    $this->apiKey = $transport['api_token'];
                }
            }

        }
    }




    public function index(){


        $colis = [
            "Colis"=>
            [
            [ "Tracking" =>rand(22,999999),
                "TypeLivraison" => "0", // Domicile : 0 & Stopdesk : 1
                "TypeColis" => "0", // Echange : 1
                "Confrimee" => "", // 1 pour les colis Confirmer directement en pret a expedier
                "Client" => "Mohamed",
                "MobileA" => "0990909090",
                "MobileB" => "0880808080",
                "Adresse" => "Rue 39",
                "IDWilaya" => "31",
                "Commune" => "Maraval",
                "Total" => "1000",
                "Note" => "",
                "TProduit" =>  "Article1",
                "id_Externe" => rand(22,999999) ,  // Votre ID ou Tracking
                "Source" => ""]
        ]];






        try {

            $ch = curl_init($this->baseUrl.'add_colis');
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($colis));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    'token:'. $this->apiToken,
                    'key:'. $this->apiKey,
                    "Content-Type: application/json",
                    'Accept' => 'application/json, application/json',
                )
            );

            $result = curl_exec($ch);

            curl_close($ch);

            $result = json_decode($result, true);

            dd(json_decode($result, true));

            return json_decode($result, true);

        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function get_tarifs(){
       return  $this->post('tarification');
    }

    public function post($uri, $data = null)
    {
        try {
            $ch = curl_init($this->baseUrl.$uri);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    'token:'. $this->apiToken,
                    'key:'. $this->apiKey,
                    "Content-Type: application/json",
                    'Accept' => 'application/json, application/json',
                )
            );

            $result = curl_exec($ch);

            curl_close($ch);


            return json_decode($result, true);

        } catch (\Throwable $th) {
            throw $th;
        }
    }




}
