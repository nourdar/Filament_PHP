<?php

use App\Http\Controllers\AlgeriaCities;
use App\Http\Controllers\DeliveryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Models\Delivery;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [ShopController::class, 'index']);
Route::get('/brand/{id}', [ShopController::class, 'show_brand']);
Route::get('/category/{id}', [ShopController::class, 'show_category']);
Route::get('/product/{id}', [ShopController::class, 'show_product']);
Route::get('/wilayas', [AlgeriaCities::class, 'get_all_wilayas']);
Route::get('/communs/{wilayaCode}', [AlgeriaCities::class, 'get_all_communs']);
Route::get('/yalidine-delivery-fees/{wilaya}', [DeliveryController::class, 'get_yalididne_delivery_fees']);

Route::post('/place-order', [ShopController::class, 'place_order']);
