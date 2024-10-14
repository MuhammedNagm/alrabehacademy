<?php

namespace Modules\Components\Utility\database\seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UtilitySettingsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('settings')->insert([
            [
                'code' => 'utility_google_address_api_key',
                'type' => 'TEXT',
                'category' => 'Utilities',
                'label' => 'Google address api key',
                'value' => 'AIzaSyBrMjtZWqBiHz1Nr9XZTTbBLjvYFICPHDM',
                'editable' => 1,
                'hidden' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'utility_mailchimp_api_key',
                'type' => 'TEXT',
                'category' => 'Utilities',
                'label' => 'Mailchimp API Key',
                'value' => '',
                'editable' => 1,
                'hidden' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'utility_mailchimp_list_id',
                'type' => 'TEXT',
                'category' => 'Utilities',
                'label' => 'Mailchimp List Id',
                'value' => '',
                'editable' => 1,
                'hidden' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'utility_schedule_time',
                'type' => 'SELECT',
                'label' => 'Schedule Time',
                'value' => '{"Off":"- Off -","06":"06 AM","07":"07 AM"}',
                'editable' => 1,
                'hidden' => 0,
                'category' => 'Utilities',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'utility_days_of_the_week',
                'type' => 'SELECT',
                'label' => 'Days of the week',
                'value' => '{"Mon":"Mon","Tue":"Tue","Wed":"Wed","Thu":"Thu","Fri":"Fri","Sat":"Sat","Sun":"Sun"}',
                'editable' => 1,
                'hidden' => 0,
                'category' => 'Utilities',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
