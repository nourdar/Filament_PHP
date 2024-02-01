<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Delivery;
use App\Models\Settings;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use App\Http\Controllers\AlgeriaCities;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\HelperController;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Combindma\FacebookPixel\Facades\FacebookPixel;



class ProductForm extends Component
{
    use LivewireAlert;

    public $product;
    public $productId;
    public int  $quantity = 1;
    public int $totalPrice;
    public $item;
    public int $deliveryPrice = 0;
    public $deliveryType = 'desk';
    public $deliveryCompany;
    public $desk = true;
    public $home;
    public $wilaya = 16;
    public $wilayasList;
    public $commune;
    public $communesList;
    public $notes;


    #[Validate('required')]
    public $phone;
    public int $price;

    #[Validate('required')]
    public $name;

    public $errorMessage = false;
    public $sucessMessage = false;
    public $mesuresList = [];
    public $mesures = [];


    protected $messages = [
        'phone.required' => 'الرجاء ادخال رقم الهاتف',
        'name.required' => 'الرجاء ادخال الاسم',
    ];



    public function render()
    {
        $settings = Settings::first();

        if (isset(collect($settings->transport)->where('is_principal')[0])) {
            if (isset(collect($settings->transport)->where('is_principal')[0]['provider'])) {
                $this->deliveryCompany = collect($settings->transport)->where('is_principal')[0]['provider'];
            }
        }
        if (empty($this->product)) {
            $this->product = Product::find($this->productId);
        }

        if (empty($this->mesures)) {

            $this->mesuresList = HelperController::product_mesures($this->product);

            if (!empty($this->mesuresList)) {
                foreach ($this->mesuresList as $mesure => $options) {
                    // dd($mesure);
                    $this->mesures[$mesure] = '';
                }
            }
        }

        if (empty($this->wilayasList)) {

            $this->wilayasList =  Delivery::all()->pluck('wilaya_name', 'wilaya_id');
        }


        if (empty($this->communesList)) {

            $communes = Delivery::where('wilaya_id', $this->wilaya)->pluck('commune_name', 'commune_name');

            $this->communesList = $communes;
        }


        $fees = Delivery::where('wilaya_id', $this->wilaya)->where('is_wilaya', true)->where('company_name', $this->deliveryCompany)->select('home', 'desk')->get();
        if (isset($fees[0])) {
            $this->deliveryPrice  = ($this->home) ? $fees[0]['home'] : $fees[0]['desk'];
        }

        $this->updateTotalPrice();
        return view('shop.product.form');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }


    public function save()
    {



        $this->validate();

        $save = (new ShopController())->livewire_place_order($this);

        if ($save) {
            // $this->reset();

            $this->dispatch('openModal', component: 'order-placed-modal', arguments: [
                'success' => true
            ]);

            $this->sucessMessage = true;
            $this->errorMessage = false;
            $this->alert('success', 'تم تسجيل الطلب بنجاح');
        } else {

            $this->dispatch('openModal', component: 'order-placed-modal', arguments: [
                'success' => false
            ]);

            $this->errorMessage = true;
            $this->sucessMessage = false;
            $this->alert('alert', 'تم تسجيل الطلب بنجاح');
        }

        return true;
    }

    public function updateTotalPrice()
    {
        $mesures = $this->mesures;
        $price = ($this->product?->price * $this->quantity);

        // this code is for the wilayas that doesn't have stop desk, so I will define the price as home and the desk checkbox will be disabled
        if ($this->deliveryPrice == 0 && $this->deliveryType == 'desk') {
            $this->deliveryType = 'home';
            $this->desk = false;
            $this->home = true;
        }

        $total = $price + $this->deliveryPrice;

        $this->price =  $price;

        $this->totalPrice = $total;
        $this->mesures = $mesures;
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
        $this->communesList = $communes->pluck('commune_name', 'commune_name')->filter();

        $this->commune =  $this->communesList->first();
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
        $this->deliveryType = 'desk';
        $this->updateTotalPrice();
    }

    public function homeChecked()
    {

        $this->desk = false;
        $this->home = true;
        $this->deliveryType = 'home';
        $this->updateTotalPrice();
    }
}
