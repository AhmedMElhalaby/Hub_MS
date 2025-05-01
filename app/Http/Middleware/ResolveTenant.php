<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class ResolveTenant
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();

        // Extract subdomain from the host
        $parts = explode('.', $host);
        if (count($parts) >= 3) {
            $subdomain = $parts[0];
        } else {
            abort(404, 'Invalid tenant domain');
        }

        // Find tenant by subdomain
        $tenant = Tenant::with('settings')
            ->where('domain', $subdomain)
            ->firstOrFail();

        if (!$tenant->active) {
            abort(403, 'Tenant is inactive');
        }

        // Config::set('database.connections.tenant.database', $tenant->database);
        app()->instance('tenant', $tenant);
        return $next($request);
    }
}
