<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('enum_values')){
    function enum_values($enum)
    {
        return array_column($enum::cases(), 'value');
    }
}
if (!function_exists('enum_rules')){
    function enum_rules($enum)
    {
        return  implode(',', enum_values($enum));
    }
}
if (!function_exists('is_subdomain_request')) {
    function is_subdomain_request(): bool {
        $host = request()->getHost();
        $parts = explode('.', $host);
        return count($parts) >= 3;
    }
}

if (!function_exists('get_tenant_domain')) {
    function get_tenant_domain(): ?string {
        $request = request();
        Log::debug('get_tenant_domain:' . __LINE__ . ' - Starting tenant domain resolution', ['request'=> $request->fullUrl()]);

        // Special handling for Livewire requests
        if ($request->is('livewire/*')) {
            $referer = $request->header('referer');
            Log::debug('get_tenant_domain:' . __LINE__ . ' - Livewire request detected', ['referer' => $referer]);

            if ($referer) {
                $refererPath = parse_url($referer, PHP_URL_PATH);
                $pathParts = explode('/', trim($refererPath, '/'));

                // For prefix-based routing
                if (!empty($pathParts[0])) {
                    Log::debug('get_tenant_domain:' . __LINE__ . ' - Found tenant from Livewire referer path', ['tenant' => $pathParts[0]]);
                    return $pathParts[0];
                }

                // For subdomain-based routing
                $refererHost = parse_url($referer, PHP_URL_HOST);
                if ($refererHost) {
                    $hostParts = explode('.', $refererHost);
                    if (count($hostParts) >= 3) {
                        Log::debug('get_tenant_domain:' . __LINE__ . ' - Found tenant from Livewire referer subdomain', ['tenant' => $hostParts[0]]);
                        return $hostParts[0];
                    }
                }
            }

            // If tenant is in session, use it as fallback for Livewire requests
            if (session()->has('tenant_domain')) {
                $sessionTenant = session('tenant_domain');
                Log::debug('get_tenant_domain:' . __LINE__ . ' - Found tenant from session', ['tenant' => $sessionTenant]);
                return $sessionTenant;
            }
        }

        // Try to get tenant from route parameter first
        $routeTenant = $request->route('tenant');
        Log::debug('get_tenant_domain:' . __LINE__ . ' - Route tenant parameter', ['tenant' => $routeTenant]);

        if ($routeTenant && is_string($routeTenant)) {
            Log::debug('get_tenant_domain:' . __LINE__ . ' - Found tenant from route parameter', ['tenant' => $routeTenant]);
            session(['tenant_domain' => $routeTenant]); // Store in session for Livewire requests
            return $routeTenant;
        }

        // Only check subdomain if we're using subdomain routing
        if (is_subdomain_request()) {
            $host = $request->getHost();
            $parts = explode('.', $host);
            Log::debug('get_tenant_domain:' . __LINE__ . ' - Checking subdomain', ['host' => $host, 'parts' => $parts]);

            if (count($parts) >= 3) {
                Log::debug('get_tenant_domain:' . __LINE__ . ' - Found tenant from subdomain', ['tenant' => $parts[0]]);
                session(['tenant_domain' => $parts[0]]); // Store in session for Livewire requests
                return $parts[0];
            }
        }

        Log::debug('get_tenant_domain:' . __LINE__ . ' - No tenant domain found');
        return null;
    }
}

