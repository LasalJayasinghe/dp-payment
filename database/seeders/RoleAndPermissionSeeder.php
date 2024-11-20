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
            'check request',
            'waiting request',
            'approve request',
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
            'low amount requests',
            'higher amount requests',
            'check request',
            'waiting request',
            'approve request',
        ]);

        $minAccount = Role::firstOrCreate(['name' => "minAcoountRole", 'guard_name' => 'web']);
        $minAccount->givePermissionTo([
            'show admin requests',
            'check request',
            'low amount requests',
        ]);

        $maxAccount = Role::firstOrCreate(['name' => "maxAcoountRole", 'guard_name' => 'web']);
        $maxAccount->givePermissionTo([
            'show admin requests',
            'check request',
            'higher amount requests',
        ]);

        $userAccount = Role::firstOrCreate(['name' => "userRole", 'guard_name' => 'web']);
        $userAccount->givePermissionTo([
            'show requests',
            'request status'
        ]);

        $account = Role::firstOrCreate(['name' => "account", 'guard_name' => 'web']);
        $account->givePermissionTo([
            'show admin requests',
            'request status',
            'approve request'
        ]);

        $manager = Role::firstOrCreate(['name' => "manager", 'guard_name' => 'web']);
        $manager->givePermissionTo([
            'show admin requests',
            'request status',
            'waiting request'
        ]);

    }
}
