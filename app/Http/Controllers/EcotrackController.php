<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class EcotrackController extends Controller
{
    private $apiToken = 'bf96587ee875bb17ae216d8ecff414f75425a2bf1914ebecb2f09759fffbd8d6';
    private $apiKey = '2cbf4623f475456f81c8f0bc26c4438a';
    private $baseUrl = 'https://procolis.com/api_v1/';

    public function __construct()
    {

        $settings = Settings::first();

        if ($settings?->transport) {

            foreach ($settings?->transport as $transport) {

                if (strtolower($transport['provider']) == 'ecotrack') {
                    $this->apiToken = $transport['api_key'];
                    $this->apiKey = $transport['api_token'];
                }
            }
        }
    }
}
