<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Settings;
use App\Mail\OrderPlaced;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\ProductMesure;
use Spatie\Searchable\Search;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Spatie\ImageOptimizer\OptimizerChain;
use Symfony\Component\Console\Input\Input;

use Combindma\FacebookPixel\Facades\FacebookPixel;
use FacebookAds\Object\ServerSide\Content;
use FacebookAds\Object\ServerSide\CustomData;
use FacebookAds\Object\ServerSide\DeliveryCategory;
use FacebookAds\Object\ServerSide\UserData;



class ShopController extends Controller
{

    public $settings;
    public $cities;

    public function __construct()
    {

        $this->settings = Settings::first();
        $this->cities = new AlgeriaCities();
    }



    public function index()
    {
        $title = ($this->settings?->name) ?? 'SARL NAMEQUE - contact@nameque.net';

        $settings = $this->settings;

        $products = Product::where('is_visible', true)->orderBy('updated_at', 'desc')->paginate(12, ['*'], 'products');
        $brands = Brand::where('is_visible', true)->has('products')->paginate(5, ['*'], 'brands');

        $categories = Category::where('is_visible', true)->has('products')->limit(4)->get();

        // $categories = Category::where('is_visible', true)->has('products')->paginate(3, ['*'], 'categories');

        return view('shop.shop')->with(compact(['title', 'settings', 'products', 'brands', 'categories']));
    }

    public function optimize_images()
    {
        $files = Storage::disk('public')->files('form-attachments');

        foreach ($files as $file) {
            // dd(Storage::disk('public')->exists($file));
            app(OptimizerChain::class)->optimize('storage/' . $file);
        }

        dd('Images are optimized');
    }


    public function show_brand(Request $request, $id)
    {

        $brand = Brand::where('is_visible', true)->with('products')->findOrFail($id);

        $title = ($this->settings?->name . ' - ' . $brand?->name) ?? 'test';

        $settings = $this->settings;

        $products = $brand->products()->paginate(10, ['*'], 'products');

        $brands = Brand::where('is_visible', true)->paginate(10, ['*'], 'brands');
        $categories = Category::where('is_visible', true)->paginate(10, ['*'], 'categories');

        return view('shop.brand.show')->with(compact(['title', 'settings', 'products', 'brand', 'categories']));
    }

    public function show_category(Request $request, $id)
    {

        $category = Category::where('is_visible', true)->with('products')->findOrFail($id);

        $title = ($this->settings?->name . ' - ' . $category?->name) ?? 'test';

        $settings = $this->settings;

        $products = $category->products()->paginate(10, ['*'], 'products');


        $categories = Category::where('is_visible', true)->paginate(10, ['*'], 'categories');

        return view('shop.category.show')->with(compact(['title', 'settings', 'products',  'category']));
    }

    public function show_product(Request $request, $id)
    {

        $product = Product::where('is_visible', true)->with(['brand', 'categories'])->findOrFail($id);

        $title = ($this->settings?->name . ' - ' . $product?->name) ?? 'test';

        $wilayas = $this->cities->get_all_wilayas();

        $settings = $this->settings;

        return view('shop.product.show')->with(compact(['title', 'settings', 'product', 'wilayas']));
    }


    public function place_order(Request $request)
    {

        $request->validate([
            'name'  => '',
            'phone'  => '',
            'wilaya'  => '',
            'quantity'  => '',

        ]);

        if (empty($request->name) and empty($request->phone)) {

            Session::flash('message', 'لم تتم العملية بنجاح الرجاء التاكد من المعلومات');

            return redirect()->back()->withInput();
        }

        if (empty($request->phone)) {

            Session::flash('message', 'الرجاء ادخال رقم الهاتف');

            return redirect()->back()->withInput();
        }

        $mesures = ProductMesure::all();
        $options = [];
        foreach ($mesures as $mesure) {
            if ($request->has($mesure->mesure)) {
                $options[$mesure->mesure] = $request[$mesure->mesure];
            };
        }


        DB::transaction(function () use ($request, $options) {

            // Create a new customer
            $customer =  new Customer();
            $customer->name = $request->name;
            $customer->surname = $request->name;
            $customer->phone = $request->phone;
            $customer->address = (new AlgeriaCities())->get_all_wilayas()[$request->wilaya];
            $customer->city = $request->commune;
            $customer->save();


            $order = new Order();
            $order->order_number = random_int(10000, 999999999);
            $order->customer_id = $customer->id;
            $order->status = 'placed';
            $order->notes = $request->note;
            $order->shipping_type = $request->delivery_type;
            $order->shipping_price = $request->delivery_fees;
            $order->total_price = $request->unit_price;
            $order->save();


            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $request->product_id;
            $orderItem->quantity = $request->quantity;
            $orderItem->unit_price = $request->unit_price;
            $orderItem->options = $options;
            $orderItem->save();

            // Send email Notification
            if ($this->settings->email) {
                // Mail::to('gachtoun@gmail.com')
                Mail::to($this->settings->email)
                    ->send(new OrderPlaced($order));
            }
        });



        Session::flash('message', 'تم تسجيل الطلب بنجاح');
        Session::flash('alert-class', 'alert-success');

        return redirect()->back();
    }


