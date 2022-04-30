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
        $roles = [['name' => 'admin'],['name' => 'control escolar'],['name' => 'maestro']];
        $permissions = [
            ['guard_name' => 'sanctum','name' =>'crear examenes'], ['guard_name' => 'sanctum','name' =>'editar examenes'], ['guard_name' => 'sanctum','name' =>'ver examenes'], ['guard_name' => 'sanctum','name' =>'eliminar examenes'],
            ['guard_name' => 'sanctum','name' =>'crear usuario'], ['guard_name' => 'sanctum','name' =>'editar usuario'], ['guard_name' => 'sanctum','name' =>'ver usuario'], ['guard_name' => 'sanctum','name' =>'eliminar usuario'],
            ['guard_name' => 'sanctum','name' =>'crear materias'], ['guard_name' => 'sanctum','name' =>'editar materias'], ['guard_name' => 'sanctum','name' =>'ver materias'], ['guard_name' => 'sanctum','name' =>'eliminar materias']
        ];
        foreach ($roles as $role) {
            Role::create($role);
        }
        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
        User::where('email', '=', 'admin@example.com')
            ->first()
            ->assignRole('admin');
    }
}
