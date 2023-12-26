<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
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

}
