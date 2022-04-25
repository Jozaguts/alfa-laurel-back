<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsTableseeder extends Seeder
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
            ['name' =>'crear examenes'], ['name' =>'editar examenes'], ['name' =>'ver examenes'], ['name' =>'eliminar examenes'],
            ['name' =>'crear usuario'], ['name' =>'editar usuario'], ['name' =>'ver usuario'], ['name' =>'eliminar usuario'],
            ['name' =>'crear materias'], ['name' =>'editar materias'], ['name' =>'ver materias'], ['name' =>'eliminar materias']
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