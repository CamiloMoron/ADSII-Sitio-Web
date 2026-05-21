<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\OrdenServicioController;
use App\Http\Controllers\OrdenVentaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GuiaController;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\VehiculoController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard - requires authentication
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Clientes CRUD
    Route::resource('clientes', ClienteController::class);
    
    // Vehiculos CRUD
    Route::resource('vehiculos', VehiculoController::class);
    
    // Materiales CRUD
    Route::resource('materiales', MaterialController::class);
    
    Route::resource('usuarios', UserController::class);
    // Placeholder routes for menu items (will be implemented later)
    Route::get('/catalogos', function () {
        return redirect()->route('clientes.index'); // Redirect to clientes for now
    })->name('catalogos.index');
    
    Route::resource('ordenes-servicio', OrdenServicioController::class)->parameters(['ordenes-servicio' => 'orden_servicio']);
    
    Route::resource('ordenes-venta', OrdenVentaController::class)->parameters(['ordenes-venta' => 'orden_venta']);
    
    Route::resource('facturas', FacturaController::class)->parameters(['facturas' => 'factura']);
    
    Route::resource('rutas', RutaController::class)->parameters(['rutas' => 'ruta']);
    
    Route::resource('guias', GuiaController::class)->parameters(['guias' => 'guia']);
    
    Route::get('/clasificacion', function () {
        return view('dashboard'); // Temporary
    })->name('clasificacion.index');
    
    Route::get('/inventario', function () {
        return view('dashboard'); // Temporary
    })->name('inventario.index');
});

require __DIR__.'/auth.php';
