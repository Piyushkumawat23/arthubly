<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('123456'),
                'role' => 'admin',
            ]
        );

        // Normal User
        User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'Normal User',
                'password' => Hash::make('123456'),
                'role' => 'user',
            ]
        );
    }
}
