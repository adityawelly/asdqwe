<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $actions = ['create', 'read', 'update', 'delete'];
        $permissions = [
            ['employee'],
            ['division'],
            ['department'],
            ['job-title'],
            ['grade-title'],
            ['company-region'],
            ['user'],
        ];

        foreach ($permissions as $permission) {
            foreach ($actions as $action) {
                Permission::create(['name' => $action.'-'.$permission]);
            }
        }
    }
}
