<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tutor; // <-- IMPORTANTE: Esta línea le dice a PHP dónde está el modelo Tutor
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Creamos los tutores (maestros)
        Tutor::create(['nombre' => 'Mtro. Carlos Francisco Dominguez Dominguez']);
        Tutor::create(['nombre' => 'Mtra. Eloisa Ruiz Jimenez']);

        // 2. Creamos tu usuario Admin oficial
        User::create([
            'name' => 'Rafael Lara',
            'email' => 'lykancorp@gmail.com',
            'password' => bcrypt('kodalara99'),
            'role' => 'admin',
        ]);

        // Opcional: El usuario de prueba de Laravel (puedes dejarlo o borrarlo)
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}