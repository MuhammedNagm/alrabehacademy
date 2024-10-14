<?php

namespace Modules\Settings\Policies;

use Modules\Settings\Models\Module;
use Modules\User\Models\User;

class ModulePolicy
{
    /**
     * @param User $user
     * @param Module|null $module
     * @return bool
     */
    public function manage(User $user, Module $module = null)
    {
        if ($user->can('Settings::module.manage')) {
            return true;
        }
        return false;
    }

    /**
     * @param $user
     * @param $ability
     * @return bool|null
     */
    public function before($user, $ability)
    {
        if (isSuperUser($user)) {
            return true;
        }
        return null;
    }
}
