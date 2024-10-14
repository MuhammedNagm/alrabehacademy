<?php

namespace Modules\User\Communication\Facades;

use Modules\User\Communication\Models\NotificationTemplate;
use Illuminate\Support\Facades\Facade;


/**
 * @method static addEvent($name, $friendlyName, $notificationClass, $title = '', $body = [])
 * @method static getEventsList();
 * @method static insertNewEventsToDatabase();
 * @method static getEventByName($name);
 * @method static getNotificationParametersDescription(NotificationTemplate $notificationTemplate);
 *
 * Class ModulesNotification
 * @package Modules\User\Communication\Facades
 */
class ModulesNotification extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Modules\User\Communication\Classes\ModulesNotification::class;
    }
}
