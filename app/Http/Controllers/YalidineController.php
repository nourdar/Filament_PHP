<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class YalidineController extends Controller

{

    private static $url = "https://api.yalidine.com/v1/";
    public static $api_id = null;
    public static $api_token = null;


    public function __construct()
    {
        $settings = Settings::first();
        if ($settings?->transport) {
            foreach ($settings?->transport as $transport) {
                if (strtolower($transport['provider']) == 'yalidine') {
                    self::$api_id = $transport['api_key'];
                    self::$api_token = $transport['api_token'];
                }
            }
        }
    }
    public static function initGuzzleHttpClient()
    {

        if (is_null(self::$api_id)) {
            self::$api_id = env('YALIDINE_API_ID', null);
        }
        if (is_null(self::$api_token)) {
            self::$api_token = env('YALIDINE_API_TOKEN', null);
        }
    }

    /**
     * Get the parcels from Yalidine API
     * @param array of tracking parcels
     */
    public static function getParcels(array $trackings)
    {

        try {
            self::initGuzzleHttpClient();

            $curl = curl_init();
            $tracking  = '';
            for ($i = 0; $i < count($trackings); $i++) {
                if ($i == 0) $tracking  = '?tracking=' . $trackings[$i];
                else $tracking  .= ',' . $trackings[$i];
            }
            $url = self::$url  . 'parcels/' . $tracking;
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'X-API-ID: ' . self::$api_id,
                    'X-API-TOKEN: ' . self::$api_token
                ),
            ));
            $response_json = curl_exec($curl);
            curl_close($curl);
            $response_array = json_decode($response_json, true);
            return $response_array;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Create a parcel in Yalidine API
     * @param array of Parcel $parcels
     */
    public static function createParcels($parcels)
    {
        try {
            self::initGuzzleHttpClient();
            $ch = curl_init(self::$url . 'parcels/');
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parcels));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    "X-API-ID: " . self::$api_id,
                    "X-API-TOKEN: " . self::$api_token,
                    "Content-Type: application/json"
                )
            );

            $result = curl_exec($ch);
            curl_close($ch);
            header("Content-Type: application/json");
            return json_decode($result, true);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    /**
     * Delete a parcel from Yalidine API
     * @param array of tracking parcels
     */
    public static function deleteParcels(array $trackings)
    {
        try {
            self::initGuzzleHttpClient();

            $curl = curl_init();
            $tracking  = '';
            for ($i = 0; $i < count($trackings); $i++) {
                if ($i == 0) $tracking  = '?tracking=' . $trackings[$i];
                else $tracking  .= ',' . $trackings[$i];
            }
            $url = self::$url  . 'parcels/' . $tracking;
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'DELETE',
                CURLOPT_HTTPHEADER => array(
                    'X-API-ID: ' . self::$api_id,
                    'X-API-TOKEN: ' . self::$api_token
                ),
            ));
            $response_json = curl_exec($curl);
            curl_close($curl);
            $response_array = json_decode($response_json, true);
            return $response_array;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    /**
     * Get the delivery fees from Yalidine API
     * @param array of Parcel $parcels
     */
    public static function getDeliveryFees($wilaya)
    {

        try {
            self::initGuzzleHttpClient();
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => self::$url . 'deliveryfees/?wilaya_id=' . $wilaya,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'X-API-ID: ' . self::$api_id,
                    'X-API-TOKEN: ' . self::$api_token
                ),
            ));
            $response_json = curl_exec($curl);
            curl_close($curl);
            $response_array = json_decode($response_json, true);
            return $response_array;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function getAllDeliveryFees()
    {

        try {
            self::initGuzzleHttpClient();
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => self::$url . 'deliveryfees',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'X-API-ID: ' . self::$api_id,
                    'X-API-TOKEN: ' . self::$api_token
                ),
            ));
            $response_json = curl_exec($curl);
            curl_close($curl);
            $response_array = json_decode($response_json, true);

            return $response_array;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function get_centers_by_wilaya($wilaya_code)
    {

        $wilaya_code = (new AlgeriaCities())->get_wilaya_code($wilaya_code);
        try {
            self::initGuzzleHttpClient();

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => self::$url . 'centers/?wilaya_id=' . $wilaya_code,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'X-API-ID: ' . self::$api_id,
                    'X-API-TOKEN: ' . self::$api_token
                ),
            ));

            $response_json = curl_exec($curl);
            curl_close($curl);


            $response_array = json_decode($response_json, true); // converting the json to a php array

            return $response_array['data'] ?? false;
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    // public function get_centers_by_wilaya($wilayaCode){

    //     $wilayaCode = (new AlgeriaCities())->get_wilaya_code($wilayaCode);

    //     $centers = $this->get_centers_by_wilaya($wilayaCode);

    //      return $centers['data'];

    // }


    public function get_center_id($wilayaCode)
    {
        $centers =  $this->get_centers_by_wilaya($wilayaCode);

        $centers = collect($centers)->pluck('center_id', 'commune_name');

        return $centers;
    }


    public static function get_all_wilayas()
    {

        try {
            self::initGuzzleHttpClient();

            $curl = curl_init();

            $url = self::$url  . 'wilayas';
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'X-API-ID: ' . self::$api_id,
                    'X-API-TOKEN: ' . self::$api_token
                ),
            ));
            $response_json = curl_exec($curl);
            curl_close($curl);
            $response_array = json_decode($response_json, true);
            return $response_array;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function get_all_communes($page = 1)
    {

        try {
            self::initGuzzleHttpClient();

            $curl = curl_init();

            $url = self::$url  . 'communes?page=' . $page;
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'X-API-ID: ' . self::$api_id,
                    'X-API-TOKEN: ' . self::$api_token
                ),
            ));
            $response_json = curl_exec($curl);
            curl_close($curl);
            $response_array = json_decode($response_json, true);
            return $response_array;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function get_all_centers($page = 1)
    {

        try {
            self::initGuzzleHttpClient();

            $curl = curl_init();

            $url = self::$url  . 'centers?page=' . $page;
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'X-API-ID: ' . self::$api_id,
                    'X-API-TOKEN: ' . self::$api_token
                ),
            ));
            $response_json = curl_exec($curl);
            curl_close($curl);
            $response_array = json_decode($response_json, true);
            return $response_array;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
