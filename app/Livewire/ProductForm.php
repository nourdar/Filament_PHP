<?php

namespace App\Livewire;

use App\Http\Controllers\AlgeriaCities;
use App\Models\Delivery;
use App\Models\Product;
use App\Models\Settings;
use Livewire\Component;
use Livewire\Attributes\On;

class ProductForm extends Component
{


    public $product;
    public $productId;
    public $quantity = 1;
    public $totalPrice;
    public $item;
    public $deliveryPrice = 0;
    public $deliveryType;
    public $deliveryCompany;
    public $desk = true;
    public $home;
    public $wilaya = 16;
    public $wilayasList;
    public $commune;
    public $communesList;
    public $phone;
    public $price;
    public $name = 'test';


    public function render()
    {
        $settings = Settings::first();

        if (isset(collect($settings->transport)->where('is_principal')[0])) {
            if (isset(collect($settings->transport)->where('is_principal')[0]['provider'])) {
                $this->deliveryCompany = collect($settings->transport)->where('is_principal')[0]['provider'];
            }
        }

        $this->product = Product::find($this->productId);
        $this->wilayasList =  Delivery::all()->pluck('wilaya_name', 'wilaya_id');


        // $communes = Delivery::where('wilaya_id', $this->wilaya)->get();
        $communes = (new AlgeriaCities())->get_all_communs($this->wilaya);

        $this->communesList = $communes;


        $fees = Delivery::where('wilaya_id', $this->wilaya)->where('is_wilaya', true)->where('company_name', $this->deliveryCompany)->select('home', 'desk')->get();
        if (isset($fees[0])) {

            $this->deliveryPrice  = ($this->home) ? $fees[0]['home'] : $fees[0]['desk'];
        }

        $this->updateTotalPrice();
        return view('shop.product.form');
    }

    public function save()
    {
        return true;
    }

    public function updateTotalPrice()
    {

        $price = ($this->product->price * $this->quantity);
        $total = $price + $this->deliveryPrice;

        $this->price = number_format($price, '0', ',', ' ');
        $this->totalPrice = number_format($total, '0', ',', ' ');
    }


    public function incrementQuantity()
    {
        $this->quantity++;
        $this->updateTotalPrice();
    }


    public function decrementQuantity()
    {
        if ($this->quantity <= 1) {
            return true;
        }

        return $this->quantity--;
    }

    public function wilayaChanged()
    {
        $communes = Delivery::where('wilaya_id', $this->wilaya)->get();
        $this->communesList = $communes->pluck('commune_name', 'commune_name');

        $fees = Delivery::where('wilaya_id', $this->wilaya)->where('is_wilaya', true)->where('company_name', $this->deliveryCompany)->select('home', 'desk')->get();

        if (isset($fees[0])) {

            $this->deliveryPrice  = ($this->home) ? $fees[0]['home'] : $fees[0]['desk'];
        }


        $this->updateTotalPrice();
    }

    public function deskChecked()
    {
        $this->desk = true;
        $this->home = false;
        $this->updateTotalPrice();
    }

    public function homeChecked()
    {
        $this->desk = false;
        $this->home = true;
    }
}
