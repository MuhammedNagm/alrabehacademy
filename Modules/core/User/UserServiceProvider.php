<?php

namespace Modules\User;

use Modules\User\Communication\CommunicationServiceProvider;
use Modules\User\Communication\Facades\ModulesNotification;
use Modules\Settings\Facades\Settings;
use Modules\User\Classes\TwoFactorAuth;
use Modules\User\Facades\Users;
use Modules\User\Hooks\Users as UserHooks;
use Modules\User\Models\Role;
use Modules\User\Models\User;
use Modules\User\Notifications\UserConfirmationNotification;
use Modules\User\Notifications\UserRegisteredNotification;
use Modules\User\Providers\UserAuthServiceProvider;
use Modules\User\Providers\UserEventServiceProvider;
use Modules\User\Providers\UserObserverServiceProvider;
use Modules\User\Providers\UserRouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'User');

        // Load translation
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'User');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->registerWidgets();

        $this->registerCustomFieldsModels();
        $this->addEvents();
        $this->registerHooks();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $configFilesPath = __DIR__ . '/config';

        $configFiles = \File::allFiles($configFilesPath);

        foreach ($configFiles as $file) {
            $config = $file->getBasename('.php');

            $this->mergeConfigFrom($configFilesPath . '/' . $file->getBasename(), $config);
        }

        $this->app->register(UserRouteServiceProvider::class);
        $this->app->register(UserAuthServiceProvider::class);
        $this->app->register(UserObserverServiceProvider::class);
        $this->app->register(UserEventServiceProvider::class);
        $this->app->register(CommunicationServiceProvider::class);


        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Users', Users::class);

        });

        $this->app->singleton('TwoFactorAuth', function () {

            $is_two_factor_auth_enabled = \Settings::get('two_factor_auth_enabled', false);

            $providerKey = \Settings::get('two_factor_auth_provider');

            $providerKey = ucfirst($providerKey);

            if ($is_two_factor_auth_enabled && class_exists("Modules\User\Services\\$providerKey")) {
                $provider = app("Modules\User\Services\\$providerKey");
            } else {
                $provider = null;
            }

            return new TwoFactorAuth($provider);
        });

        $this->app['router']->pushMiddlewareToGroup('web', \Modules\User\Middleware\CookieConsentMiddleware::class);

    }

    public function registerWidgets()
    {
        \Shortcode::addWidget('new_users', \Modules\User\Widgets\NewUsersWidget::class);
    }

    protected function registerCustomFieldsModels()
    {
        Settings::addCustomFieldModel(User::class);
        Settings::addCustomFieldModel(Role::class);
    }

    protected function addEvents()
    {
        ModulesNotification::addEvent(
            'notifications.user.registered',
            'New user registration',
            UserRegisteredNotification::class);

        ModulesNotification::addEvent(
            'notifications.user.confirmation',
            'New user confirmation',
            UserConfirmationNotification::class);
    }

    protected function registerHooks()
    {

        \Actions::add_action('footer_js', [UserHooks::class, 'add_cookie_consent'], 12);
        \Actions::add_action('admin_footer_js', [UserHooks::class, 'add_cookie_consent'], 12);


    }
}
