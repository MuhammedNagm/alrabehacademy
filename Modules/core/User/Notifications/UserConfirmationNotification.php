<?php

namespace Modules\User\Notifications;

use Modules\User\Communication\Classes\ModulesBaseNotification;

class UserConfirmationNotification extends ModulesBaseNotification
{
    /**
     * @return mixed
     */
    public function getNotifiables()
    {
        return $this->data['user'];
    }

    public function getNotificationMessageParameters($notifiable, $channel)
    {
        $user = $this->data['user'];

        return [
            'name' => $user->name,
            'confirmation_link' => url('register/confirm/' . $user->confirmation_code)
        ];
    }

    public static function getNotificationMessageParametersDescriptions()
    {
        return [
            'name' => trans('User::labels.confirmation.notification_parameters.name'),
            'confirmation_link' => trans('User::labels.confirmation.notification_parameters.link'),
        ];
    }
}
