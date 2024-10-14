<?php

namespace Modules\Components\Utility\database\seeds;

use Modules\Menu\Models\Menu;
use Modules\Settings\Models\Setting;
use Modules\User\Models\Permission;
use Illuminate\Database\Seeder;
use Spatie\MediaLibrary\Media;

class UtilityDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UtilityPermissionsDatabaseSeeder::class);
        $this->call(UtilityMenuDatabaseSeeder::class);
        $this->call(UtilitySettingsDatabaseSeeder::class);
    }

    public function rollback()
    {
        Permission::where('name', 'like', 'Utility::%')->delete();

        Menu::where('key', 'utility')
            ->orWhere('active_menu_url', 'like', 'utilitys%')
            ->orWhere('url', 'like', 'utilitys%')
            ->delete();

        Setting::where('category', 'Utilities')->delete();

        Media::whereIn('collection_name', ['utility-media-collection'])->delete();
    }
}
