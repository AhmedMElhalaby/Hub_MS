<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator as LaravelUrlGenerator;
class TenantServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->extend('url', function ($url, $app) {
            return new class($app['router']->getRoutes(), $app->rebinding(
                'request', $this->requestRebinder()
            )) extends LaravelUrlGenerator {
                public function route($name, $parameters = [], $absolute = true)
                {
                    if (str_starts_with($name, 'tenant.')) {
                        $domain = request()->route('tenant');
                        $tenant = \App\Models\Tenant::with('settings')
                            ->where('domain', $domain)
                            ->first();
                        if ($tenant) {
                            app()->instance('tenant', $tenant);
                            $parameters = array_merge(['tenant' => $domain], is_array($parameters) ? $parameters : [$parameters]);
                        }
                    }
                    return parent::route($name, $parameters, $absolute);
                }
            };
        });
    }

    protected function requestRebinder()
    {
        return function ($app, $request) {
            $app['url']->setRequest($request);
        };
    }
}
