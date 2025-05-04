<?php

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

if (!function_exists('resolve_tenant_from_request')) {
    function resolve_tenant_from_request(): ?\App\Models\Tenant {
        // Return cached tenant if already resolved
        if (app()->has('tenant')) {
            return app()->get('tenant');
        }

        // Get tenant domain from request
        $domain = get_tenant_domain();

        if (!$domain) {
            abort(404, 'Invalid tenant identifier');
        }

        // Find and cache tenant
        $tenant = cache()->remember("tenant.{$domain}", now()->addHours(1), function () use ($domain) {
            return \App\Models\Tenant::with('settings')
                ->where('domain', $domain)
                ->first();
        });

        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        if (!$tenant->active) {
            abort(403, 'Tenant is inactive');
        }

        // Store tenant in container
        app()->instance('tenant', $tenant);

        return $tenant;
    }
}

if (!function_exists('get_tenant_domain')) {
    function get_tenant_domain(): ?string {
        $request = request();

        // Try to get tenant from route parameter first
        if ($routeTenant = $request->route('tenant')) {
            return $routeTenant;
        }

        // Then try from subdomain
        $host = $request->getHost();
        $parts = explode('.', $host);

        return count($parts) >= 3 ? $parts[0] : null;
    }
}

if (! function_exists('tenant_route')) {
    function tenant_route($name, $parameters = [], $absolute = true) {
        if (!is_array($parameters)) {
            $parameters = [$parameters];
        }

        $tenant = resolve_tenant_from_request();
        $params = array_merge(['tenant' => $tenant->domain], $parameters);

        // Generate the base route
        $url = route($name, $params, $absolute);

        // If it's a subdomain request, return the URL as is
        if (is_subdomain_request()) {
            return $url;
        }

        // For prefix-based routes, convert subdomain URL to prefix format
        $mainDomain = config('app.url');
        $baseUrl = str_replace(['http://', 'https://'], '', $mainDomain);
        $pattern = "https://{$tenant->domain}." . $baseUrl;

        // Handle both http and https
        $httpPattern = "http://{$tenant->domain}." . $baseUrl;

        if (str_starts_with($url, $pattern)) {
            return str_replace($pattern, $mainDomain . '/' . $tenant->domain, $url);
        }

        if (str_starts_with($url, $httpPattern)) {
            return str_replace($httpPattern, $mainDomain . '/' . $tenant->domain, $url);
        }

        return $url;
    }
}
