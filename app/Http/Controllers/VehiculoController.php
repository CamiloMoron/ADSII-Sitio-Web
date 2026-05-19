<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehiculos = Vehiculo::orderBy('created_at', 'desc')->get();
        return view('catalogos.vehiculos.index', compact('vehiculos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'placa' => ['required', 'regex:/^[A-Z0-9]{3}-[0-9]{3}$/', 'unique:vehiculos,placa'],
            'tipo' => ['required', 'max:255'],
            'capacidad' => ['required', 'max:50'],
        ], [
            'placa.regex' => 'La placa debe tener formato XXX-999.',
            'placa.unique' => 'Esta placa ya está registrada.',
            'tipo.required' => 'El tipo es obligatorio.',
            'capacidad.required' => 'La capacidad es obligatoria.',
        ]);

        $validated['estado'] = 'Operativo';

        Vehiculo::create($validated);

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo registrado exitosamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehiculo $vehiculo)
    {
        $validated = $request->validate([
            'placa' => ['required', 'regex:/^[A-Z0-9]{3}-[0-9]{3}$/', Rule::unique('vehiculos')->ignore($vehiculo->id)],
            'tipo' => ['required', 'max:255'],
            'capacidad' => ['required', 'max:50'],
            'estado' => ['required', 'in:Operativo,En Mantto.,Inactivo'],
        ], [
            'placa.regex' => 'La placa debe tener formato XXX-999.',
            'placa.unique' => 'Esta placa ya está registrada.',
            'tipo.required' => 'El tipo es obligatorio.',
            'capacidad.required' => 'La capacidad es obligatoria.',
        ]);

        $vehiculo->update($validated);

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehiculo $vehiculo)
    {
        $vehiculo->delete();

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo eliminado exitosamente.');
    }
}
