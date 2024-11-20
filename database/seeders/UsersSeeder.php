<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminUser = User::create([
            'name' => 'Admin User',
            'role' => 'admin',
            'email' => 'superAdmin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $superAdminUser->assignRole('Super Admin');    

    
    }
}
