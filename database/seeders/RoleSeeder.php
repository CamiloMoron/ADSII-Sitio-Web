<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Administrador', 'display_name' => 'Administrador'],
            ['name' => 'Asistente Administrativo', 'display_name' => 'Asistente Administrativo'],
            ['name' => 'Supervisor de Planta', 'display_name' => 'Supervisor de Planta'],
            ['name' => 'Chofer', 'display_name' => 'Chofer'],
            ['name' => 'Encargado de Logística', 'display_name' => 'Encargado de Logística'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
