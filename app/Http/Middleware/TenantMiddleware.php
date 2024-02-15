<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // $tenant = $this->resolveTenant($request);

        // // Set the database connection for the current tenant
        // config(['database.connections.tenant' => [
        //     'driver' => 'mysql',
        //     'host' => $tenant->db_host,
        //     'port' => $tenant->db_port,
        //     'database' => $tenant->db_database,
        //     'username' => $tenant->db_username,
        //     'password' => $tenant->db_password,
        // ]]);

        // DB::purge('tenant');
        // DB::setDefaultConnection('tenant');

        // // Store the current tenant in the request object
        // $request->merge(['tenant' => $tenant]);

        return $next($request);
    }

    protected function resolveTenant($request)
    {
        // Resolve the current tenant based on the request
        // This could be done using a subdomain, path prefix, or custom header
        $tenantId = $request->header('X-Tenant-ID');

        return Tenant::findOrFail($tenantId);
    }
}
