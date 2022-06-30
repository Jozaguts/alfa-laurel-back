<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //error el los guard_name
        $roles = [['name' => 'Super Admin'],['name' => 'admin'],['name' => 'control escolar'],['name' => 'maestro']];

        foreach ($roles as $role) {
            Role::create($role);
        }

        User::where('email', '=', 'iscdavidarreola@gmail.com')
            ->first()
            ->assignRole('Super Admin');

        User::where('email', '=', 'alfalaurel.online@outlook.com')
            ->first()
            ->assignRole('admin');
    }
}
