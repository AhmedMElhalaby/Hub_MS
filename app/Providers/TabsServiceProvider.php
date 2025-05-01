<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\View\Components\Tabs\Container;
use App\View\Components\Tabs\Tab;
use App\View\Components\Tabs\Content;

class TabsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Blade::component('tabs.container', Container::class);
        Blade::component('tabs.tab', Tab::class);
        Blade::component('tabs.content', Content::class);
    }
}
