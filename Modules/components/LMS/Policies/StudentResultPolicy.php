<?php

namespace Modules\Components\LMS\Policies;

use Modules\User\Models\User;
use Modules\Components\LMS\Models\StudentResult;

class StudentResultPolicy
{

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('LMS::student_result.view')) {
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
        return $user->can('LMS::student_result.create');
    }

    /**
     * @param User $user
     * @param StudentResult $student_result
     * @return bool
     */
    public function update(User $user, StudentResult $student_result)
    {
        if ($user->can('LMS::student_result.update')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param StudentResult $student_result
     * @return bool
     */
    public function destroy(User $user, StudentResult $student_result)
    {
        if ($user->can('LMS::student_result.delete')) {
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
