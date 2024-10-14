<?php

namespace Modules\User\Communication\Providers;

use Modules\User\Communication\Observers\NotificationTemplateObserver;
use Modules\User\Communication\Models\NotificationTemplate;
use Illuminate\Support\ServiceProvider;

class NotificationObserverServiceProvider extends ServiceProvider
{
    /**
     * Register Observers
     */
    public function boot()
    {
        NotificationTemplate::observe(NotificationTemplateObserver::class);
    }
}
