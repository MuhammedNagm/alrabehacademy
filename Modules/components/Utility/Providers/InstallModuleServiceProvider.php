<?php

namespace Modules\Components\Utility\Providers;

use Modules\Foundation\Providers\BaseInstallModuleServiceProvider;
use Modules\Components\Utility\database\migrations\CreateAddressTables;
use Modules\Components\Utility\database\migrations\CreateCategoryAttributeTables;
use Modules\Components\Utility\database\migrations\CreateRatingsTable;
use Modules\Components\Utility\database\migrations\CreateSchedulestsTable;
use Modules\Components\Utility\database\migrations\CreateCommentsTable;
use Modules\Components\Utility\database\migrations\CreateTagTables;
use Modules\Components\Utility\database\migrations\CreateWishlistsTable;
use Modules\Components\Utility\database\seeds\UtilityDatabaseSeeder;

class InstallModuleServiceProvider extends BaseInstallModuleServiceProvider
{
    protected $module_public_path = __DIR__ . '/../public';

    protected $migrations = [
        CreateRatingsTable::class,
        CreateWishlistsTable::class,
        CreateAddressTables::class,
        CreateTagTables::class,
        CreateCategoryAttributeTables::class,
        CreateSchedulestsTable::class,
        CreateCommentsTable::class,
    ];

    protected function booted()
    {
        $this->createSchema();

        $utilityDatabaseSeeder = new UtilityDatabaseSeeder();

        $utilityDatabaseSeeder->run();
    }
}
