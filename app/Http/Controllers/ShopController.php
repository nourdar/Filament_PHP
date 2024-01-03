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
use Symfony\Component\Console\Input\Input;

class ShopController extends Controller
{

    public $settings;
    public $cities;

    public function __construct(){

        $this->settings = Settings::first();
        $this->cities = new AlgeriaCities();
    }



    public function index(){

        $title = ($this->settings?->name) ?? 'SARL NAMEQUE - contact@nameque.net';

        $settings = $this->settings;

        $products = Product::where('is_visible', true)->orderBy('updated_at', 'desc')->paginate(12, ['*'], 'products');
        $brands = Brand::where('is_visible', true)->has('products')->paginate(3, ['*'], 'brands');
        $categories = Category::where('is_visible', true)->has('products')->paginate(3, ['*'], 'categories');

        return view('lshop.lshop')->with(compact(['title', 'settings', 'products', 'brands', 'categories']));
    }


    public function show_brand(Request $request, $id) {

        $brand = Brand::where('is_visible', true)->with('products')->findOrFail($id);

        $title = ($this->settings?->name.' - '. $brand?->name) ?? 'test';

        $settings = $this->settings;

        $products = $brand->products()->paginate(10, ['*'], 'products');

        $brands = Brand::where('is_visible', true)->paginate(10, ['*'], 'brands');
        $categories = Category::where('is_visible', true)->paginate(10, ['*'], 'categories');

        return view('shop.brand.show')->with(compact(['title', 'settings', 'products', 'brand', 'categories']));
    }

    public function show_category(Request $request, $id) {

        $category = Category::where('is_visible', true)->with('products')->findOrFail($id);

        $title = ($this->settings?->name.' - '. $category?->name) ?? 'test';

        $settings = $this->settings;

        $products = $category->products()->paginate(10, ['*'], 'products');


        $categories = Category::where('is_visible', true)->paginate(10, ['*'], 'categories');

        return view('shop.category.show')->with(compact(['title', 'settings', 'products',  'category']));
    }

    public function show_product(Request $request, $id) {

        $product = Product::where('is_visible', true)->with(['brand', 'categories'])->findOrFail($id);

        $title = ($this->settings?->name.' - '. $product?->name) ?? 'test';

        $wilayas = $this->cities->get_all_wilayas();

        $settings = $this->settings;

        return view('shop.product.show')->with(compact(['title', 'settings', 'product', 'wilayas']));
    }


    public function place_order(Request $request){

        $request->validate([
            'name'  => '',
            'phone'  => '',
            'wilaya'  => '',
            'quantity'  => '',

        ]);

        if(empty($request->name) and empty($request->phone)){

            Session::flash('message', 'لم تتم العملية بنجاح الرجاء التاكد من المعلومات');

            return redirect()->back()->withInput();
        }

        if(empty($request->phone)){

            Session::flash('message', 'الرجاء ادخال رقم الهاتف');

            return redirect()->back()->withInput();
        }

        $mesures = ProductMesure::all();
        $options = [];
        foreach($mesures as $mesure){
            if($request->has($mesure->mesure)){
                $options[$mesure->mesure] = $request[$mesure->mesure];
            };
        }


        DB::transaction(function() use($request, $options) {

        // Create a new customer
        $customer =  new Customer();
        $customer->name = $request->name;
        $customer->surname = $request->name;
        $customer->phone = $request->phone;
        $customer->address = (new AlgeriaCities())->get_all_wilayas()[$request->wilaya];
        $customer->city = $request->commune;
        $customer->save();


        $order = new Order();
        $order->order_number = random_int(10000, 99999);
        $order->customer_id = $customer->id;
        $order->status = 'placed';
        $order->notes = $request->note;
        $order->shipping_type = $request->delivery_type;
        $order->shipping_price = $request->delivery_fees;
        $order->save();


        $orderItem = new OrderItem();
        $orderItem->order_id = $order->id;
        $orderItem->product_id = $request->product_id;
        $orderItem->quantity = $request->quantity;
        $orderItem->unit_price = $request->unit_price;
        $orderItem->options = $options;
        $orderItem->save();

        // Send email Notification
            if($this->settings->email){
                // Mail::to('gachtoun@gmail.com')
                Mail::to($this->settings->email)
                ->send(new OrderPlaced($order));
            }


    });



    Session::flash('message', 'تم تسجيل الطلب بنجاح');
    Session::flash('alert-class', 'alert-success');

    return redirect()->back();

    }


    public function search(Request $search){

        // $searchResults = (new Search())
        //     ->registerModel(Product::class, 'name', 'description')
        //     ->registerModel(Brand::class, 'name', 'description')
        //     ->registerModel(Category::class, 'name', 'description')
        //     ->search($search->search);


        $settings = $this->settings;

        $products = Product::where('name', 'like', '%'.$search->search.'%')
            ->orWhere('description', 'like', '%'.$search->search.'%')
            ->paginate(10);
        $brands = Brand::where('name', 'like', '%'.$search->search.'%')
            ->orWhere('description', 'like', '%'.$search->search.'%')
            ->paginate(10);
        $categories = Category::where('name', 'like', '%'.$search->search.'%')
            ->orWhere('description', 'like', '%'.$search->search.'%')
            ->paginate(10);



            $isSearch = true;


            return view('shop.shop')->with(compact(['settings', 'products', 'brands', 'categories', 'isSearch']));

    }


    public function get_all_products(){
        $showAll = true;
        $settings = $this->settings;
        $products = Product::where('is_visible', true)->OrderBy('updated_at', 'desc')->paginate(10);
        return view('shop.product.index')->with(compact(['settings', 'products', 'showAll']));
    }

    public function get_all_brands(){
        $showAll = true;
        $settings = $this->settings;
        $brands = Brand::where('is_visible', true)->has('products')->OrderBy('updated_at', 'desc')->paginate(10);
        return view('shop.brand.index')->with(compact(['settings', 'brands', 'showAll']));
    }

    public function get_all_categories(){
        $showAll = true;
        $settings = $this->settings;
        $categories = Category::where('is_visible', true)->has('products')->OrderBy('updated_at', 'desc')->paginate(10);
        return view('shop.category.index')->with(compact(['settings', 'categories', 'showAll']));
    }
}
