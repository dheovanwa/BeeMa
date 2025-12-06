<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 1 Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);

        // Create 5 Dosen users
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => 'Dosen ' . $i,
                'email' => 'd@' . $i,
                'password' => Hash::make('12345678'),
                'role' => 'dosen',
            ]);
        }

        // Create 5 Mahasiswa users
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => 'Mahasiswa ' . $i,
                'email' => 'm@' . $i,
                'password' => Hash::make('12345678'),
                'role' => 'mahasiswa',
            ]);
        }
    }
}
