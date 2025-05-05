<?php

namespace App\Traits;

trait WithTenantContext
{
    public function bootWithTenantContext()
    {
        if (!app()->has('tenant')) {
            $tenant = resolve_tenant_from_request();
            if ($tenant) {
                app()->instance('tenant', $tenant);
            }
        }
    }
}
