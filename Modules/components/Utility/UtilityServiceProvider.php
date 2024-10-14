<?php

namespace Modules\Components\Utility;

use Modules\Components\Utility\Facades\Address\Address;
use Modules\Components\Utility\Facades\Category\Category;
use Modules\Components\Utility\Facades\Tag\Tag;
use Modules\Components\Utility\Facades\Utility;
use Modules\Components\Utility\Providers\UtilityAuthServiceProvider;
use Modules\Components\Utility\Providers\UtilityObserverServiceProvider;
use Modules\Components\Utility\Providers\UtilityRouteServiceProvider;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class UtilityServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */

    public function boot()
    {
        // Load view
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'Utility');

        // Load translation
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'Utility');

        // Load migrations
//        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->registerCustomFieldsModels();

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/utility.php', 'utility');

        $this->app->register(UtilityRouteServiceProvider::class);
        $this->app->register(UtilityAuthServiceProvider::class);
        $this->app->register(UtilityObserverServiceProvider::class);

        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Utility', Utility::class);
            $loader->alias('Address', Address::class);
            $loader->alias('Category', Category::class);
            $loader->alias('Tag', Tag::class);
        });
    }

    protected function registerCustomFieldsModels()
    {
    }
}
