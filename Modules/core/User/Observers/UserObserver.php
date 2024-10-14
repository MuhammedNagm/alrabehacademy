<?php

namespace Modules\User\Observers;

use Modules\User\Models\Role;
use Modules\User\Models\User;

class UserObserver
{

    /**
     * @param User $user
     */
    public function created(User $user)
    {
        if (!$user->hasAnyRole(Role::all())) {
            $user->assignRole(\Settings::get('default_user_role', 'Member'));
        }
    }

    /**
     * @param User $user
     * @throws \Exception
     */
    public function deleted(User $user)
    {

    }
}
