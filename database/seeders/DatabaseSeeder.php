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
    public function run(): void
    {

    \App\Models\tutor::create(['nombre' => 'Mtro. Carlos Francisco Dominguez Dominguez']);
    \App\Models\tutor::create(['nombre' => 'Mtra. Eloisa Ruiz Jimenez']);
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        \App\Models\User::create([
        'name' => 'RafaelLara',
        'email' => 'lykancorp@gmail.com',
        'password' => bcrypt('kodalara99'),
        'role' => 'admin',
    ]);
    }
}
