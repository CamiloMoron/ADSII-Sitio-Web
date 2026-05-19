<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Material;
use App\Models\User;
use App\Models\Vehiculo;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $stats = [
            'clientes' => Cliente::count(),
            'vehiculos' => Vehiculo::count(),
            'materiales' => Material::count(),
            'usuarios' => User::count(),
        ];

        $menuByRole = config('menu');

        return view('dashboard', compact('stats', 'menuByRole'));
    }
}
