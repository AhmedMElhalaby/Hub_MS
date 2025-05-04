<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ResolveTenant
{
    public function handle(Request $request, Closure $next)
    {
        // Get tenant identifier either from subdomain or route parameter
        $tenantIdentifier = $this->getTenantIdentifier($request);

        if (!$tenantIdentifier) {
            abort(404, 'Invalid tenant identifier');
        }

        // Find tenant by domain
        $tenant = Tenant::with('settings')
            ->where('domain', $tenantIdentifier)
            ->firstOrFail();

        if (!$tenant->active) {
            abort(403, 'Tenant is inactive');
        }

        app()->instance('tenant', $tenant);
        return $next($request);
    }

    private function getTenantIdentifier(Request $request): ?string
    {
        // Check if request is using subdomain
        $host = $request->getHost();
        $parts = explode('.', $host);

        // If using subdomain (e.g. tenant.domain.com)
        if (count($parts) >= 3) {
            return $parts[0];
        }

        // If using prefix route parameter (e.g. domain.com/tenant)
        if ($request->route('tenant')) {
            return $request->route('tenant');
        }

        return null;
    }
}
