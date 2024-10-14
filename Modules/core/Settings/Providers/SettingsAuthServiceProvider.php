<?php

namespace Modules\Settings\Providers;

use Modules\Settings\Models\CustomFieldSetting;
use Modules\Settings\Models\Module;
use Modules\Settings\Models\Setting;
use Modules\Settings\Policies\CustomFieldSettingPolicy;
use Modules\Settings\Policies\ModulePolicy;
use Modules\Settings\Policies\SettingPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class SettingsAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Setting::class => SettingPolicy::class,
        Module::class => ModulePolicy::class,
        CustomFieldSetting::class => CustomFieldSettingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
