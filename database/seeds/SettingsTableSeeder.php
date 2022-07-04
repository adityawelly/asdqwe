<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            [
                'key' => 'company_name',
                'value' => 'Laravel'
            ],
            [
                'key' => 'company_logo',
                'value' => null
            ],
            [
                'key' => 'default_avatar',
                'value' => null
            ],
            [
                'key' => 'company_address',
                'value' => null
            ],
            [
                'key' => 'company_email',
                'value' => 'email@company.com'
            ],
            [
                'key' => 'company_phone',
                'value' => null
            ],
            [
                'key' => 'use_logo',
                'value' => null
            ],
            [
                'key' => 'dashboard_banner',
                'value' => null
            ],
        ]);
    }
}
