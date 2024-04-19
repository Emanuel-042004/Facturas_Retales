<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        DB::table('model_has_roles')->delete();
        DB::table('roles')->delete();
        $role1 = Role::create(['name' => 'Admin']);
        $role2 = Role::create(['name' => 'Digitador']);
        $role3 = Role::create(['name' => 'Aprobador']);
        $role4 = Role::create(['name' => 'Causador']);
        $role5 = Role::create(['name' => 'Pagador']);
        $role6 = Role::create(['name' => 'Cau-Pag']);
        $role7 = Role::create(['name' => 'Apro-Car']);
        $role8 = Role::create(['name' => 'Digitador-Ap']);

        Permission::create(['name' => 'home'])->syncRoles([$role1]); 
        Permission::create(['name' => 'cargar_pendiente'])->syncRoles([$role1, $role2, $role3, $role8]); 
        Permission::create(['name' => 'aprobar_pendiente'])->syncRoles([$role1, $role8, $role3]); 
        Permission::create(['name' => 'rechazar_pendiente'])->syncRoles([$role1, $role2, $role3]); 
        Permission::create(['name' => 'aprobar_cargada'])->syncRoles([$role1, $role7]); 
        Permission::create(['name' => 'rechazar_cargada'])->syncRoles([$role1, $role7]); 
        Permission::create(['name' => 'causar'])->syncRoles([$role1, $role4, $role6]); 
        Permission::create(['name' => 'aprobar_causacion'])->syncRoles([$role1, $role6, $role4]); 
        Permission::create(['name' => 'rechazar_causacion'])->syncRoles([$role1, $role6, $role4]);
        Permission::create(['name' => 'carga_egreso'])->syncRoles([$role1, $role5, $role6, $role4]); 
        Permission::create(['name' => 'rechazar_egreso'])->syncRoles([$role1, $role5, $role6, $role4]);
        Permission::create(['name' => 'finalizar'])->syncRoles([$role1, $role5, $role6]); 


        
    }

    
    
}
