<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant;
use Illuminate\Http\Request;

class ResolveTenant
{
    public function handle(Request $request, Closure $next)
    {
        $tenant = resolve_tenant_from_request();
        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        return $next($request);
    }
}
