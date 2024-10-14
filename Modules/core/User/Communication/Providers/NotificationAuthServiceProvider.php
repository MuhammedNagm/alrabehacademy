<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 10/22/2017
 * Time: 10:44 PM
 */

namespace Modules\User\Communication\Providers;

use Modules\User\Communication\Models\Notification;
use Modules\User\Communication\Models\NotificationTemplate;
use Modules\User\Communication\Policies\NotificationPolicy;
use Modules\User\Communication\Policies\NotificationTemplatePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class NotificationAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        NotificationTemplate::class => NotificationTemplatePolicy::class,
        Notification::class => NotificationPolicy::class,
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
