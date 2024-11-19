<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'show admin requests',
            'show requests',
            'add user',
            'low amount requests',
            'higher amount requests',
            'request status',
        ];        

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission , 'guard_name' => 'web']);
        }

        $superAdminRole = Role::firstOrCreate(['name' => "Super Admin" , 'guard_name' => 'web']);
        $superAdminRole->givePermissionTo($permissions);

        $adminAccount = Role::firstOrCreate(['name' => "admin", 'guard_name' => 'web']);
        $adminAccount->givePermissionTo([
            'show admin requests',
            'add user',
            'low amount requests',
            'higher amount requests'
        ]);

        $minAccount = Role::firstOrCreate(['name' => "minAcoountRole", 'guard_name' => 'web']);
        $minAccount->givePermissionTo([
            'show admin requests',
            'add user',
            'low amount requests',
        ]);

        $maxAccount = Role::firstOrCreate(['name' => "maxAcoountRole", 'guard_name' => 'web']);
        $maxAccount->givePermissionTo([
            'show admin requests',
            'add user',
            'higher amount requests',
        ]);

        $userAccount = Role::firstOrCreate(['name' => "userRole", 'guard_name' => 'web']);
        $userAccount->givePermissionTo([
            'show requests',
            'request status'
        ]);

    }
}
