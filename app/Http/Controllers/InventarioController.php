<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\RegistroInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class InventarioController extends Controller
{
    public function index()
    {
        $registros = RegistroInventario::with('supervisor')->orderBy('id', 'asc')->get();
        $materiales = Material::orderBy('nombre')->get();
        return view('inventario.index', compact('registros', 'materiales'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'material' => ['required', 'string', 'max:255'],
            'peso_bruto' => ['required', 'integer', 'min:0'],
            'peso_final' => ['required', 'integer', 'min:0'],
            'zona_almacen' => ['required', 'string', 'max:255'],
        ], [
            'material.required' => 'El material es obligatorio.',
            'peso_bruto.required' => 'El peso bruto es obligatorio.',
            'peso_final.required' => 'El peso final es obligatorio.',
            'zona_almacen.required' => 'La zona de almacén es obligatoria.',
        ]);

        $validated['merma'] = $validated['peso_bruto'] - $validated['peso_final'];
        $validated['estado'] = 'Cerrado';
        $validated['supervisor_id'] = Auth::id();

        RegistroInventario::create($validated);

        return redirect()->route('inventario.index')
            ->with('success', 'Lote cerrado y stock actualizado.');
    }

    public function update(Request $request, RegistroInventario $registro)
    {
        $validated = $request->validate([
            'material' => ['required', 'string', 'max:255'],
            'peso_bruto' => ['required', 'integer', 'min:0'],
            'peso_final' => ['required', 'integer', 'min:0'],
            'zona_almacen' => ['required', 'string', 'max:255'],
            'estado' => ['required', Rule::in(['Cerrado', 'Abierto'])],
        ], [
            'material.required' => 'El material es obligatorio.',
            'peso_bruto.required' => 'El peso bruto es obligatorio.',
            'peso_final.required' => 'El peso final es obligatorio.',
            'zona_almacen.required' => 'La zona de almacén es obligatoria.',
            'estado.required' => 'El estado es obligatorio.',
        ]);

        $validated['merma'] = $validated['peso_bruto'] - $validated['peso_final'];

        $registro->update($validated);

        return redirect()->route('inventario.index')
            ->with('success', 'Registro de inventario actualizado.');
    }

    public function destroy(RegistroInventario $registro)
    {
        $registro->delete();

        return redirect()->route('inventario.index')
            ->with('success', 'Registro de inventario eliminado.');
    }
}