if (!function_exists('resolve_tenant_from_request')) {
    function resolve_tenant_from_request(): ?\App\Models\Tenant {
        try {
            Log::debug('resolve_tenant_from_request:' . __LINE__ . ' - Starting tenant resolution');

            // Return cached tenant if already resolved
            if (app()->has('tenant')) {
                Log::debug('resolve_tenant_from_request:' . __LINE__ . ' - Found tenant in container', ['tenant' => app()->get('tenant')]);
                return app()->get('tenant');
            }

            // Get tenant domain from request
            $domain = get_tenant_domain();
            Log::debug('resolve_tenant_from_request:' . __LINE__ . ' - Got tenant domain', ['domain' => $domain]);

            if (!$domain) {
                Log::debug('resolve_tenant_from_request:' . __LINE__ . ' - No domain found');
                return null;
            }

            // Find and cache tenant
            Log::debug('resolve_tenant_from_request:' . __LINE__ . ' - Looking up tenant in cache/database', ['domain' => $domain]);
            $tenant = cache()->remember("tenant.{$domain}", now()->addHours(1), function () use ($domain) {
                $tenant = \App\Models\Tenant::with('settings')
                    ->where('domain', $domain)
                    ->first();
                Log::debug('resolve_tenant_from_request:' . __LINE__ . ' - Database lookup result', ['tenant' => $tenant]);
                return $tenant;
            });

            if (!$tenant) {
                Log::debug('resolve_tenant_from_request:' . __LINE__ . ' - No tenant found for domain', ['domain' => $domain]);
                return null;
            }

            if (!$tenant->active) {
                Log::debug('resolve_tenant_from_request:' . __LINE__ . ' - Tenant is inactive', ['domain' => $domain]);
                return null;
            }

            // Store tenant in container
            app()->instance('tenant', $tenant);
            Log::debug('resolve_tenant_from_request:' . __LINE__ . ' - Tenant stored in container', ['tenant' => $tenant]);

            return $tenant;
        } catch (\Exception $e) {
            Log::error('resolve_tenant_from_request:' . __LINE__ . ' - Error resolving tenant', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
}

if (! function_exists('tenant_route')) {
    function tenant_route($name, $parameters = [], $absolute = true) {
        Log::debug('tenant_route:' . __LINE__ . ' - Starting tenant route generation', [
            'route' => $name,
            'parameters' => $parameters,
            'absolute' => $absolute
        ]);

        if (!is_array($parameters)) {
            $parameters = [$parameters];
        }

        $tenant = resolve_tenant_from_request();
        Log::debug('tenant_route:' . __LINE__ . ' - Resolved tenant for route', ['tenant' => $tenant]);

        if (!$tenant) {
            Log::error('tenant_route:' . __LINE__ . ' - No tenant found for route generation');
            throw new \Exception('No tenant found for route generation');
        }

        $params = array_merge(['tenant' => $tenant->domain], $parameters);
        Log::debug('tenant_route:' . __LINE__ . ' - Merged route parameters', ['params' => $params]);

        // Generate the base route
        $url = route($name, $params, $absolute);
        Log::debug('tenant_route:' . __LINE__ . ' - Generated base route', ['url' => $url]);

        // If it's a subdomain request, return the URL as is
        if (is_subdomain_request()) {
            Log::debug('tenant_route:' . __LINE__ . ' - Returning subdomain URL', ['url' => $url]);
            return $url;
        }

        // For prefix-based routes, convert subdomain URL to prefix format
        $mainDomain = config('app.url');
        $baseUrl = str_replace(['http://', 'https://'], '', $mainDomain);
        $pattern = "https://{$tenant->domain}." . $baseUrl;
        $httpPattern = "http://{$tenant->domain}." . $baseUrl;

        Log::debug('tenant_route:' . __LINE__ . ' - Converting URL patterns', [
            'mainDomain' => $mainDomain,
            'baseUrl' => $baseUrl,
            'pattern' => $pattern,
            'httpPattern' => $httpPattern
        ]);

        if (str_starts_with($url, $pattern)) {
            $finalUrl = str_replace($pattern, $mainDomain . '/' . $tenant->domain, $url);
            Log::debug('tenant_route:' . __LINE__ . ' - Converted HTTPS URL', ['from' => $url, 'to' => $finalUrl]);
            return $finalUrl;
        }

        if (str_starts_with($url, $httpPattern)) {
            $finalUrl = str_replace($httpPattern, $mainDomain . '/' . $tenant->domain, $url);
            Log::debug('tenant_route:' . __LINE__ . ' - Converted HTTP URL', ['from' => $url, 'to' => $finalUrl]);
            return $finalUrl;
        }

        Log::debug('tenant_route:' . __LINE__ . ' - Returning original URL', ['url' => $url]);
        return $url;
    }
}
