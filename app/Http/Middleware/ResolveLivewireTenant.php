<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ResolveLivewireTenant
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('livewire/*')) {
            $referer = $request->header('referer');
            if ($referer) {
                $originalUrl = $request->url();
                $request->server->set('HTTP_HOST', parse_url($referer, PHP_URL_HOST));
                $request->server->set('REQUEST_URI', parse_url($referer, PHP_URL_PATH));
                $tenant = resolve_tenant_from_request();
                $request->server->set('HTTP_HOST', parse_url($originalUrl, PHP_URL_HOST));
                $request->server->set('REQUEST_URI', parse_url($originalUrl, PHP_URL_PATH));
                if ($tenant) {
                    app()->instance('tenant', $tenant);
                }
            }
        }

        return $next($request);
    }
}
