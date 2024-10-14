<?php

namespace Modules\Foundation\database\seeds;

use Modules\Activity\database\seeds\ActivitiesDatabaseSeeder;
use Modules\Menu\database\seeds\MenusTableSeeder;
use Modules\Settings\database\seeds\SettingsTableSeeder;
use Modules\User\database\seeds\UsersDatabaseSeeder;

use Illuminate\Database\Seeder;

class FoundationDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ActivitiesDatabaseSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(UsersDatabaseSeeder::class);
        $this->call(MenusTableSeeder::class);
    }
}
