<?php

namespace Modules\User\database\seeds;

use Carbon\Carbon;
use Modules\User\Communication\database\seeds\NotificationDatabaseSeeder;
use Modules\User\Models\Role;
use Modules\User\Models\User;
use Illuminate\Database\Seeder;

class UsersDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionsDatabaseSeeder::class);
        $this->call(RolesDatabaseSeeder::class);
        $this->call(NotificationDatabaseSeeder::class);
        $this->call(UserNotificationTemplatesSeeder::class);
        $this->call(UsersSettingsDatabaseSeeder::class);


        \DB::table('users')->delete();

        $superuser_id = \DB::table('users')->insertGetId([
            'name' => 'Super User',
            'email' => 'superuser@developnet.net',
            'password' => bcrypt('123456'),
            'job_title' => 'Administrator',
            'address' => null,
            'confirmed_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $superuser_role = Role::whereName('superuser')->first();

        if ($superuser_role) {
            $superuser_role->users()->attach($superuser_id);
        }

        User::create([
            'name' => 'Modules Member',
            'email' => 'member@developnet.net',
            'password' => '123456',
            'job_title' => 'Ads Coordinator',
            'address' => null,
            'confirmed_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
