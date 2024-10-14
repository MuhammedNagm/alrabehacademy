<?php

namespace Modules\Settings;

use Modules\Settings\Console\Commands\ModuleManager;
use Modules\Settings\Facades\Settings;
use Modules\Settings\Models\Setting;
use Modules\Settings\Providers\SettingsAuthServiceProvider;
use Modules\Settings\Providers\SettingsRouteServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Load view
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'Settings');

        // Load translation
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'Settings');

        // load
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'Custom');

        //
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'Module');

        //
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'Cache');


        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->registerCustomFieldsModels();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/settings.php', 'settings');
        $this->app->register(SettingsAuthServiceProvider::class);
        $this->app->register(SettingsRouteServiceProvider::class);
        $this->registerCommand();
    }

    protected function registerCustomFieldsModels()
    {
        Settings::addCustomFieldModel(Setting::class);
    }

    protected function registerCommand()
    {
        $this->commands(ModuleManager::class);
    }
}