    public function livewire_place_order($data)
    {


        $options = [];

        if (isset($data->mesures)) {

            foreach ($data->mesures as $mesure => $value) {
                // dd($data->mesures, $mesure, $value);
                if (is_string($value)) {
                    $options[0][$mesure] = $value;
                };
            }
        }

        // dd($options);
        DB::transaction(function () use ($data, $options) {

            // customer a new customer
            $customer =  new Customer();
            $customer->name = $data->name;
            $customer->surname = $data->name;
            $customer->phone = $data->phone;
            $customer->address = (new AlgeriaCities())->get_all_wilayas()[$data->wilaya];
            $customer->city = $data->commune;
            $customer->save();


            $order = new Order();
            $order->order_number = random_int(10000, 999999999);
            $order->customer_id = $customer->id;
            $order->status = 'placed';
            $order->notes = $data->notes;
            $order->shipping_type = $data->deliveryType;
            $order->shipping_price = $data->deliveryPrice;
            $order->total_price = $data->price;
            $order->save();


            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $data->productId;
            $orderItem->quantity = $data->quantity;
            $orderItem->unit_price = $data->price;
            $orderItem->options = $options;
            $orderItem->save();

            // Send email Notification
            if ($this->settings->email) {
                // Mail::to('gachtoun@gmail.com')

                Mail::to($this->settings->email)
                    ->send(new OrderPlaced($order));
            }
        });

        // dd($this->settings['social_media'][0]);
        if (isset($this->settings['social_media'][0])) {

            $facebook = new FacebookController();
            $facebook->set_content($data->productId, $data->quantity, $data->deliveryType);
            $facebook->set_custom_data($data->price);
            $facebook->set_user_data($data->phone, '');

            // $facebook->eventId = uniqid('prefix_');
            // $facebook->send('ViewContent');
            $facebook->eventId = uniqid('prefix_');
            // dd($facebook->send('AddPaymentInfo'));
            $facebook->send('AddPaymentInfo');

            $facebook->eventId = uniqid('prefix_');
            $facebook->send('Purchase');
        }



        return true;
    }


    public function search(Request $search)
    {

        $facebook = new FacebookController();

        $facebook->simple_send('ViewContent');
        $facebook->simple_send('Search');







        // $searchResults = (new Search())
        //     ->registerModel(Product::class, 'name', 'description')
        //     ->registerModel(Brand::class, 'name', 'description')
        //     ->registerModel(Category::class, 'name', 'description')
        //     ->search($search->search);


        $settings = $this->settings;

        $products = Product::where('name', 'like', '%' . $search->search . '%')
            ->orWhere('description', 'like', '%' . $search->search . '%')
            ->paginate(10);
        $brands = Brand::where('name', 'like', '%' . $search->search . '%')
            ->orWhere('description', 'like', '%' . $search->search . '%')
            ->paginate(10);
        $categories = Category::where('name', 'like', '%' . $search->search . '%')
            ->orWhere('description', 'like', '%' . $search->search . '%')
            ->paginate(10);



        $isSearch = true;


        return view('shop.shop')->with(compact(['settings', 'products', 'brands', 'categories', 'isSearch']));
    }


    public function get_all_products()
    {
        $showAll = true;
        $settings = $this->settings;
        $products = Product::where('is_visible', true)->OrderBy('updated_at', 'desc')->paginate(10);
        return view('shop.product.index')->with(compact(['settings', 'products', 'showAll']));
    }

    public function get_all_brands()
    {
        $showAll = true;
        $settings = $this->settings;
        $brands = Brand::where('is_visible', true)->has('products')->OrderBy('updated_at', 'desc')->paginate(10);
        return view('shop.brand.index')->with(compact(['settings', 'brands', 'showAll']));
    }

    public function get_all_categories()
    {
        $showAll = true;
        $settings = $this->settings;
        $categories = Category::where('is_visible', true)->has('products')->OrderBy('updated_at', 'desc')->paginate(10);
        return view('shop.category.index')->with(compact(['settings', 'categories', 'showAll']));
    }
}
