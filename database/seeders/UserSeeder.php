<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Super Admin
        User::create([
            'name' => 'Super Admin Aronta',
            'email' => 'admin@aronta.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
        ]);

        // 2. Akun Supir
        User::create([
            'name' => 'Supir Budi',
            'email' => 'supir@aronta.com',
            'password' => Hash::make('password123'),
            'role' => 'driver',
        ]);

        // 3. Akun Customer
        User::create([
            'name' => 'Customer Setia',
            'email' => 'customer@aronta.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);
    }
}