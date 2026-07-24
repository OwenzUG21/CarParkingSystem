<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    // public function run(): void
    // {
    //     // User::factory(10)->create();

    //     User::updateOrCreate(
    //         ['email' => 'test@example.com'],
    //         ['name' => 'Test User']
    //     );

    //     $this->call([
    //         ParkingLocationSeeder::class,
    //     ]);
    // }

    public function run(): void
{
    \App\Models\User::updateOrCreate(
        ['email' => 'test@example.com'],
        [
            'name' => 'Test User',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'is_admin' => true, // Add this since your migration has an is_admin flag
            'role' => 'admin',  // Add this if your migration requires it
        ]
    );

    $this->call([
        ParkingLocationSeeder::class,
    ]);
}
}
