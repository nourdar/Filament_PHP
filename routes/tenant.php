<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

use GuzzleHttp\Client;
use App\Models\Delivery;

use App\Http\Controllers\AlgeriaCities;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\NoestController;
use App\Http\Controllers\YalidineController;
use App\Http\Controllers\ZrExpressController;
/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    // 'universal',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {


    Route::get('/', [ShopController::class, 'index'])->name('welcome');
    Route::get('/brand/{id}', [ShopController::class, 'show_brand']);
    Route::get('/category/{id}', [ShopController::class, 'show_category']);
    Route::get('/product/{id}', [ShopController::class, 'show_product'])->name('product.show');
    Route::get('/all-categories', [ShopController::class, 'get_all_categories'])->name('categories.show');
    Route::get('/all-brands', [ShopController::class, 'get_all_brands'])->name('brands.show');
    Route::get('/all-products', [ShopController::class, 'get_all_products'])->name('products.show');
    Route::get('/wilayas', [AlgeriaCities::class, 'get_all_wilayas']);
    Route::get('/communs/{wilayaCode}', [AlgeriaCities::class, 'get_all_communes']);
    Route::get('/yalidine-delivery-fees/{wilaya}', [DeliveryController::class, 'get_yalididne_delivery_fees']);
    Route::get('/yalidine-delivery/all', [YalidineController::class, 'getAllDeliveryFees']);
    Route::get('/calculate-delivery-fees/{wilaya}', [DeliveryController::class, 'calculate_delivery_fees']);
    Route::get('/yalidine/webhook', [DeliveryController::class, 'yalidine_webhook']);
    Route::get('/delivery-seeder', [DeliveryController::class, 'seeder']);
    Route::get('/noest', [NoestController::class, 'test']);

    Route::post('/place-order', [ShopController::class, 'place_order']);

    Route::post('search', [ShopController::class, 'search'])->name('search');


    Route::get('/optimize', function () {

        Artisan::call('optimize:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');

        dd(Artisan::output());
    });


    Route::get('/install', function () {

        Artisan::call('optimize:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');

        Artisan::call('storage:link');
        Artisan::call('migrate');

        dd(Artisan::output());
    });


    Route::get('/zrexpress/create-coli', [ZrExpressController::class, 'index'])->name('zrexpress.create');

    Route::get('/get-zrexpress-tarifs', [ZrExpressController::class, 'get_tarifs'])->name('zrexpress.tarifs');




    Route::get('/link-storage', function () {
        Artisan::call('storage:link');
    });
});
