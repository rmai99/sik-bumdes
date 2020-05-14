<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'company']);
        Role::create(['name' => 'employee']);
        Role::create(['name' => 'super admin']);
        Role::create(['name' => 'admin']);
    }
}
