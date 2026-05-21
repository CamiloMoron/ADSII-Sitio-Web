<?php

namespace Database\Seeders;

use App\Models\OrdenServicio;
use App\Models\Role;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RutasDevelopmentSeeder extends Seeder
{
    public function run(): void
    {
        $choferRole = Role::where('name', 'Chofer')->first();

        $choferes = [
            [
                'nombre' => 'Roberto Díaz',
                'usuario' => 'chofer2',
                'password' => Hash::make('1234'),
                'initials' => 'RD',
                'estado' => 'Activo',
            ],
            [
                'nombre' => 'Sandra Vargas',
                'usuario' => 'chofer3',
                'password' => Hash::make('1234'),
                'initials' => 'SV',
                'estado' => 'Activo',
            ],
        ];

        foreach ($choferes as $data) {
            User::firstOrCreate(
                ['usuario' => $data['usuario']],
                array_merge($data, ['role_id' => $choferRole->id])
            );
        }

        $vehiculos = [
            [
                'placa' => 'GHI-321',
                'tipo' => 'Camión Compactador',
                'capacidad' => '10 Ton',
                'estado' => 'Operativo',
            ],
            [
                'placa' => 'JKL-654',
                'tipo' => 'Camión Baranda',
                'capacidad' => '6 Ton',
                'estado' => 'Operativo',
            ],
        ];

        foreach ($vehiculos as $data) {
            Vehiculo::firstOrCreate(['placa' => $data['placa']], $data);
        }

        $clientes = \App\Models\Cliente::activos()->limit(3)->get();

        if ($clientes->isNotEmpty()) {
            $ordenes = [
                [
                    'cliente_id' => $clientes[0]->id,
                    'sede' => 'Planta Principal - Lima',
                    'ventana_horaria' => '06:00-08:00',
                    'volumen_estimado' => '3 m³',
                    'estado' => 'Aprobada',
                    'fecha' => now()->toDateString(),
                ],
                [
                    'cliente_id' => $clientes[1]->id ?? $clientes[0]->id,
                    'sede' => 'Almacén Norte - Callao',
                    'ventana_horaria' => '09:00-11:00',
                    'volumen_estimado' => '5 m³',
                    'estado' => 'Aprobada',
                    'fecha' => now()->toDateString(),
                ],
                [
                    'cliente_id' => $clientes[2]->id ?? $clientes[0]->id,
                    'sede' => 'Oficina Central - San Isidro',
                    'ventana_horaria' => '14:00-16:00',
                    'volumen_estimado' => '2 m³',
                    'estado' => 'Aprobada',
                    'fecha' => now()->addDay()->toDateString(),
                ],
            ];

            foreach ($ordenes as $data) {
                OrdenServicio::create($data);
            }
        }
    }
}
