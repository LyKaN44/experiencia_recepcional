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

    \App\Models\Tutor::create(['nombre' => 'Mtro. Carlos Francisco Dominguez Dominguez']);
    \App\Models\Tutor::create(['nombre' => 'Mtra. Eloisa Ruiz Jimenez']);
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        \App\Models\User::create([
        'name' => 'Rafael Lara',
        'email' => 'lykancorp@gmail.com', // El correo que quieras
        'password' => bcrypt('kodalara99'), // Tu contraseña
        'role' => 'admin', // O como se llame tu campo de rol en la tabla users
    ]);
    }
}
