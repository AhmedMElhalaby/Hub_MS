<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ResolveLivewireTenant
{
    public function handle(Request $request, Closure $next)
    {
        // For Livewire requests, extract tenant from referer URL
        if ($request->is('livewire/*')) {
            $referer = $request->header('referer');
            if ($referer) {
                // Store the original URL to restore it later
                $originalUrl = $request->url();

                // Temporarily modify the request URL to the referer
                $request->server->set('HTTP_HOST', parse_url($referer, PHP_URL_HOST));
                $request->server->set('REQUEST_URI', parse_url($referer, PHP_URL_PATH));

                // Resolve tenant from the modified request
                $tenant = resolve_tenant_from_request();

                // Restore the original URL
                $request->server->set('HTTP_HOST', parse_url($originalUrl, PHP_URL_HOST));
                $request->server->set('REQUEST_URI', parse_url($originalUrl, PHP_URL_PATH));

                if ($tenant) {
                    app()->instance('tenant', $tenant);
                }
            }
        } else {
            // For non-Livewire requests, resolve tenant normally
            if (!app()->has('tenant')) {
                $tenant = resolve_tenant_from_request();
                if ($tenant) {
                    app()->instance('tenant', $tenant);
                }
            }
        }

        return $next($request);
    }
}
