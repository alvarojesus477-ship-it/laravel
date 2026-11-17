<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario fijo para poder iniciar sesión con Breeze
        User::factory()->create([
            'name'  => 'Admin',
            'email' => 'admin@example.com',
        ]);

        // Usuarios aleatorios
        User::factory(9)->create();
    }
}