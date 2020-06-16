<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin = User::create([
                    'email' => 'superadmin@bumdes.com',
                    'password' => Hash::make('password'),
                ]);
        $superadmin->assignRole('super admin');

        $admin = User::create([
                    'email' => 'admin@bumdes.com',
                    'password' => Hash::make('password'),
                ]);
        $admin->assignRole('admin');
    }
}
