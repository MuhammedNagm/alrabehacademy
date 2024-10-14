<?php

namespace Modules\Foundation;

use Modules\Activity\ActivityServiceProvider;
use Modules\Elfinder\ElfinderServiceProvider;
use Modules\Foundation\Console\Commands\ModelCacheFlush;
use Modules\Foundation\Console\Commands\TranslateLocalisation;
use Modules\Foundation\Facades\Actions;
use Modules\Foundation\Facades\ModulesForm;
use Modules\Foundation\Facades\Filters;
use Modules\Foundation\Facades\Language;
use Modules\Foundation\Http\Middleware\SetLocale;
use Modules\Foundation\Providers\RouteServiceProvider;
use Modules\Foundation\Search\FulltextServiceProvider;
use Modules\Foundation\Shortcodes\Facades\Shortcode;
use Modules\Foundation\Shortcodes\ShortcodesServiceProvider;
use Modules\Foundation\View\Facades\JavaScriptFacade;
use Modules\Foundation\View\Transformers\Transformer;
use Modules\Foundation\View\ViewBinder\ModulesViewBinder;
use Modules\Media\MediaServiceProvider;
use Modules\Menu\Facades\Menus;
use Modules\Menu\MenuServiceProvider;
use Modules\User\Communication\Facades\ModulesNotification;
use Modules\Settings\Facades\Modules;
use Modules\Settings\Facades\Settings;
use Modules\Settings\SettingsServiceProvider;
use Modules\Theme\Facades\Theme;
use Modules\Theme\ThemeServiceProvider;
use Modules\User\Facades\Roles;
use Modules\User\Facades\TwoFactorAuth;
use Modules\User\UserServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Hashids\Hashids;

class FoundationServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'Modules');
        // Load translation
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'Modules');
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $helpers = \File::glob(__DIR__ . '/Helpers/*.php');

        foreach ($helpers as $helper) {
            require_once $helper;
        }

        $this->app->register(RouteServiceProvider::class);
        $this->app->register(FulltextServiceProvider::class);
        $this->app->register(ShortcodesServiceProvider::class);
        $this->app->register(UserServiceProvider::class);
        $this->app->register(MenuServiceProvider::class);
        $this->app->register(SettingsServiceProvider::class);
        $this->app->register(ActivityServiceProvider::class);
        $this->app->register(MediaServiceProvider::class);
        $this->app->register(ElfinderServiceProvider::class);
        $this->app->register(ThemeServiceProvider::class);

        $this->app->singleton('JavaScript', function ($app) {
            return new Transformer(
                new ModulesViewBinder($app['events'], config('javascript.bind_js_vars_to_this_view')),
                config('javascript.js_namespace')
            );
        });


        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Language', Language::class);
            $loader->alias('Theme', Theme::class);
            $loader->alias('Roles', Roles::class);
            $loader->alias('ModulesForm', ModulesForm::class);
            $loader->alias('Actions', Actions::class);
            $loader->alias('Filters', Filters::class);
            $loader->alias('Menus', Menus::class);
            $loader->alias('Settings', Settings::class);
            $loader->alias('Modules', Modules::class);
            $loader->alias('Shortcode', Shortcode::class);
            $loader->alias('TwoFactorAuth', TwoFactorAuth::class);
            $loader->alias('ModulesNotification', ModulesNotification::class);
            $loader->alias('JavaScript', JavaScriptFacade::class);
        });

        $this->app['router']->pushMiddlewareToGroup('web', SetLocale::class);

        Actions::do_action('post_coral_registration');

        // Bind 'hashids' shared component to the IoC container
        $this->app->singleton('hashids', function ($app) {

            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

            $salt = hash('sha256', 'Modules');

            return new Hashids($salt, 10, $alphabet);
        });

        $this->registerConfig();
        $this->registerCommand();
    }

    protected function registerConfig()
    {
        $configFiles = \File::glob(__DIR__ . '/config/*.php');

        foreach ($configFiles as $config) {
            $key = basename($config, '.php');
            $this->mergeConfigFrom(__DIR__ . "/config/$key.php", $key);
        }
    }

    protected function registerCommand()
    {
        $this->commands(ModelCacheFlush::class);
        $this->commands(TranslateLocalisation::class);
    }

    public function provides()
    {
        return ['hashids', 'JavaScript'];
    }
}
