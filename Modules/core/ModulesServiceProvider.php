<?php

namespace Modules;

use Illuminate\Support\ServiceProvider;
use Modules\Components\ComponentsServiceProvider;
use Modules\Foundation\FoundationServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(FoundationServiceProvider::class);

        //load modules last thing
        if (class_exists(ComponentsServiceProvider::class)) {
            $this->app->register(ComponentsServiceProvider::class);
        }
    }
}
