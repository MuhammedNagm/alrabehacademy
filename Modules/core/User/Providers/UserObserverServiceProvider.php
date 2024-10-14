<?php

namespace Modules\User\Providers;

use Modules\User\Models\User;
use Modules\User\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class UserObserverServiceProvider extends ServiceProvider
{
    /**
     * Register Observers
     */
    public function boot()
    {
        User::observe(UserObserver::class);
    }
}
