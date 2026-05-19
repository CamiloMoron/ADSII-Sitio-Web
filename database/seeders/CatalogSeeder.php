<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Material;
use App\Models\Vehiculo;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Clientes
        $clientes = [
            [
                'nombre' => 'Industrias del Sur S.A.',
                'ruc' => '20100001234',
                'contacto' => 'Juan Pérez',
                'estado' => 'Activo',
            ],
            [
                'nombre' => 'Minera Andina Corp.',
                'ruc' => '20200005678',
                'contacto' => 'Ana Torres',
                'estado' => 'Activo',
            ],
            [
                'nombre' => 'Textil Norte EIRL',
                'ruc' => '20300009012',
                'contacto' => 'Luis Gómez',
                'estado' => 'Inactivo',
            ],
        ];

        foreach ($clientes as $cliente) {
            Cliente::create($cliente);
        }

        // Seed Vehiculos
        $vehiculos = [
            [
                'placa' => 'ABC-123',
                'tipo' => 'Camión Baranda',
                'capacidad' => '8 Ton',
                'estado' => 'Operativo',
            ],
            [
                'placa' => 'XYZ-789',
                'tipo' => 'Cisterna',
                'capacidad' => '12 Ton',
                'estado' => 'En Mantto.',
            ],
            [
                'placa' => 'DEF-456',
                'tipo' => 'Furgón',
                'capacidad' => '5 Ton',
                'estado' => 'Operativo',
            ],
        ];

        foreach ($vehiculos as $vehiculo) {
            Vehiculo::create($vehiculo);
        }

        // Seed Materiales
        $materiales = [
            [
                'codigo' => 'RES-001',
                'nombre' => 'Aceite usado',
                'tipo' => 'Peligroso',
                'unidad' => 'Galón',
            ],
            [
                'codigo' => 'RES-002',
                'nombre' => 'Cartón',
                'tipo' => 'No Peligroso',
                'unidad' => 'Kg',
            ],
            [
                'codigo' => 'RES-003',
                'nombre' => 'Baterías plomo',
                'tipo' => 'Peligroso',
                'unidad' => 'Unidad',
            ],
        ];

        foreach ($materiales as $material) {
            Material::create($material);
        }
    }
}
