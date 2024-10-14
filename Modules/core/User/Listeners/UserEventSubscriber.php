<?php

namespace Modules\User\Listeners;

use Modules\User\Mail\UserRegistered;
use Illuminate\Auth\Events\Registered;

class UserEventSubscriber
{
    /**
     * Handle user login events.
     */
    public function onUserLogin($event)
    {
        $user = $event->user;

        activity()
            ->causedBy($user)
            ->withProperties(['ip' => request()->ip()])
            ->log("{$user->name} logged In");
    }

    /**
     * Handle user logout events.
     */
    public function onUserLogout($event)
    {
        $user = $event->user;

        activity()
            ->causedBy($user)
            ->withProperties(['ip' => request()->ip()])
            ->log("{$user->name} logged Out");
    }

    /**
     * Handle user registration events.
     */
    public function onUserRegistered($event)
    {
        $user = $event->user;

        activity()
            ->causedBy($user)
            ->withProperties(['ip' => request()->ip()])
            ->log("{$user->name} registered");

        event('notifications.user.registered', ['user' => $user]);
    }

    /**
     * @param $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'Modules\User\Listeners\UserEventSubscriber@onUserLogin'
        );

        $events->listen(
            'Illuminate\Auth\Events\Logout',
            'Modules\User\Listeners\UserEventSubscriber@onUserLogout'
        );

        // $events->listen(Registered::class,
        //     'Modules\User\Listeners\UserEventSubscriber@onUserRegistered');
    }

}
