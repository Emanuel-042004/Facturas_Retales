<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    
    public function run()
    {
        $role1 = Role::create(['name' => 'Admin']);
        $role2 = Role::create(['name' => 'Digitador']);
        $role3 = Role::create(['name' => 'Aprobador']);
        $role4 = Role::create(['name' => 'Causador']);
        $role5 = Role::create(['name' => 'Pagador']);
        $role6 = Role::create(['name' => 'Causador - Pagador']);

        Permission::create(['name' => 'home'])->syncRoles([$role1]); 
        Permission::create(['name' => 'cargar_pendiente'])->syncRoles([$role1, $role2, $role3]); 
        Permission::create(['name' => 'aprobar_pendiente'])->syncRoles([$role1, $role2, $role3]); 
        Permission::create(['name' => 'rechazar_pendiente'])->syncRoles([$role1, $role2, $role3]); 
        Permission::create(['name' => 'causar'])->syncRoles([$role4, $role6]); 
        Permission::create(['name' => 'aprobar_causacion'])->syncRoles([$role1, $role6]); 
        Permission::create(['name' => 'rechazar_causacion'])->syncRoles([$role1, $role6]);
        Permission::create(['name' => 'carga_egreso'])->syncRoles([$role5, $role6]); 
        Permission::create(['name' => 'rechazar_egreso'])->syncRoles([$role1, $role5, $role6]);
        Permission::create(['name' => 'finalizar'])->syncRoles([$role1, $role5, $role6]); 


        
    }

    
    
}
