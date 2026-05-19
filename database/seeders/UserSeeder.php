<?php

namespace Database\Seeders;

use App\Models\Role;
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
        $users = [
            [
                'nombre' => 'Admin General',
                'usuario' => 'admin',
                'password' => Hash::make('1234'),
                'initials' => 'AG',
                'role_name' => 'Administrador',
                'estado' => 'Activo',
            ],
            [
                'nombre' => 'María López',
                'usuario' => 'asistente',
                'password' => Hash::make('1234'),
                'initials' => 'ML',
                'role_name' => 'Asistente Administrativo',
                'estado' => 'Activo',
            ],
            [
                'nombre' => 'Carlos Ruiz',
                'usuario' => 'supervisor',
                'password' => Hash::make('1234'),
                'initials' => 'CR',
                'role_name' => 'Supervisor de Planta',
                'estado' => 'Activo',
            ],
            [
                'nombre' => 'Juan Morales',
                'usuario' => 'chofer',
                'password' => Hash::make('1234'),
                'initials' => 'JM',
                'role_name' => 'Chofer',
                'estado' => 'Activo',
            ],
            [
                'nombre' => 'Patricia Gómez',
                'usuario' => 'logistica',
                'password' => Hash::make('1234'),
                'initials' => 'PG',
                'role_name' => 'Encargado de Logística',
                'estado' => 'Activo',
            ],
        ];

        foreach ($users as $userData) {
            $role = Role::where('name', $userData['role_name'])->first();
            
            User::create([
                'nombre' => $userData['nombre'],
                'usuario' => $userData['usuario'],
                'password' => $userData['password'],
                'initials' => $userData['initials'],
                'role_id' => $role->id,
                'estado' => $userData['estado'],
            ]);
        }
    }
}
