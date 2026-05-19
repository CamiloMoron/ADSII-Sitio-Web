<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\OrdenServicioController;
use App\Http\Controllers\OrdenVentaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
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
    
    Route::resource('ordenes-servicio', OrdenServicioController::class);
    
    Route::resource('ordenes-venta', OrdenVentaController::class);
    
    Route::get('/facturas', function () {
        return view('dashboard'); // Temporary
    })->name('facturas.index');
    
    Route::get('/rutas', function () {
        return view('dashboard'); // Temporary
    })->name('rutas.index');
    
    Route::get('/guias', function () {
        return view('dashboard'); // Temporary
    })->name('guias.index');
    
    Route::get('/clasificacion', function () {
        return view('dashboard'); // Temporary
    })->name('clasificacion.index');
    
    Route::get('/inventario', function () {
        return view('dashboard'); // Temporary
    })->name('inventario.index');
});

require __DIR__.'/auth.php';
