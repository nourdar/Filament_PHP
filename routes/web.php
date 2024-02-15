<?php

use App\Http\Controllers\TenantController;
use App\Models\User;
use App\Models\Tenant;
use GuzzleHttp\Client;
use App\Models\Delivery;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlgeriaCities;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\NoestController;
use App\Http\Controllers\CentralController;
use App\Http\Controllers\DeliveryController;
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

Route::post('/create-shop', [TenantController::class, 'store'])->name('create-shop');

Route::get('/create-customer', function () {
    return view('tenant.create')->with(['title' => 'Create']);
    // $tenant = Tenant::create([
    //     'id'    => 'tester1',
    //     'plan' => 'free',

    // ]);


    // $tenant->domains()->create([
    //     'domain' => 'tester1',
    // ]);

    $tenant = Tenant::where('id', 'nour')->first();

    // dd($tenant->domains);
    $tenant->run(function () {
        // $user = User::create(
        //     [
        //         'name' => 'tester',
        //         'email' => 'test@test.com',
        //         'password' => bcrypt('testpassword')
        //     ]
        // );

        $user = User::where('email', 'n.gachtou@hotmail.com')->first();
        // dd($user);
        $user->assignRole('super_admin');

        // $output = Artisan::output();

        // dd($output);
    });
});
