<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Officer User',
            'email' => 'officer@example.com',
            'password' => Hash::make('password'),
            'role' => 'officer',
        ]);

        User::factory()->create([
            'name' => 'Bidder User',
            'email' => 'bidder@example.com',
            'password' => Hash::make('password'),
            'role' => 'bidder',
        ]);
    }
}
