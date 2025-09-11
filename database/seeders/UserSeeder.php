<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('password'),
                'role'     => 'admin'
            ]
        );

        User::updateOrCreate(
            ['email' => 'seller@example.com'],
            [
                'name'     => 'Seller',
                'password' => Hash::make('password'),
                'role'     => 'seller'
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name'     => 'Customer',
                'password' => Hash::make('password'),
                'role'     => 'customer'
            ]
        );
    }
}
