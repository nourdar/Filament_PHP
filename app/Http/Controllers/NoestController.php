<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NoestController extends Controller
{
    private $apiToken = 'bf96587ee875bb17ae216d8ecff414f75425a2bf1914ebecb2f09759fffbd8d6';
    private $apiKey = '2cbf4623f475456f81c8f0bc26c4438a';
    private $baseUrl = 'https://app.noest-dz.com/api/public/';

    public function __construct()
    {

        $settings = Settings::first();

        if ($settings?->transport) {

            foreach ($settings?->transport as $transport) {

                if (strtolower($transport['provider']) == 'nord_et_west') {
                    $this->apiToken = $transport['api_key'];
                    $this->apiKey = $transport['api_token'];
                }
            }
        }
    }


    public function create($coli)
    {


        $response = Http::post($this->baseUrl . 'create/order', [
            'api_token' => $this->apiToken,
            'user_guid' => $this->apiKey,
            ...$coli
        ]);

        // $response = Http::post($this->baseUrl, [
        //     'api_token' => $this->apiToken,
        //     'user_guid' => $this->apiKey,
        // ]);

        return $response->json();
    }
}
