<?php

namespace Modules\User\Communication\Policies;

use Modules\User\Communication\Models\Notification;
use Modules\User\Models\User;

class NotificationPolicy
{

    /**
     * @param User $user
     * @param Notification|null $notification
     * @return bool
     */
    public function view(User $user, Notification $notification = null)
    {
        if ($user->can('Notification::my_notification.view')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * @param User $user
     * @param Notification $notification
     * @return bool
     */
    public function update(User $user, Notification $notification)
    {
        if ($user->can('Notification::my_notification.update')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Notification $notification
     * @return bool
     */
    public function destroy(User $user, Notification $notification)
    {
        if ($user->can('Notification::my_notification.delete')) {
            return true;
        }
        return false;
    }


    /**
     * @param $user
     * @param $ability
     * @return bool
     */
    public function before($user, $ability)
    {
        if (isSuperUser($user)) {
            return true;
        }
        return null;
    }
}
