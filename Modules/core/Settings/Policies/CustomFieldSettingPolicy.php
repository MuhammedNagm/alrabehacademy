<?php

namespace Modules\Settings\Policies;

use Modules\Settings\Models\CustomFieldSetting;
use Modules\User\Models\User;

class CustomFieldSettingPolicy
{

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('Settings::custom_field_setting.view')) {
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
        return $user->can('Settings::custom_field_setting.create');
    }

    /**
     * @param User $user
     * @param CustomFieldSetting $setting
     * @return bool
     */
    public function update(User $user, CustomFieldSetting $setting)
    {
        if ($user->can('Settings::custom_field_setting.update')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param CustomFieldSetting $setting
     * @return bool
     */
    public function destroy(User $user, CustomFieldSetting $setting)
    {
        if ($user->can('Settings::custom_field_setting.delete')) {
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
