<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'Admin'
        ]);

        // Create sample users
        $users = [
            [
                'name' => 'HANNAN AZHARI BATUBARA',
                'email' => 'hannan@example.com',
                'password' => Hash::make('password'),
                'role' => 'Mahasiswa'
            ],
            [
                'name' => 'Rita Isdaryini',
                'email' => 'rita@example.com',
                'password' => Hash::make('password'),
                'role' => 'Petani'
            ],
            [
                'name' => 'syamsul',
                'email' => 'syamsul@example.com',
                'password' => Hash::make('password'),
                'role' => 'Petani'
            ],
            [
                'name' => 'reno',
                'email' => 'reno@example.com',
                'password' => Hash::make('password'),
                'role' => 'Petani'
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}