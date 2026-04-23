<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'email' => 'admin@gmail.com',
            'password' => bcrypt(value: 'AdminTesec_2026@'),
            'nombre' => 'Root',
            'apellidos' => '',
            'perfil_id' => 1
        ]);
    }
}
