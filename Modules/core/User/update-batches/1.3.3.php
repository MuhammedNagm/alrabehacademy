<?php

use Illuminate\Support\Facades\Schema;

use Modules\User\Communication\database\migrations\CreateNotificationTemplatesTable;
use Modules\User\Communication\database\migrations\CreateNotificationsTable;
use Modules\User\Communication\database\seeds\NotificationDatabaseSeeder;

if (!Schema::hasTable('notification_templates')) {

    $migrationObject = new CreateNotificationTemplatesTable;
    $migrationObject->up();

    $seeder = new NotificationDatabaseSeeder();
    $seeder->run();

}

if (!Schema::hasTable('notifications')) {

    $migrationObject = new CreateNotificationsTable;
    $migrationObject->up();

    $templatesSeeder = new \Modules\User\database\seeds\UserNotificationTemplatesSeeder();
    $templatesSeeder->run();

}



