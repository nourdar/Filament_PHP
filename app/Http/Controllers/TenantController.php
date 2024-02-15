<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::all();

        dd($tenants);
        // return view('admin.tenants.index', compact('tenants'));
    }

    // public function create()
    // {
    //     // return view('admin.tenants.create');
    // }

    public function store(Request $request)
    {



        // dd($request->shop_name . '.localhost');
        $tenant = new Tenant();
        $tenant->id = $request->shop_name;
        $tenant->domain = $request->shop_name . '.localhost';
        $tenant->save();

        $tenant->domains()->create(['domain' => $request->shop_name . '.localhost']);

        // dd($tenant);



        $tenant->run(function () use ($request) {

            $user = User::create(
                [
                    'name' => $request->shop_name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password)
                ]
            );

            Artisan::call('shield:generate', [
                '--all' => true,

            ]);

            Artisan::call('shield:super-admin');

            Role::create([
                'guard' => 'web',
                'name'  => 'free_plan'
            ]);

            $user->assignRole('free_plan');
        });


        return redirect()->to("http://$request->shop_name.localhost:8000");
    }

    public function suspend(Tenant $tenant)
    {
        $tenant->suspended_at = now();
        $tenant->save();
        // return redirect()->route('admin.tenants.index');
    }

    public function delete(Tenant $tenant)
    {
        $tenant->delete();
        // return redirect()->route('admin.tenants.index');
    }
}
