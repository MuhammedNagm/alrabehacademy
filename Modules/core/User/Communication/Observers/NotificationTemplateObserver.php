<?php

namespace Modules\User\Communication\Observers;

use Modules\User\Communication\Models\NotificationTemplate;

class NotificationTemplateObserver
{

    /**
     * @param NotificationTemplate $notification_template
     */
    public function created(NotificationTemplate $notification_template)
    {
    }
}
