<?php

use GuzzleHttp\Client;
use App\Models\Delivery;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlgeriaCities;
use App\Http\Controllers\CentralController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\NoestController;
use App\Http\Controllers\YalidineController;
use App\Http\Controllers\ZrExpressController;

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

Route::get('/', [CentralController::class, 'index']);
