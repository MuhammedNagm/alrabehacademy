<?php

namespace Modules\Components\Utility\Providers;

use Modules\Foundation\Providers\BaseUninstallModuleServiceProvider;
use Modules\Components\Utility\database\migrations\CreateAddressTables;
use Modules\Components\Utility\database\migrations\CreateCategoryAttributeTables;
use Modules\Components\Utility\database\migrations\CreateRatingsTable;
use Modules\Components\Utility\database\migrations\CreateTagTables;
use Modules\Components\Utility\database\migrations\CreateSchedulestsTable;
use Modules\Components\Utility\database\migrations\CreateWishlistsTable;
use Modules\Components\Utility\database\seeds\UtilityDatabaseSeeder;

class UninstallModuleServiceProvider extends BaseUninstallModuleServiceProvider
{
    protected $migrations = [
        CreateRatingsTable::class,
        CreateWishlistsTable::class,
        CreateAddressTables::class,
        CreateTagTables::class,
        CreateCategoryAttributeTables::class,
        CreateSchedulestsTable::Class,
    ];

    protected function booted()
    {
        $this->dropSchema();

        $utilityDatabaseSeeder = new UtilityDatabaseSeeder();

        $utilityDatabaseSeeder->rollback();
    }
}
